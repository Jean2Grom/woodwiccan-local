<?php 
namespace WW\Cauldron\Ingredient;

use WW\Cauldron\Ingredient;

class IntegerIngredient extends Ingredient
{
    const TYPE  = 'integer';

    /**
     * Init function used to setup ingredient
     * @param mixed $value : if left to null, read from properties values 'value'
     * @return self
     */
    function init( mixed $value=null ): self {
        //return $this->set( $value ?? (int) $this->properties[ 'value' ] ?? null );
        return $this->set( $value ?? $this->properties[ 'value' ] ?? null );
    }

    /**
     * Default function to set value
     * @param mixed $value : has to be a integer
     * @return self
     */
    public function set( mixed $value ): self
    {
        if( !is_null($value) && !ctype_digit(strval($value)) ){
            $this->ww->log->error( "Try to set a non integer value to ".$this->type." ingredient");
        }
        else {
            $this->value = $value;
        }

        return $this;
    }
}