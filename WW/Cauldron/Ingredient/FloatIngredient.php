<?php 
namespace WW\Cauldron\Ingredient;

use WW\Cauldron\Ingredient;

class FloatIngredient extends Ingredient
{
    const TYPE  = 'float';

    /**
     * Init function used to setup ingredient
     * @param mixed $value : if left to null, read from properties values 'value'
     * @return self
     */
    function init( mixed $value=null ): self {
        return $this->set( $value ?? (float) $this->properties[ 'value' ] ?? null );
    }

    /**
     * Default function to set value
     * @param mixed $value : has to be a float
     * @return self
     */
    public function set( mixed $value ): self
    {
        if( !is_null($value) && !is_float($value) ){
            $this->ww->log->error( "Try to set a non float value to ".$this->type." ingredient");
        }
        else {
            $this->value = $value;
        }

        return $this;
    }

    function readInput( mixed $input ): self {
        return $this->set( (float) $input );
    }

}