<?php
namespace WW;

/**
 * PHP Session WW Custom Handler 
 * 
 * @author Jean2Grom
 */
class Session 
{
    public $namespace;
    
    /** 
     * WoodWiccan container class to allow whole access to Kernel
     * @var WoodWiccan
     */
    public WoodWiccan $ww;
    
    function __construct( WoodWiccan $ww, string $namespace="" )
    {
        $this->ww           = $ww;
        
        if( session_status() !== PHP_SESSION_ACTIVE ){
            session_start();
        }
        
        if( !empty($namespace) ){
            $this->namespace = $namespace;
        }
        else {
            $this->namespace = $this->ww->website->name;
        }
        
        if( empty($_SESSION[ $this->namespace ]) ){
            $_SESSION[ $this->namespace ] = [];
        }
    }
    
    function write( string $name, mixed $value ): self
    {        
        if( is_object($value) )
        {
            $value = serialize($value);
            $_SESSION[ $this->namespace ][ 'wwObjectsHashArray' ] = array_replace(
                $_SESSION[ $this->namespace ][ 'wwObjectsHashArray' ] ?? [],
                [ $name => hash_hmac('sha256', $value, session_id()) ]
            );
        }
        
        $_SESSION[ $this->namespace ][ $name ] = $value;
        
        return $this;
    }    
    
    function read( string $name ): mixed
    {
        $value      = $_SESSION[ $this->namespace ][ $name ] ?? false;
        $objectHash = $_SESSION[ $this->namespace ][ 'wwObjectsHashArray' ][ $name ] ?? false;
        
        if( $objectHash && hash_hmac('sha256', $value, session_id()) === $objectHash ){
            return unserialize($value);
        }
        
        return $value;
    }
    
    function delete( string $name ): self
    {
        $_SESSION[ $this->namespace ][ 'wwObjectsHashArray' ][ $name ]    = null;
        $_SESSION[ $this->namespace ][ $name ]                            = null;
        
        return $this;
    }
    
    function destroy(): self
    {
        $_SESSION[ $this->namespace ] = null;
        
        return $this;
    }
    
    function kill(): self
    {
        session_unset();
        
        return $this;
    }
    
    function resetAll(): self
    {
        session_destroy();
        
        return $this;
    }
    
    function pushTo( string $name, mixed $value ): self
    {
        $array = $_SESSION[ $this->namespace ][ $name ] ?? [];
        
        if( !is_array($array) ){
            $array = [ $array ];
        }
        
        $array[] = $value;
        
        $_SESSION[ $this->namespace ][ $name ] = $array;
        
        return $this;
    }
    
    function mergeTo( string $name, array $value ): self
    {
        $array = $_SESSION[ $this->namespace ][ $name ] ?? [];
        
        if( !is_array($array) ){
            $array = [ $array ];
        }
        
        $_SESSION[ $this->namespace ][ $name ] = array_replace_recursive($array, $value);
        
        return $this;
    }

    function removeFrom( string $name, mixed $value ): self
    {
        $array = $_SESSION[ $this->namespace ][ $name ] ?? [];
        
        if( !is_array($array) ){
            $array = [ $array ];
        }
        
        $newArray = [];
        foreach( $array as $arrayItem ){
            if( $value !== $arrayItem ){
                $newArray[] = $arrayItem;
            }
        }
        unset($array);        
        
        $_SESSION[ $this->namespace ][ $name ] = $newArray;
        
        return $this;
    }    
}
