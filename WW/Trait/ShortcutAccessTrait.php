<?php
namespace WW\Trait;

use WW\Witch;

trait ShortcutAccessTrait
{
    function __call( $name, $arguments )
    {
        $callable = [$this->ww, $name];
        if( !is_callable($callable, false) )
        {
            $trace = debug_backtrace();
            $this->ww->log->error(  
                __CLASS__.": Unidentified Method call \"".$name.'"',
                true, 
                [
                    'file' => $trace[0]['file'], 
                    'line' => $trace[0]['line'] 
                ]
            );
        }

        return call_user_func_array( [$this->ww, $name], $arguments );
    }


    function witch( ?string $witchName=null ): ?Witch
    {
        if( is_null($witchName) )
        {
            $obj = new \ReflectionObject($this);
            
            if( $obj->hasProperty('witch') ){
                return $this->witch;
            }
        }
        
        return $this->ww->cairn->witch( $witchName );
    }
}
