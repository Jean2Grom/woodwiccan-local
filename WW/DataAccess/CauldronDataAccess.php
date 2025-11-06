<?php
namespace WW\DataAccess;

use WW\WoodWiccan;
use WW\Cauldron;
use WW\Cauldron\Ingredient;
use WW\Witch;

class CauldronDataAccess
{    
    const RELATIONSHIPS_JOINTURE = [
        'siblings' => "siblingsJointure",
        'parents'  => "parentsJointure",
        'children' => "childrenJointure",
    ];

    static function getDepth( WoodWiccan $ww, bool $useCache=true ): int
    {
        if( $useCache ){
            $depth = $ww->cache->read( 'system', 'depth-cauldron' );
        }
        
        if( empty($depth) )
        {
            $query  =   "SHOW COLUMNS FROM `cauldron` WHERE `Field` LIKE 'level_%'";
            $result =   $ww->db->selectQuery($query);
            $depth  =   count($result);
            
            if( $useCache ){
                $ww->cache->create('system', 'depth-cauldron', $depth);
            }
        }
        
        return (int) $depth;
    }

    
    static function cauldronRequest( WoodWiccan $ww, array $configuration, bool $getWitches=true )
    {
        if( !$configuration ){
            return [];
        }

        // Determine the list of fields in select part of query
        $query  =   "SELECT DISTINCT `c`.`".implode( "`, `c`.`", Cauldron::FIELDS)."` ";

        $prefix = "`c`.`level_"; 
        $query  .=  ", ".$prefix.implode("`, ".$prefix, range(1, $ww->cauldronDepth))."` ";

        $excludFields = [
            'cauldron_fk',
        ];
        foreach( Ingredient::DEFAULT_AVAILABLE_INGREDIENT_TYPES_PREFIX as $type => $prefix ){
            foreach( Ingredient::FIELDS as $field ){
                if( !in_array($field, $excludFields) ){
                    $query  .=  ", `".$prefix."`.`".$field."` AS `".$prefix."_".$field."` ";
                }
            }
        }

        if( $getWitches )
        {
            foreach( Witch::FIELDS as $field ){
                $query  .=  ", `w`.`".$field."` AS `w_".$field."` ";
            }
            
            foreach( range(1, $ww->depth) as $i ){
                $query  .=  ", `w`.`level_".$i."` AS `w_level_".$i."` ";
            }
        }

        
        $query  .= "FROM ";

        // $userConnexionJointure = false;
        // if( in_array('user', $configuration) && $ww->user->connexion )
        // {
        //     $userConnexionJointure = true;
        //     $query  .= "`ingredient__integer` AS `user_connexion`, ";
        // }
        
        $query  .= "`cauldron` AS `c` ";
        foreach( Ingredient::DEFAULT_AVAILABLE_INGREDIENT_TYPES_PREFIX as $type => $prefix )
        {
            $query  .=  "LEFT JOIN `ingredient__".$type."` AS `".$prefix."` ";
            $query  .=      "ON `".$prefix."`.`cauldron_fk` = `c`.`id` ";
        }
        
        if( $getWitches )
        {
            $query  .= "LEFT JOIN `witch` AS `w` ";
            $query  .=  "ON `w`.`cauldron` = `c`.`id` ";
        }

        $query  .= "LEFT JOIN `cauldron` AS `c_ref` ";
        $query  .=  "ON ( ";
        $query  .=  self::childrenJointure( $ww->cauldronDepth, "c_ref", 'c', '*' );
        $query  .=  ") ";            

        $parameters =   [];
        $query      .=  "WHERE ";

        $condition  =   " %s.`id` IN ( ";
        $separator  =   " ";
        foreach( $configuration as $conf )
        {
            if( ctype_digit(strval($conf)) )
            {
                $parameters[ 'c_'.$conf ]    = (int) $conf;
    
                $condition  .=  $separator.":c_".$conf." ";
                $separator  =   ", ";

            }
        }
        $condition .=  ") ";

        $query      .=  str_replace(' %s.', ' `c`.', $condition);
        $query      .=  "OR ".str_replace(' %s.', " `c_ref`.", $condition);
        
        // if( $userConnexionJointure )
        // {
        //     $query      .=  $separator;
        //     $separator  =   "OR ";

        //     $query  .=  "( ";
        //     $query  .=      " `c`.`id` = `user_connexion`.`cauldron_fk` ";
        //     $query  .=          "OR  `c_user`.`id` = `user_connexion`.`cauldron_fk` ";
        //     $query  .=  ") ";

        //     $query  .=  "AND `user_connexion`.`id` IS NOT NULL ";
        //     $query  .=  "AND `user_connexion`.`name` = \"user__connexion\" ";
        //     $query  .=  "AND `user_connexion`.`value` = :user_id ";

        //     $parameters[ 'user_id' ] = (int) $ww->user->id;
        // }

        return $ww->db->selectQuery($query, $parameters);
    }

    private static function childrenJointure( $maxDepth, $mother, $daughter, $depth=1 )
    {
        $m = function (int $level) use ($mother): string {
            return "`".$mother."`.`level_".$level."`";
        };
        $d = function (int $level) use  ($daughter): string {
            return "`".$daughter."`.`level_".$level."`";
        };
        
        $jointure = "( `".$mother."`.`id` <> `".$daughter."`.`id` ) ";
        
        $jointure  .=      "AND ( ";
        $jointure  .=          "( ".$m(1)." IS NOT NULL AND ".$d(1)." = ".$m(1)." ) ";
        $jointure  .=          "OR ( ".$m(1)." IS NULL AND ".$d(1)." IS NOT NULL ) ";
        $jointure  .=      ") ";
        
        for( $i=2; $i <= $maxDepth; $i++ )
        {
            $jointure  .=  "AND ( ";
            $jointure  .=      "( ".$m($i)." IS NOT NULL AND ".$d($i)." = ".$m($i)." ) ";
            $jointure  .=      "OR ( ".$m($i)." IS NULL AND ".$m($i-1)." IS NOT NULL AND ".$d($i)." IS NOT NULL ) ";
            $jointure  .=      "OR (  ".$m($i)." IS NULL AND ".$m($i-1)." IS NULL ";
            // Apply level
            if( $depth != '*' && ($depth + $i - 1) <= $maxDepth ){
                $jointure  .=       "AND ".$d($depth + $i - 1)." IS NULL ";
            }
            $jointure  .=      ") ";
            $jointure  .=  ") ";
        }
        
        return $jointure;
    }
    
    private static function parentsJointure( $maxDepth, $daughter, $mother, $depth=1 ){
        return self::childrenJointure( $maxDepth, $mother, $daughter, $depth );
    }

    private static function siblingsJointure( $maxDepth, $witch, $sister, $depth=1 )
    {
        $w = function (int $level) use ($witch): string {
            return "`".$witch."`.`level_".$level."`";
        };
        $s = function (int $level) use  ($sister): string {
            return "`".$sister."`.`level_".$level."`";
        };
        
        $jointure = "( `".$witch."`.`id` <> `".$sister."`.`id` ) ";
        
        for( $i=1; $i < $maxDepth; $i++ )
        {
            $jointure  .=  "AND ( ";
            $jointure  .=      "( ".$w($i)." IS NOT NULL AND ".$w($i+1)." IS NOT NULL AND ".$s($i)." = ".$w($i)." ) ";
            $jointure  .=      "OR ( ".$w($i)." IS NOT NULL AND ".$w($i+1)." IS NULL AND ".$s($i)." IS NOT NULL ) ";
            
            if( $i == 1 ){
                $jointure  .=      "OR ( ".$w($i)." IS NULL AND ".$s($i)." IS NULL ) ";
            }
            elseif( $depth != '*' && ($i + 1 - $depth) > 0 )
            {
                $jointure  .=      "OR ( ".$w($i)." IS NULL AND ".$w($i + 1 - $depth)." IS NULL AND ".$s($i)." IS NULL ) ";
                $jointure  .=      "OR ( ".$w($i)." IS NULL AND ".$w($i + 1 - $depth)." IS NOT NULL ) ";
                
            }
            else {
                $jointure  .=      "OR ( ".$w($i)." IS NULL ) ";
            }
            
            $jointure  .=  ") ";
        }
        
        $jointure  .=      "AND ( ";
        $jointure  .=          "( ".$w($maxDepth)." IS NOT NULL AND ".$s($maxDepth)." IS NOT NULL ) ";
        if( $depth != '*' && ($maxDepth + 1 - $depth) > 0 )
        {
            $jointure  .=          "OR ( ".$w($maxDepth)." IS NULL AND ".$w($maxDepth + 1 - $depth)." IS NULL AND ".$s($maxDepth)." IS NULL ) ";
            $jointure  .=          "OR ( ".$w($maxDepth)." IS NULL AND ".$w($maxDepth + 1 - $depth)." IS NOT NULL ) ";
        }
        else {
            $jointure  .=      "OR ( ".$w($maxDepth)." IS NULL ) ";
        }
        $jointure  .=      ") ";
        
        return $jointure;
    }

    static function addLevel( WoodWiccan $ww ): int
    {
        $ww->cache->delete( 'system', 'depth-cauldron' );
        $newLevelDepth = self::getDepth($ww, false) + 1;
        
        $query  =   "ALTER TABLE `cauldron` ";
        $query  .=  "ADD `level_".$newLevelDepth."` INT(11) UNSIGNED NULL DEFAULT NULL ";
        $query  .=  ", ADD KEY `IDX_level_".$newLevelDepth."` (`level_".$newLevelDepth."`) ";
        
        $ww->db->alterQuery($query);
        $ww->cauldronDepth = $newLevelDepth;
        
        return $newLevelDepth;
    }

    
    static function getNewPosition( Cauldron $cauldron )
    {
        $depth = count($cauldron->position()) + 1;
        
        if( $depth > $cauldron->ww->cauldronDepth ){
            return 1;
        }

        $params = [];
        $query  = "SELECT MAX(`level_".$depth."`) AS `maxIndex` FROM `cauldron` ";
        
        $linkingCondition = "WHERE ";
        foreach( $cauldron->position() as $level => $levelPosition )
        {
            $field              =   "level_".$level;
            $query              .=  $linkingCondition."`".$field."` = :".$field." ";
            $params[ $field ]   =   $levelPosition;
            $linkingCondition   =   "AND ";
        }
        
        $result = $cauldron->ww->db->fetchQuery($query, $params);
        
        if( !$result ){
            return false;
        }
        
        $max = (int) $result["maxIndex"];
        
        return $max + 1;
    }

    static function insert( WoodWiccan $ww, array $params )
    {
        $query = "";
        $query  .=  "INSERT INTO `cauldron` ";
        
        $separator = "( ";
        foreach( array_keys($params) as $field )
        {
            $query  .=  $separator."`".$field."` ";
            $separator = ", ";
        }
        $query  .=  ") VALUES ";
        
        $separator = "( ";
        foreach( array_keys($params) as $field )
        {
            $query  .=  $separator.":".$field." ";
            $separator = ", ";
        }
        $query  .=  ") ";
        
        return $ww->db->insertQuery($query, $params);
    }

    static function update( WoodWiccan $ww, array $params, array $conditions )
    {
        $query = "";
        $query  .=  "UPDATE `cauldron` ";
        
        $bindParams         = [];
        $paramQueryElement  = [];
        foreach( $params as $field => $value )
        {
            $key                    = "param__".$field;
            $bindParams[ $key ]     = $value;
            $paramQueryElement[]    = '`'.$ww->db->escape_string($field)."` = :".$key." ";
        }
        $query  .=  "SET ".implode( ", ", $paramQueryElement );

        $condQueryElement  = [];
        foreach( $conditions as $field => $value )
        {
            $key                    = "cond__".$field;
            $bindParams[ $key ]     = $value;
            $condQueryElement[]    = '`'.$ww->db->escape_string($field)."` = :".$key." ";
        }
        $query  .=  "WHERE ".implode( "AND ", $condQueryElement );
        
        return $ww->db->updateQuery( $query, $bindParams );
    }

    static function delete( WoodWiccan $ww, array $conditions )
    {
        if( empty($conditions) ){
            return false;
        }

        $query = "";
        $query  .=  "DELETE FROM `cauldron` ";
        
        $separator = "WHERE ";
        foreach( array_keys($conditions) as $field )
        {
            $query      .=  $separator.'`'.$ww->db->escape_string($field)."` = :".$field." ";
            $separator  =  "AND ";
        }

        return $ww->db->deleteQuery( $query,  $conditions );
    }
    
    static function getStorageStructure( WoodWiccan $ww )
    {
        $query = "";
        $separator = "SELECT DISTINCT ";
        foreach( Cauldron::FIELDS as $field )
        {
            $query      .=  $separator."`c`.`".$field."` ";
            $separator  =   ", ";
        }
        for( $i=1; $i<=$ww->cauldronDepth; $i++ ){
            $query      .=  $separator."`c`.`level_".$i."` ";
        }
        $query  .= "FROM `cauldron` AS `c` ";
        $query  .=  "WHERE `c`.`level_3` IS NULL ";

        return $ww->db->selectQuery( $query );
    }

    static function fetchConnectedData( WoodWiccan $ww, string $table, array $conditions=[] )
    {
        $selectConnectedData = self::selectConnectedData($ww, $table, $conditions);
        if( count($selectConnectedData) === 1 ){
            return array_values($selectConnectedData)[0];
        }

        return $selectConnectedData;
    }

    static function selectConnectedData( WoodWiccan $ww, string $table, array $conditions=[] )
    {
        $params = [];
        $lines  = [];
        foreach( $conditions as $field => $value )
        {
            $escapedField = $ww->db->escape_string($field);
            if( is_array($value) )
            {
                $keys = [];
                foreach( $value as $i => $valueItem )
                {
                    $key = $escapedField."_".$i;
                    $params[ $key ] = $valueItem;
                    $keys[]         = $key;
                }
                $lines[] = " `".$escapedField."` IN ( :".implode(", :", $keys)." ) ";
            }
            else 
            { 
                $params[ $escapedField ] = $value;
                $lines[] = "`".$escapedField."` = :".$escapedField." ";
            }
        }

        $query = "";
        $query  .= "SELECT * ";
        $query  .= "FROM `".$ww->db->escape_string($table)."` ";

        if( $lines ){
            $query  .= "WHERE ".implode( ", ", $lines )." ";
        }

        return $ww->db->selectQuery( $query, $params );
    }


    static function insertConnectedData( WoodWiccan $ww, string $table, array $values )
    {
        $params = [];
        foreach( $values as $field => $value ){
            $params[ $ww->db->escape_string($field) ] = $value;
        }

        $query = "";
        $query .=   "INSERT INTO `".$ww->db->escape_string($table)."` ";
        $query .=   "( `".implode("`, `", array_keys($params))."` ) ";
        $query .=   "VALUES ( :".implode(" , :", array_keys($params))." ) ";
        
        return $ww->db->insertQuery($query, $params);;
    }
    
    static function updateConnectedData( WoodWiccan $ww, string $table, array $updates, array $conditions )
    {
        $query  = "";
        $params = [];
        $query  .=  "UPDATE `".$ww->db->escape_string($table)."` ";

        $separator = "SET ";
        foreach( $updates as $field => $value )
        {
            $escapedField   =   $ww->db->escape_string($field);
            $key            =   'upt__'.$escapedField;
            $params[ $key ] =   $value;
            $query          .=  $separator.'`'.$escapedField."` = :".$key." ";
            $separator      =   ", ";
        }

        $separator = "WHERE ";
        foreach( $conditions as $field => $value )
        {
            $escapedField   =   $ww->db->escape_string($field);
            $key            =   'cond__'.$escapedField;
            $params[ $key ] =   $value;
            $query          .=  $separator.'`'.$escapedField."` = :".$key." ";
            $separator      =   ", ";
        }
        
        return $ww->db->updateQuery( $query, $params );
    }


    static function deleteConnectedData( WoodWiccan $ww, string $table, array $conditions )
    {
        $params             = [];
        $queryConditions    = [];
        foreach( $conditions as $field => $value )
        {
            $escapedField               = $ww->db->escape_string($field);
            $params[ $escapedField ]    = $value;
            $queryConditions[]          = "`".$escapedField."` = :".$escapedField." ";
        }
        
        $query = "";
        $query .=   "DELETE FROM `".$ww->db->escape_string($table)."` ";
        $query .=   "WHERE ".implode("AND ", $queryConditions)." ";
        
        return $ww->db->deleteQuery($query, $params);;
    }


}