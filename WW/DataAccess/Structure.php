<?php
namespace WW\DataAccess;

use WW\WoodWiccan;
use WW\Craft;

/**
 * Class to aggregate Structure related data access functions
 * all these functions are statics
 * 
 * @author Jean2Grom
 */
class Structure 
{
    const CACHE_FOLDER = "structure";
    
    static function readTableStructure( WoodWiccan $ww, string $table ) 
    {
        if( empty($table) ){
            return false;
        }
        
        $columns = $ww->cache->read( self::CACHE_FOLDER, $table );
        
        if( is_null($columns) )
        {
            $query  =   "SHOW COLUMNS FROM `".$ww->db->escape_string($table)."` ";
            
            $result = $ww->db->selectQuery($query);
            
            if( $result === false ){
                return false;
            }
            
            $columns  = [];
            foreach( $result as $columnItem ){
                $columns[ $columnItem["Field"] ] = $columnItem;
            }
            
            $ww->cache->create( self::CACHE_FOLDER, $table, $columns );
        }
        
        return $columns;
    }
    
    static function readTableCreateTime( WoodWiccan $ww, string $table )
    {
        if( empty($table) ){
            return false;
        }
        
        $query = "";
        $query  .=  "SELECT `CREATE_TIME` AS `time` ";
        $query  .=  "FROM `information_schema`.`tables` ";
        $query  .=  "WHERE `table_type` = 'BASE TABLE' ";
        $query  .=  "AND table_name LIKE :table ";
        
        $result = $ww->db->fetchQuery($query, [ 'table' => $table ]);
        
        return $result['time'] ?? false;
    }
    
    static function createStructureTable( WoodWiccan $ww, string $table, array $columns )
    { 
        if( empty($table) || empty($columns) ){
            return false;
        }
        
        $query = "";
        $query  .=  "CREATE TABLE `".$ww->db->escape_string($table)."` ( ";
        $query  .=  implode( ", ", $columns );
        $query  .=  ") ";
        
        return $ww->db->createQuery($query);
    }
    
    static function updateStructureTable( WoodWiccan $ww, string $table, array $addColumns=[], array $removeColumns=[], array $changeColumns=[] )
    { 
        if( empty($table) || (empty( $addColumns ) && empty( $removeColumns ) && empty( $changeColumns )) ){
            return false;
        }
        
        $query = "";
        $query  .=  "ALTER TABLE `".$ww->db->escape_string($table)."` ";
        
        $separator = "";
        foreach( $removeColumns as $columnName )
        {
            $query      .=  $separator." DROP `".$columnName."` ";
            $separator  =   ", ";
        }
        foreach( $addColumns as $columnDefinition )
        {
            $query      .=  $separator." ADD ".$columnDefinition." ";
            $separator  =   ", ";
        }
        foreach( $changeColumns as $fromColumnName => $toColumnDefinition )
        {
            $query      .=  $separator." CHANGE `".$fromColumnName."` ".$toColumnDefinition." ";
            $separator  =   ", ";
        }
        
        $ww->db->alterQuery($query);
        $ww->cache->delete( self::CACHE_FOLDER, $table );
        
        return true;
    }
    
    static function getWitchDataFromStructureTables( WoodWiccan $ww, array $tables )
    {
        if( empty($tables) ){
            return false;
        }
        
        $params = [];        
        $query = "";
        $query  .= "SELECT * ";
        $query  .= "FROM `witch` ";
        $query  .= "WHERE ";
        
        $separator = "";
        foreach( $tables as $i => $tableName )
        {
            $key = 'table'.$i;
            $query  .=  $separator."`craft_table` LIKE :".$key." ";
            $params[ $key ] = $tableName;
            $separator      = "OR ";
        }
        
        return $ww->db->selectQuery($query, $params);        
    }
    
    
    static function deleteStructureTable( WoodWiccan $ww, string $table )
    { 
        if( empty($table) ){
            return false;
        }
    
        $query = "DROP TABLE `".$ww->db->escape_string($table)."` ";
        
        $ww->db->deleteQuery($query);
        
        $ww->cache->delete( self::CACHE_FOLDER, $table );
        
        return true;
    }

    static function listStructures( WoodWiccan $ww )
    {
        $query = "";
        $query  .=  "SELECT table_name AS tn ";
        $query  .=  ", create_time AS ct ";
        $query  .=  "FROM information_schema.tables ";
        $query  .=  "WHERE table_type = 'BASE TABLE' ";
        $query  .=  "AND table_name LIKE 'content__%' ";
        $query  .=  "ORDER BY table_name ASC ";
        
        $result =   $ww->db->multipleRowsQuery($query);
        
        $structures     = [];
        foreach( $result as $item )
        {
            $tableName  = $item['tn'];
            
            if( !str_starts_with($tableName, "content__") ){
                continue;
            } 
            
            $structureName = substr($tableName, strlen("content__"));
            
            $structures[ $structureName ] = [ 
                'name'      => $structureName, 
                'created'   => $item['ct'],
            ];
        }
        
        return $structures;
    }
    
    static function countElements( WoodWiccan $ww, string $structure )
    {
        if( empty($structure) ){
            return false;
        }
        
        $count = [];
        foreach( Craft::TYPES as $type ) 
        {
            $query  =   "SELECT COUNT(*) ";
            $query  .=  "FROM `".$type."__".$structure."` ";
            
            $count[$type]  = $ww->db->countQuery($query);
        }
        
        return $count;
    }
    
    static function createCraft( WoodWiccan $ww, string $table, ?string $name=null, ?int $contentKey=null )
    {
        if( empty($table) ){
            return false;
        }
        
        $userId = $ww->user->id;
        $params = [];
        if( $userId )
        {
            $params["creator"]      = $userId;
            $params["modificator"]  = $userId;            
        }
        if( $name ){
            $params["name"]         = $name;
        }
        if( $contentKey ){
            $params["content_key"]  = $contentKey;
        }
        
        $query = "";
        $query  .=  "INSERT INTO `".$ww->db->escape_string($table)."` ( ";
        if( !empty($params) ){
            $query  .=  "`".implode( "`, `", array_keys($params) )."`";
        }
        $query  .=  ") VALUES ( ";
        if( !empty($params) ){
            $query  .=  ":".implode( ", :", array_keys($params) )." ";
        }
        $query  .=  ") ";
        
        return $ww->db->insertQuery($query, $params);
    }    
}
