<?php
namespace WW\DataAccess;

use WW\WoodWiccan;
use WW\Cairn;

/**
 * Class to aggregate Witch related data access functions
 * all these functions are statics
 * 
 * @author Jean2Grom
 */
class WitchDataAccess
{

    static function getDepth( WoodWiccan $ww, bool $useCache=true ): int
    {
        if( $useCache ){
            $depth = $ww->cache->read( 'system', 'depth' );
        }
        
        if( empty($depth) )
        {
            $query  =   "SHOW COLUMNS FROM `witch` WHERE `Field` LIKE 'level_%'";
            $result =   $ww->db->selectQuery($query);
            $depth  =   count($result);
            
            if( $useCache ){
                $ww->cache->create('system', 'depth', $depth);
            }
        }
        
        return (int) $depth;
    }
    
    static function fetch( WoodWiccan $ww, int $id )
    {
        if( empty($id) ){
            return false;
        }

        $query = "";
        $query  .=  "SELECT * FROM witch ";
        $query  .=  "WHERE id = :id ";

        $data = $ww->db->fetchQuery($query, [ 'id' => $id ]);
        
        return $data;
    }

    static function search( WoodWiccan $ww, array $params, bool $or=false )
    {
        if( !$params ){
            return false;
        }

        $query = "";
        $query  .=  "SELECT * FROM witch ";
        $query  .=  "WHERE ";

        $queryParts = [];
        foreach( array_keys($params) as $key ){
            $queryParts[] = $key." = :".$key." ";
        }

        $query  .=  implode( ($or? "OR ": "AND "), $queryParts );
        
        return $ww->db->selectQuery( $query, $params );
    }
    
    static function update( WoodWiccan $ww, array $params, array $conditions )
    {
        if( empty($params) || empty($conditions) ){
            return false;
        }
        
        $query = "";
        $query  .=  "UPDATE `witch` ";
        
        $separator = "SET ";
        foreach( array_keys($params) as $field )
        {
            $query      .=  $separator.'`'.$ww->db->escape_string($field)."` = :".$field." ";
            $separator  =  ", ";
        }
        
        $separator = "WHERE ";
        foreach( $conditions as $field => $condition )
        {
            $key = "condition_".$field;

            $query      .=  $separator.'`'.$ww->db->escape_string($field)."` = :".$key." ";
            $separator  =  "AND ";

            $params[ $key ] = $condition;
        }

        return $ww->db->updateQuery( $query, $params );
    }
    
    static function updates( WoodWiccan $ww, array $params, array $conditions )
    {
        if( empty($params) 
            || empty($conditions) 
            || array_keys($params) !== array_keys($conditions)
            || empty(array_values( $params )[0] ?? null) 
            || empty(array_values( $conditions )[0] ?? null)  ){
            return false;
        }
        
        $query = "";
        $query  .=  "UPDATE `witch` ";
        
        $separator = "SET ";
        foreach( array_keys( array_values($params)[0] ) as $field )
        {
            $query      .=  $separator.'`'.$ww->db->escape_string($field)."` = :".$field." ";
            $separator  =  ", ";
        }
        
        $separator = "WHERE ";
        foreach( array_keys( array_values($conditions)[0] ) as $field )
        {
            $key = "condition_".$field;

            $query      .=  $separator.'`'.$ww->db->escape_string($field)."` = :".$key." ";
            $separator  =  "AND ";

            foreach( array_keys($params) as $i ){
                $params[ $i ][ $key ] = $conditions[ $i ][ $field ];
            }
        }
        
        return $ww->db->updateQuery( $query, $params, true );
    }
    
    static function create( WoodWiccan $ww, array $params )
    {
        if( isset($params['id']) ){
            unset($params['id']);
        }
        if( isset($params['datetime']) ){
            unset($params['datetime']);
        }
        
        $query = "";
        $query  .=  "INSERT INTO `witch` ";
        
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
    
    static function increasePlateformDepth( WoodWiccan $ww ): int
    {
        $ww->cache->delete( 'system', 'depth' );
        $newLevelDepth = self::getDepth($ww, false) + 1;
        
        $query  =   "ALTER TABLE `witch` ";
        $query  .=  "ADD `level_".$newLevelDepth."` INT(11) UNSIGNED NULL DEFAULT NULL ";
        $query  .=  ", ADD KEY `IDX_level_".$newLevelDepth."` (`level_".$newLevelDepth."`) ";
        
        $ww->db->alterQuery($query);
        $ww->depth = $newLevelDepth;
        
        return $newLevelDepth;
    }
    
    static function getNewDaughterIndex( WoodWiccan $ww, array $position=[] )
    {
        $depth = count($position) + 1;
        
        $params = [];
        $query  = "SELECT MAX(`level_".$depth."`) AS `maxIndex` FROM `witch` ";
        
        $linkingCondition = "WHERE ";
        foreach($position as $level => $levelPosition )
        {
            $field              =   "level_".$level;
            $query              .=  $linkingCondition."`".$field."` = :".$field." ";
            $params[ $field ]   =   $levelPosition;
            $linkingCondition   =   "AND ";
        }
        
        $result = $ww->db->fetchQuery($query, $params);
        
        if( !$result ){
            return false;
        }
        
        $max = (int) $result["maxIndex"];
        
        return $max + 1;
    }
        
    static function getUrlsData(  WoodWiccan $ww, string $site, array $urls, ?int $excludedId=null )
    {
        $params = [ 
            'site'      => $site,
        ];
        
        foreach( $urls as $i => $url )
        {
            $params['url_'.$i]      = $url;
            $params['regexp_'.$i]   = '^'. $url.'-[0-9]+$';            
        }
        
        $query = "";
        $query  .=  "SELECT `url` ";
        $query  .=  "FROM `witch` ";
        $query  .=  "WHERE `site` = :site ";
        if( $excludedId )
        {
            $query  .=  "AND `id` <> :excludedId ";
            $params['excludedId'] = $excludedId;
        }
        $query  .=  "AND ( ";
        
        $separator = "";
        foreach( array_keys($urls) as $i )
        {
            $query  .=      $separator."`url` = :url_".$i." ";
            $query  .=      "OR `url` REGEXP :regexp_".$i." ";
            $separator =  "OR ";
        }
        $query  .=  ") ";
        
        return $ww->db->selectQuery($query, $params);
    }
    
    static function fetchAncestors( WoodWiccan $ww, int $witchId, bool $toRoot=true, mixed $sitesRestriction=null )
    {
        $depth = 1;
        if( $toRoot ){
            $depth = '*';
        }
        
        $configuration = [
            'fetchAncestors' => [
                'id'    => $witchId,
                'craft' => false,
                'parents' => [
                    'depth' => $depth,
                    'craft' => false,
                ]
            ]
        ];
        
        $website = clone $ww->website;
        if( $sitesRestriction ){
            $website->sitesRestrictions  = $sitesRestriction;
        }
        
        $witches        = Summoning::witches($ww, Cairn::prepareConfiguration($website, $configuration) );
        
        if( empty($witches['fetchAncestors']) ){
            return false;
        }
        
        return $witches['fetchAncestors']->mother;
    }
    
    static function fetchDescendants(  WoodWiccan $ww, int $witchId, bool $completeSubtree=true, ?array $sitesRestriction=null ): array
    {
        $depth = 1;
        if( $completeSubtree ){
            $depth = '*';
        }
                
        $configuration = [
            'fetchDescendants' => [
                'id'    => $witchId,
                'craft' => false,
                'children' => [
                    'depth' => $depth,
                    'craft' => false,
                ]
            ]
        ];
        
        $website = clone $ww->website;
        if( $sitesRestriction ){
            $website->sitesRestrictions  = $sitesRestriction;
        }
        
        $witches = Summoning::witches($ww, Cairn::prepareConfiguration($website, $configuration) );
        
        return $witches['fetchDescendants']->daughters ?? [];
    }
    
    static function delete( WoodWiccan $ww, array $witchesToDeleteIds ): bool
    {
        if( empty($witchesToDeleteIds) ){
            return true;
        }
        
        $params = [];
        foreach( $witchesToDeleteIds as $i => $id ){
            $params[ 'id'.$i ] = $id;
        }
        
        $query = "";
        $query  .=  "DELETE FROM `witch` ";
        $query  .=  "WHERE `id` IN ( ";
        $query  .=  ":".implode(", :", array_keys($params));
        $query  .=  " ) ";
        
        return $ww->db->deleteQuery($query, $params);
    }
    
}
