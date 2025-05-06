<?php 
namespace WW\Cauldron\Ingredient;

use WW\Cauldron\Ingredient;

class DatetimeIngredient extends Ingredient
{
    const TYPE  = 'datetime';

    function __toString(){
        return empty($this->value)? "unset": $this->value->format("Y-m-d H:i:s");
    }    

    /**
     * Prepare function used to write ingredient properties
     * @return self
     */
    function prepare(): self {
        if( $this->value ){
            $this->properties['value'] = $this->value->format('Y-m-d H:i:s');
        }
        else {
            $this->properties['value'] = null;
        }
        
        return $this;
    }

    /**
     * Default function to set value
     * @param mixed $value : has to be a string
     * @return self
     */
    public function set( mixed $value ): self
    {
        if( empty($value) ){
            $this->reset();
        }
        elseif( gettype($value) === 'object' && get_class($value) === "DateTime" ){
            $this->value = $value;
        }
        else 
        {
            $datetime       = false;
            $datetimeString = (string) $value;
            if( $datetimeString ){
                //$datetime = \DateTime::createFromFormat('Y-m-d H:i:s', $datetimeString);
                $datetime = new \DateTime( $datetimeString );
            }

            if( !$datetime ){
                $this->ww->log->error( "Try to set a non DateTime value to ".$this->type." ingredient");
            }
            else {
                $this->value = $datetime;
            }
        }

        return $this;
    }

}