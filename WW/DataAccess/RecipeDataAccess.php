<?php
namespace WW\DataAccess;

use WW\WoodWiccan;

/**
 * Class to aggregate recipe related data access functions
 * all these functions are statics
 * 
 * @author Jean2Grom
 */
class RecipeDataAccess 
{
    static function readUsage( WoodWiccan $ww, array $recipes ) 
    {
        if( empty($recipes) ){
            return false;
        }

        $query = "";
        $query  .=  "SELECT `cauldron`.`recipe` ";
        $query  .=  ", COUNT(`cauldron`.`id`) AS cauldron ";
        $query  .=  ", COUNT(`witch`.`id`) AS witches ";
        $query  .=  "FROM `cauldron` ";
        $query  .=  "LEFT JOIN `witch` ";
        $query  .=      "ON `cauldron`.`id`=`witch`.`cauldron` ";
        $query  .=  "WHERE `cauldron`.`recipe` ";
        $query  .=      "IN ( :".implode("key, :", array_keys($recipes))."key ) ";
        $query  .=  "GROUP BY `recipe` ";
        
        $params = [];
        foreach( $recipes as $i => $recipe ){
            $params[ $i."key" ] = $recipe;
        }

        $result = $ww->db->selectQuery($query, $params);

        if( $result === false ){
            return false;
        }

        $return = [];
        foreach( $recipes as $recipe ){
            $return[ $recipe ] = [ "cauldron" => 0, "witches" => 0 ];
        }
        
        foreach( $result as $row ){
            $return[ $row['recipe'] ] = [ 
                "cauldron"  => $row['cauldron'], 
                "witches"   => $row['witches'] 
            ];
        }

        return $return;
    }

}
