<?php
namespace WW\DataAccess;

use WW\WoodWiccan;
use WW\Witch;
use WW\Module;
use WW\Structure;
use WW\Attribute;

/**
 * Class to aggregate Witch's Craft related data access functions
 * all these functions are statics
 * 
 * @author Jean2Grom
 */
class WitchCrafting 
{
    const CACHE_FOLDER = "craft";
    
    static function readCraftData( WoodWiccan $ww, array $summoningConfiguration, array $witches )
    {
        $targetsToCraft = [];
        foreach( $summoningConfiguration as $type => $typeConfiguration )
        {
            if( $type === 'user' ){
                $witchRefConfJoins = [ 'user' => $typeConfiguration ];
            }
            else {
                $witchRefConfJoins = $typeConfiguration;
            }
            
            foreach( $witchRefConfJoins as $witchConf )
            {
                $refWitch = array_keys($witchConf['entries'])[0];
                
                if( empty($witches[ $refWitch ]) ){
                    continue;
                }
                
                $permission = false;
                foreach( $witchConf['entries'] as $invoke )
                {
                    if( $invoke === false ){
                        $permission = true;
                    }
                    elseif( $invoke == true && $witches[ $refWitch ]->hasInvoke() )
                    {
                        $module     = new Module( $witches[ $refWitch ], $witches[ $refWitch ]->invoke );
                        $permission = $witches[ $refWitch ]->isAllowed( $module );
                    }
                    else {
                        $module     = new Module( $witches[ $refWitch ], $invoke );
                        $permission = $witches[ $refWitch ]->isAllowed( $module );
                    }
                    
                    if( $permission ){
                        break;
                    }
                }
                
                if( !$permission ){
                    continue;
                }
                
                if( !isset($witchConf['craft']) || !empty($witchConf['craft']) )
                {
                    $table  = $witches[ $refWitch ]->craft_table;
                    $fk     = (int) $witches[ $refWitch ]->craft_fk;
                    
                    if( !empty($table) && !empty($fk) ){
                        $targetsToCraft[ $table ] = array_merge($targetsToCraft[ $table ] ?? [], [$fk]);
                    }
                }
                
                if( !empty($witchConf['parents']['craft']) ){
                    $targetsToCraft = array_merge_recursive( 
                        $targetsToCraft, 
                        self::getParentsCraftData( $witches[ $refWitch ], $witchConf['parents']['craft'] )
                    );

                }

                if( !empty($witchConf['sisters']['craft']) && !empty($witches[ $refWitch ]->sisters) ){
                    foreach( $witches[ $refWitch ]->sisters as $sisterWitch ){
                        $targetsToCraft = array_merge_recursive( 
                            $targetsToCraft, 
                            self::getChildrenCraftData( $sisterWitch, $witchConf['sisters']['craft'] )
                        );
                    }
                }

                if( !empty($witchConf['children']['craft']) ){
                    $targetsToCraft = array_merge_recursive( 
                        $targetsToCraft, 
                        self::getChildrenCraftData( $witches[ $refWitch ], $witchConf['children']['craft'] )
                    );
                }
            }
        }
        
        $craftedData     = [];
        foreach( $targetsToCraft as $table => $ids )
        {
            $craftedData[ $table ]  = [];
            $idList                 = [];
            
            $cachedData = $ww->cache->read( self::CACHE_FOLDER, $table ) ?? [];
            
            foreach( array_unique($ids) as $id ){
                if( isset( $cachedData[ $id ]) ){
                    $craftedData[ $table ][ $id ] = $cachedData[ $id ];
                }
                else {
                    $idList[] = $id;
                }
            }
            
            if( !empty($idList) )
            {
                $craftedData[ $table ]  = array_replace($craftedData[ $table ], self::craftQueryFromIds( $ww, $table, $idList ));
                $ww->cache->create( self::CACHE_FOLDER, $table, array_replace($cachedData, $craftedData[ $table ]) );
            }
            $cachedData = null;
        }
        
        return $craftedData;
    }

    // RECURSIVE READ CRAFT DATA FUNCTIONS
    private static function getChildrenCraftData( Witch $witch, mixed $craftLevel )
    {
        $targetsToCraft = [];
        if( !empty($witch->daughters) ){
            foreach( $witch->daughters as $daughterWitch )
            {
                $table  = $daughterWitch->craft_table;
                $fk     = (int) $daughterWitch->craft_fk;
                
                if( !empty($table) && !empty($fk) )
                {
                    if( empty($targetsToCraft[ $table ]) ){
                        $targetsToCraft[ $table ] = [];
                    }
                    
                    if( !in_array($fk, $targetsToCraft[ $table ]) ){
                        $targetsToCraft[ $table ][] = $fk;
                    }
                }
                
                if( $craftLevel == "*" ){
                    $craftSubLevel = $craftLevel;
                }
                else 
                {
                    $craftSubLevel = $craftLevel - 1;
                    if( $craftSubLevel == 0 ){
                        continue;
                    }
                }
                
                $targetsToCraft = array_merge_recursive(
                    $targetsToCraft, 
                    self::getChildrenCraftData($daughterWitch, $craftSubLevel) 
                );
            }
        }
        
        return $targetsToCraft;
    }
    
    private static function getParentsCraftData( Witch $witch, mixed $craftLevel )
    {
        $targetsToCraft = [];
        if( !empty($witch->mother) )
        {
            $motherWitch    = $witch->mother;
            
            $table          = $motherWitch->craft_table;
            $fk             = (int) $motherWitch->craft_fk;
            
            if( !empty($table) && !empty($fk) )
            {
                if( empty($targetsToCraft[ $table ]) ){
                    $targetsToCraft[ $table ] = [];
                }

                if( !in_array($fk, $targetsToCraft[ $table ]) ){
                    $targetsToCraft[ $table ][] = $fk;
                }
            }

            if( $craftLevel == "*" ){
                $craftSubLevel = $craftLevel;
            }
            else {
                $craftSubLevel = $craftLevel - 1;
            }

            if( $craftSubLevel == "*" || $craftSubLevel > 0 ){
                $targetsToCraft = array_merge_recursive(
                    $targetsToCraft, 
                    self::getParentsCraftData($motherWitch, $craftSubLevel) 
                );
            }
        }
        
        return $targetsToCraft;
    }
    
    
    static function getCraftDataFromIds( WoodWiccan $ww, string $table,  array $ids )
    {
        $craftedData     = [];
        $idList          = [];
        
        $cachedData = $ww->cache->read( self::CACHE_FOLDER, $table ) ?? [];

        foreach( array_unique($ids) as $id ){
            if( isset( $cachedData[ $id ]) ){
                $craftedData[ $id ] = $cachedData[ $id ];
            }
            else {
                $idList[] = $id;
            }
        }

        if( !empty($idList) )
        {
            $craftedData  = array_replace($craftedData, self::craftQueryFromIds( $ww, $table, $idList ));
            $ww->cache->create( self::CACHE_FOLDER, $table, array_replace($cachedData, $craftedData) );
        }
        
        return $craftedData;
    }
    
    static function craftQueryFromIds( WoodWiccan $ww, string $table, array $ids ): array
    {
        if( empty($table) || empty($ids) ){
            return [];
        }
        
        $structure = new Structure( $ww, $table );
        
        //$querySelectElements    = [];
        $querySelectElements    = $structure->getJoinFields();
        //$queryTablesElements    = [];
        $queryTablesElements    = $structure->getJointure();
        $queryWhereElements     = [];
        $params                 = [];
        
        foreach( $ids as $paramKey => $paramValue ){
            $params[ $table.'_'.$paramKey ] = $paramValue;
        }

        $queryWhereElements[]   = "`".$table."`.`id` IN ( :".implode(', :', array_keys($params))." ) ";
        
        foreach( $structure->getFields() as $commonStructureField )
        {
            $field  =   "`".$table."`.`".$commonStructureField."` ";
            $field  .=  "AS `".$table."|".$commonStructureField."` ";
            $querySelectElements[] = $field;
        }

        foreach( $structure->attributes() as $attributeName => $attributeData )
        {
            $attribute = new $attributeData['class']( $ww, $attributeName );

            array_push( $querySelectElements, ...$attribute->getSelectFields($table) );
            array_push( $queryTablesElements, ...$attribute->getJointure($table) );
        }
        
        $query = "";
        $query  .=  "SELECT ".implode( ', ', $querySelectElements)." ";
        $query  .=  "FROM "." `".$table."` ";

        foreach( $queryTablesElements as $leftJoin ){
            $query  .=  $leftJoin." ";
        }

        $query  .=  "WHERE ".implode( 'AND ', $queryWhereElements )." ";
        
        $result         = $ww->db->selectQuery( $query, $params );        
        $craftedData    = self::formatCraftData( $result );
        
        return $craftedData[ $table ] ?? [];
    }
    
    
    static function craftQueryFromAttributeSearch( WoodWiccan $ww, Structure $structure, array $criterias, bool $excludeCriterias=true )
    {
        if( empty($criterias) ){
            return [];
        }
        
        $table = $structure->table;
        
        $querySelectElements    = [];
        $queryTablesElements    = [];
        $queryWhereElements     = [];
        $params                 = [];
        
        $queryTablesElements[ $table ] = [];
        foreach( $structure->getFields() as $commonStructureField )
        {
            $field  =   "`".$table."`.`".$commonStructureField."` ";
            $field  .=  "AS `".$table."|".$commonStructureField."` ";
            $querySelectElements[] = $field;
        }

        foreach( $structure->attributes() as $attributeName => $attributeData )
        {
            $attribute = new $attributeData['class']( $ww, $attributeName );
            
            array_push( $querySelectElements, ...$attribute->getSelectFields($table) );
            $queryTablesElements[ $table ] = array_merge($queryTablesElements[ $table ] ?? [], $attribute->getJointure( $table ) );
            
            foreach( $criterias as $criteriaKey => $criteriaValue ){
                if( $criteriaKey === $attributeName || $criteriaKey === '*' )
                {
                    $searchCondition        = $attribute->searchCondition( $table, $criteriaValue );
                    
                    if( $searchCondition ){
                        $queryWhereElements[]   = $searchCondition['query'];
                        $params                 = array_replace( $params, $searchCondition['params'] );                        
                    }
                }
            }
        }
        
        $query = "";
        $query  .=  "SELECT ".implode( ', ', $querySelectElements)." ";
        $separator = "FROM ";
        foreach( $queryTablesElements as $fromTable => $leftJoinArray )
        {
            $query  .=  $separator." `".$fromTable."` ";
            $separator = ", ";
            
            foreach( $leftJoinArray as $leftJoin ){
                $query  .=  $leftJoin;
            }
        }
        
        if( $excludeCriterias ){
            $glue = 'AND ';
        }
        else {
            $glue = 'OR ';
        }
        
        $query  .=  "WHERE ".implode( $glue, $queryWhereElements )." ";

        $result         = $ww->db->selectQuery($query, $params);
        $craftedData    = self::formatCraftData($result);
        
        $cachedData = $ww->cache->read( self::CACHE_FOLDER, $table ) ?? [];
        $ww->cache->create( self::CACHE_FOLDER, $table, array_replace($cachedData, $craftedData[ $table ] ?? []) );        
        
        return $craftedData[ $table ] ?? [];
    }
    
    private static function formatCraftData( array $sqlRawCraftDataResults ): array
    {
        $craftedData = [];
        foreach( $sqlRawCraftDataResults as $row ){
            foreach( $row as $rowField => $rowFieldValue )
            {
                $splitSelectField = Attribute::splitSelectField( $rowField );
                
                $table          = $splitSelectField['table'];
                $field          = $splitSelectField['field'];
                $fieldElement   = $splitSelectField['element'];
                
                $currentId      = $row[ $table.'|id' ];
                
                // New table
                if( empty($craftedData[ $table ]) ){
                    $craftedData[ $table ] = [];
                }
                // New record
                if( empty($craftedData[ $table ][ $currentId ]) ){
                    $craftedData[ $table ][ $currentId ] = [ '@attributes' => [] ];
                }                
                // Fixed craft columns
                if( empty($fieldElement) )
                {
                    $craftedData[ $table ][ $currentId ][ $field ] = $rowFieldValue;
                    continue;
                }
                
                // Attribute first enconter
                if( empty($craftedData[ $table ][ $currentId ]['@attributes'][ $field ]) ){
                    $craftedData[ $table ][ $currentId ]['@attributes'][ $field ] = [];
                }
                
                // Attribute element first enconter
                if( empty($craftedData[ $table ][ $currentId ]['@attributes'][ $field ][ $fieldElement ]) ){
                    $craftedData[ $table ][ $currentId ]['@attributes'][ $field ][ $fieldElement ] = $rowFieldValue;
                }
                // Attribute element second enconter, making it an array of values
                elseif( !is_array($craftedData[ $table ][ $currentId ]['@attributes'][ $field ][ $fieldElement ]) )
                {
                    $prevValue = $craftedData[ $table ][ $currentId ][ $field ][ $fieldElement ];

                    if( $prevValue != $rowFieldValue ){
                        $craftedData[ $table ][ $currentId ][ $field ][ $fieldElement ] = [
                            $prevValue,
                            $rowFieldValue,
                        ];
                    }
                }
                // Attribute element more enconter, adding a value
                else {
                    $craftedData[ $table ][ $currentId ]['@attributes'][ $field ][ $fieldElement ][] = $rowFieldValue;
                }
            }
        }
        
        return $craftedData;
    }
}
