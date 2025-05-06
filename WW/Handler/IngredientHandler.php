<?php 
namespace WW\Handler;

use WW\Cauldron;
use WW\Cauldron\Ingredient;

class IngredientHandler
{
    /**
     * @param Cauldron $cauldron
     * @param array $row
     * @return Ingredient
     */
    static function createFromData(  Cauldron $cauldron, string $type, array $data ): Ingredient
    {
        $ingredient             = self::factory($type);
        $ingredient->ww         = $cauldron->ww;

        $ingredient->properties                 = $data;
        $ingredient->properties['cauldron_fk']  = $cauldron->id;

        self::readProperties( $ingredient );
        CauldronHandler::setIngredient($cauldron, $ingredient);
        
        return $ingredient;
    }

    static function createFromDBRow(  Cauldron $cauldron, array $row ): void
    {
        foreach( Ingredient::DEFAULT_AVAILABLE_INGREDIENT_TYPES_PREFIX as $type => $prefix )
        {
            $id = $row[ $prefix.'_id' ] ?? null;
            if( is_null($id) ){
                continue;
            }

            foreach( $cauldron->ingredients ?? [] as $cauldronIngredient ){
                if( $cauldronIngredient->id == $id && $cauldronIngredient->type == $type ){
                    continue 2;
                }
            }

            $ingredient = self::factory($type);
            if( !$ingredient )
            {
                $cauldron->ww->debug( "Ingredient ".$type." : class not found, skip" );
                continue;
            }

            $properties = ['id' => $id];
            foreach( Ingredient::FIELDS as $field ){
                $properties[ $field ] = $row[ $prefix.'_'.$field ] ?? null;
            }

            self::createFromData( $cauldron, $type, $properties );
        }
        
        return;
    }

    /**
     * Update  Object current state based on var "properties" (database direct fields) 
     * @return void
     */
    static function readProperties( Ingredient $ingredient ): void
    {
        $ingredient->id = null;
        if( isset($ingredient->properties['id']) && ctype_digit(strval($ingredient->properties['id'])) ){
            $ingredient->id = (int) $ingredient->properties['id'];
        }

        $ingredient->cauldronID = null;
        if( isset($ingredient->properties['cauldron_fk']) && ctype_digit(strval($ingredient->properties['cauldron_fk'])) ){
            $ingredient->cauldronID = (int) $ingredient->properties['cauldron_fk'];
        }
        
        if( $ingredient->cauldronID !== $ingredient->cauldron?->id ){
            $ingredient->cauldron   = null;
        }

        $ingredient->name = null;
        if( isset($ingredient->properties['name']) ){
            $ingredient->name = $ingredient->properties['name'];
        }
        
        $ingredient->priority = 0;
        if( isset($ingredient->properties['priority']) ){
            $ingredient->priority = (int) $ingredient->properties['priority'];
        }

        $ingredient->init();
        
        return;
    }
    
    /**
     * Update var "properties" (database direct fields) based on Object current state 
     * @return void
     */
    static function writeProperties( Ingredient $ingredient ): void
    {
        $ingredient->properties = [];

        if( isset($ingredient->id) && is_int($ingredient->id) ){
            $ingredient->properties['id'] = $ingredient->id;
        }
        
        if( $ingredient->cauldron ){
            $ingredient->properties['cauldron_fk'] = $ingredient->cauldron->id;
        }
        elseif( $ingredient->cauldronID ){
            $ingredient->properties['cauldron_fk'] = $ingredient->cauldronID;
        }

        if( isset($ingredient->name) ){
            $ingredient->properties['name'] = $ingredient->name;
        }

        if( isset($ingredient->priority) ){
            $ingredient->properties['priority'] = $ingredient->priority;
        }
        
        $ingredient->prepare();
        
        return;
    }
    
    static function factory( string $type ): ?Ingredient
    {
        $className  =   Ingredient::class."\\";
        $className  .=  str_replace('_', '', ucwords($type, '_'));
        $className  .=  'Ingredient';

        if( !class_exists($className) ){
            return null;
        }

        return new $className();
    }

    static function table( Ingredient $ingredient ): string {
        return "ingredient__".$ingredient->type;
    }
}