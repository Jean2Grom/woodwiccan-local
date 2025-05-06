<?php
namespace WW\DataAccess;

use WW\WoodWiccan;
use WW\Craft\Draft;
use WW\Craft\Archive;

/**
 * Class to aggregate Craft related data access functions
 * all these functions are statics
 * 
 * @author Jean2Grom
 */
class Craft 
{
    static function getRelatedCraftsIds( WoodWiccan $ww, string $table, int $id )
    {
        if( empty($table) || empty($id) ){
            return false;
        }
        
        $params = [ 'id' => $id, ];
        
        $query = "";
        $query  .=  "SELECT `id` ";
        $query  .=  "FROM `".$ww->db->escape_string($table)."` ";
        $query  .=  "WHERE `content_key` = :id ";
        
        $result =   $ww->db->selectQuery($query, $params);
        
        $ids = [];
        foreach( $result ?? [] as $row ){
            $ids[] = $row['id'];
        }
        
        return $ids;
    }
    
    static function countWitches( WoodWiccan $ww, ?string $table, ?int $id )
    {
        if( empty($table) || empty($id) ){
            return false;
        }
        
        $params = [
            'craft_table'  => $table,
            'craft_fk'     => $id,
        ];
        
        $query = "";
        $query  .=  "SELECT count(`id`) ";
        $query  .=  "FROM `witch` ";
        $query  .=  "WHERE `witch`.`craft_table` = :craft_table ";
        $query  .=  "AND `witch`.`craft_fk` = :craft_fk ";
        
        return $ww->db->countQuery($query, $params);
    }
    
    static function getWitches( WoodWiccan $ww, string $table, int $id )
    {
        if( empty($table) || empty($id) ){
            return false;
        }
        
        $params = [
            'craft_table'  => $table,
            'craft_fk'     => $id,
        ];
        
        $query = "";
        $query  .=  "SELECT * ";
        $query  .=  "FROM `witch` ";
        $query  .=  "WHERE `witch`.`craft_table` = :craft_table ";
        $query  .=  "AND `witch`.`craft_fk` = :craft_fk ";
        
        return $ww->db->selectQuery($query, $params);
    }
        
    static function getWitchesFromContentKey( WoodWiccan $ww, string $table, int $contentKey )
    {
        if( empty($table) || empty($contentKey) ){
            return false;
        }
        
        $params = [
            'craft_table'  => $table,
            'content_key'   => $contentKey,
        ];
        
        $tableSql = $ww->db->escape_string($table);
        
        $query = "";
        $query  .=  "SELECT `witch`.* ";
        $query  .=  "FROM `".$tableSql."` ";
        $query  .=  "LEFT JOIN `witch` ";
        $query  .=      "ON `witch`.`craft_fk` = `".$tableSql."`.`id` ";
        $query  .=      "AND `witch`.`craft_table` = :craft_table ";
        $query  .=  "WHERE `".$tableSql."`.`content_key` = :content_key ";
        $query  .=  "AND `witch`.`id` IS NOT NULL ";
        
        return $ww->db->selectQuery($query, $params);
    }
    
    static function delete( WoodWiccan $ww, string $table, int $id )
    {
        if( empty($table) || empty($id) ){
            return false;
        }
        
        $cachedData = $ww->cache->read( WitchCrafting::CACHE_FOLDER, $table ) ?? [];
        if( isset($cachedData[ $id ]) ){
            $ww->cache->delete( WitchCrafting::CACHE_FOLDER, $table );
        }
        
        $query = "";
        $query  .=  "DELETE FROM `".$ww->db->escape_string($table)."` ";
        $query  .=  "WHERE `id` = :id ";
        
        return $ww->db->deleteQuery($query, [ 'id' => $id ]);
    }
    
    static function cleanupContentKey( WoodWiccan $ww, string $structureName, int $contentKey )
    {
        if( empty($structureName) || empty($contentKey) ){
            return false;
        }
        
        $draftIds   = self::getRelatedCraftsIds($ww, Draft::TYPE.'__'.$structureName, $contentKey);
        $archiveIds = self::getRelatedCraftsIds($ww, Archive::TYPE.'__'.$structureName, $contentKey);
        
        if( empty($draftIds) && empty($archiveIds) ){
            return;
        }
        
        $query = "";
        $query  .=  "SELECT count(`id`) ";
        $query  .=  "FROM `witch` ";        
        $query  .=  "WHERE ";
        
        $params = [];
        if( !empty($draftIds) )
        {
            $params['draft_craft_table'] = Archive::TYPE.'__'.$structureName;
            
            foreach( $draftIds as $i => $id ){
                $params[ 'draft_id_'.$i ] = $id;
            }
            
            $query  .=  "( `witch`.`craft_table` = :draft_craft_table ";
            $query  .=      "AND `witch`.`craft_fk` IN ( :draft_id_".implode(', :draft_id_', array_keys($draftIds))." ) ";
            $query  .=  ") ";
        }
        
        if( !empty($draftIds) && !empty($archiveIds) ){
             $query  .=  "OR ";
        }
        
        if( !empty($archiveIds) )
        {
            $params['archive_craft_table'] = Draft::TYPE.'__'.$structureName;
            
            foreach( $archiveIds as $i => $id ){
                $params[ 'archive_id_'.$i ] = $id;
            }
            
            $query  .=  "( `witch`.`craft_table` = :archive_craft_table ";
            $query  .=      "AND `witch`.`craft_fk` IN ( :archive_id_".implode(', :draft_id_', array_keys($archiveIds))." ) ";
            $query  .=  ") ";
        }
        
        $count = $ww->db->countQuery($query, $params);
        
        if( $count === 0 )
        {
            $query = "";
            $query  .=  "DELETE FROM `".$ww->db->escape_string( Draft::TYPE.'__'.$structureName )."` ";
            $query  .=  "WHERE `content_key` = :content_key ";

            $ww->db->deleteQuery($query, [ 'content_key' => $contentKey ]);
            
            $query = "";
            $query  .=  "DELETE FROM `".$ww->db->escape_string( Archive::TYPE.'__'.$structureName )."` ";
            $query  .=  "WHERE `content_key` = :content_key ";

            $ww->db->deleteQuery($query, [ 'content_key' => $contentKey ]);            
        }
        
        return;
    }
    
    static function update( WoodWiccan $ww, string $table, array $fields, array $conditions )
    {
        if( empty($table) || empty($fields) || empty($conditions) ){
            return false;
        }

        $userId = $ww->user->id;
        if( $userId )
        {
            $fields["creator"]      = $fields["creator"] ?? $userId;
            $fields["modificator"]  = $fields["modificator"] ?? $userId;            
        }
        
        $params = []; 
        $query  = "";
        $query  .=  "UPDATE `".$ww->db->escape_string($table)."` ";
        
        $separator = "SET ";
        foreach( $fields as $field => $value )
        {
            $key            = md5($field.$value);
            $params[ $key ] = $value;
            $query  .=  $separator."`".$field."` = :".$key." ";
            $separator = ", ";
        }
        
        $separator = "WHERE ";
        foreach( $conditions as $field => $value )
        {
            $key            = md5($field.$value);
            $params[ $key ] = $value;
            
            $query  .=  $separator."`".$field."` = :".$key." ";
            $separator = "AND ";            
        }
        
        if( isset($conditions['id']) )
        {
            $cachedData = $ww->cache->read( WitchCrafting::CACHE_FOLDER, $table ) ?? [];
            if( isset($cachedData[ $conditions['id'] ]) ){
                $ww->cache->delete( WitchCrafting::CACHE_FOLDER, $table );
            }
        }
        
        return $ww->db->updateQuery($query, $params);
    }
    
}
