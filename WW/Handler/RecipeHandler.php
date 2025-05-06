<?php 
namespace WW\Handler;

use WW\WoodWiccan;
use WW\Cauldron\Recipe;
use WW\Cauldron\Ingredient;

class RecipeHandler
{

    /**
     * Cauldron factory class, implements Cauldron with data provided
     * @param WoodWiccan $ww
     * @param array $data
     * @return Recipe
     */
    static function createFromData(  WoodWiccan $ww, array $data ): Recipe
    {
        $recipe      = new Recipe();
        $recipe->ww  = $ww;
        
        $recipe->properties = $data;

        self::readProperties( $recipe );

        return $recipe;
    }  

    /**
     * @param WoodWiccan $ww
     * @param string $file
     * @return ?Recipe
     */
    static function createFromFile(  WoodWiccan $ww, string $file ): ?Recipe
    {
        $jsonData = self::extractJsonDataFromFile( $file );
        if( !$jsonData ){
            return null;
        }
        
        $recipe          = self::createFromData( $ww, $jsonData );
        $recipe->file    = $file;
        
        return $recipe;
    }  

    /**
     * @param string $file
     * @return ?array 
     */
    static function extractJsonDataFromFile( string $file ): ?array
    {
        if( !is_file($file) ){
            return null;
        }

        $jsonString = file_get_contents($file);
        if( !$jsonString ){
            return null;
        }

        $jsonData   = json_decode($jsonString, true);
        if( !$jsonData ){
            return null;
        }

        return $jsonData;
    }

    /**
     * Update  Object current state based on var "properties" (directly rad from JSON file) 
     * @return void
     */
    static function readProperties( Recipe $recipe ): void
    {
        $recipe->name        = $recipe->properties['name'] ?? null;
        $recipe->class       = $recipe->properties['class'] ?? null;
        $recipe->composition = $recipe->properties['composition'] ?? null;
        $recipe->require     = $recipe->properties['require'] ?? null;

        return;
    }

    /**
     * Update var "properties" (directly rad from JSON file) based on Object current state 
     * @return void
     */
    static function writeProperties( Recipe $recipe ): void
    {
        $recipe->properties = [];
        if( $recipe->name ){
            $recipe->properties['name'] = $recipe->name;
        }
        if( $recipe->class ){
            $recipe->properties['class'] = $recipe->class;
        }
        if( $recipe->require ){
            $recipe->properties['require'] = $recipe->require;
        }
        if( $recipe->composition )
        {
            $recipe->properties['composition'] = [];
            foreach( $recipe->composition as $item )
            {
                $content = [];
                if( !empty($item[ "mandatory" ]) ){
                    $content["mandatory"] = $item["mandatory"];
                }
                if( !empty($item[ "name" ]) ){
                    $content["name"] = $item["name"];
                }
                if( !empty($item[ "type" ]) ){
                    $content["type"] = $item["type"];
                }
                if( !empty($item[ "require" ]) ){
                    $content["require"] = $item["require"];
                }

                $recipe->properties['composition'][] = $content;
            }
        }

        return;
    }

    /**
     * Insert Recipe object references in compositions
     * @param array $recipes
     * @return bool
     */
    static function resolve( array $recipes ): bool
    {
        $return = true;
        foreach( $recipes as $recipe )
        {
            // if( $recipe->type !== Recipe::DEFAULT_TYPE ){
            //     if( !isset($recipes[ $recipe->type ]) ){
            //         $return = false;
            //     }
            //     else {
            //         $recipe->recipe =  $recipes[ $recipe->type ];
            //     }
            // }

            foreach(  $recipe->composition ?? [] as $key => $content )
            {
                if( !isset($content['type']) || in_array($content['type'] ?? "", Ingredient::list()) ){
                    continue;
                }
                // elseif( $content['type'] === Recipe::DEFAULT_TYPE ){
                //     $recipe->composition[ $key ]['recipe'] = self::createFromData( $recipe->ww, $content );
                // }
                elseif( isset($recipes[ $content['type'] ]) ){
                    $recipe->composition[ $key ]['recipe'] = $recipes[ $content['type'] ] ;
                }
                else {
                    $return = false;
                }
            }
        }

        return $return;
    }

}