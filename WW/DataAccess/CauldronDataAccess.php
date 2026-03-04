<?php
namespace WW\DataAccess;

use WW\WoodWiccan;
use WW\Cauldron;
use WW\Cauldron\Ingredient;
use WW\Witch;

class CauldronDataAccess
{
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
        //$query  =   "SELECT DISTINCT `c`.`".implode( "`, `c`.`", Cauldron::FIELDS)."` ";
        $query  =   "SELECT `c`.`".implode( "`, `c`.`", Cauldron::FIELDS)."` ";

        $prefix = "`c`.`level_"; 
        $query  .=  ", ".$prefix.implode("`, ".$prefix, range(1, $ww->cauldronDepth))."` ";

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
        $query  .= "`cauldron` AS `c` ";

        $query  .= "LEFT JOIN `cauldron` AS `c_ref` ";
        $query  .=  "ON ";

        $jointureConditions = [];
        for( $i=1; $i <= $ww->cauldronDepth; $i++ )
        {
            $jointure   =   "( ";
            $jointure  .=       "( `c_ref`.`level_".$i."` IS NOT NULL ";
            $jointure  .=       "AND `c`.`level_".$i."` = `c_ref`.`level_".$i."` ) ";
            $jointure  .=       "OR ( `c_ref`.`level_".$i."` IS NULL ";
            $jointure  .=       " ) ";
            $jointure   .=  ") ";

            $jointureConditions[] = $jointure;
        }

        $query  .=  implode( "AND ", $jointureConditions);        

        if( $getWitches )
        {
            $query  .= "LEFT JOIN `witch` AS `w` ";
            $query  .=  "ON `w`.`cauldron` = `c`.`id` ";
        }

        $parameters = [];
        $conditions = [];
        foreach( $configuration as $conf ){
            if( ctype_digit(strval($conf)) )
            {
                $parameters[ 'c_'.$conf ]   = (int) $conf;
                $conditions[]               =  ":c_".$conf." ";
            }
        }
        
        $query  .=  "WHERE `c_ref`.`id` ";
        $query  .=  "IN ( ".implode(", ", $conditions)." ) ";

        $orderBy = [];
        for( $i=1; $i <= $ww->cauldronDepth; $i++ )
        {
            $orderBy[] = "`level_".$i."`";
        }

        $query .=  "ORDER BY ".implode( ', ', $orderBy );

        return $ww->db->selectQuery($query, $parameters);
    }

    static function ingredientsRequest( WoodWiccan $ww, array $configuration )
    {
        if( !$configuration ){
            return [];
        }

        // Determine the list of fields in select part of query
        //$query  =   "SELECT DISTINCT `c`.`id` ";
        $query  =   "SELECT `c`.`id` ";
        
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

        $query  .= "FROM `cauldron` AS `c` ";
        foreach( Ingredient::DEFAULT_AVAILABLE_INGREDIENT_TYPES_PREFIX as $type => $prefix )
        {
            $query  .=  "LEFT JOIN `ingredient__".$type."` AS `".$prefix."` ";
            $query  .=      "ON `".$prefix."`.`cauldron_fk` = `c`.`id` ";
        }
        
        $parameters = [];
        $conditions = [];
        foreach( $configuration as $conf ){
            if( ctype_digit(strval($conf)) )
            {
                $parameters[ 'c_'.$conf ]   = (int) $conf;
                $conditions[]               =  ":c_".$conf." ";
            }
        }
        
        $query  .=  "WHERE `c`.`id` ";
        $query  .=  "IN ( ".implode(", ", $conditions)." ) ";

        $query .=  "ORDER BY `c`.`id` ";

        return $ww->db->selectQuery($query, $parameters);
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