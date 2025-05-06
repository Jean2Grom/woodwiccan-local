<?php 
namespace WW\Cauldron\Ingredient;

use WW\Cauldron\Ingredient;

class StringIngredient extends Ingredient
{
    const TYPE  = 'string';

    /**
     * Init function used to setup ingredient
     * @param mixed $value : if left to null, read from properties values 'value'
     * @return self
     */
    function init( mixed $value=null ): self {
        return $this->set( $value ?? (string) ($this->properties[ 'value' ] ?? "") );
    }

    /**
     * Default function to set value
     * @param mixed $value : has to be a string
     * @return self
     */
    public function set( mixed $value ): self
    {
        if( !is_null($value) && !is_string($value) ){
            $this->ww->log->error( "Try to set a non string value to ".$this->type." ingredient");
        }
        else {
            $this->value = $value;
        }

        return $this;
    }

    function value(): string {
        return $this->value ?? "";
    }
}