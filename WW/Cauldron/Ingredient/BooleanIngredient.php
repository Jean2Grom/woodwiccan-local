<?php 
namespace WW\Cauldron\Ingredient;

use WW\Cauldron\Ingredient;

class BooleanIngredient extends Ingredient
{
    const TYPE  = 'boolean';

    function __toString(){
        return  is_null($this->value)? "null": ($this->value == 0? "false": "true");
    }

    /**
     * Init function used to setup ingredient
     * @param mixed $value : if left to null, read from properties values 'value'
     * @return self
     */
    function init( mixed $value=null ): self {
        return $this->set( 
            $value 
            ?? ( isset($this->properties['value']) && !is_null($this->properties['value']) )? 
                (bool) $this->properties['value']: null 
        );
    }

    /**
     * Prepare function used to write ingredient properties
     * @return self
     */
    function prepare(): self {
        if( !is_null($this->value) ){
            $this->properties['value'] = (int) $this->value;
        }
        else {
            $this->properties['value'] = null;
        }
        
        return $this;
    }

    /**
     * Set value
     * @param mixed $value : has to be a boolean
     * @return self
     */
    public function set( mixed $value ): self
    {
        if( !is_null($value) && !is_bool($value) ){
            $this->ww->log->error( "Try to set a non boolean value to ".$this->type." ingredient");
        }
        else {
            $this->value = $value;
        }

        return $this;
    }

    function input( mixed $input ): self {
        return $this->set( (bool) $input );
    }
}