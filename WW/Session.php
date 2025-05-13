<?php
namespace WW;

/**
 * PHP Session WW Custom Handler 
 * 
 * @author Jean2Grom
 */
class Session 
{
    public string   $namespace;
    public bool     $active = false;
    
    /** 
     * WoodWiccan container class to allow whole access to Kernel
     * @var WoodWiccan
     */
    public WoodWiccan $ww;
    
    function __construct( WoodWiccan $ww, ?string $namespace=null )
    {
        $this->ww           = $ww;
        $this->namespace    = $namespace ?? $this->ww->website->name;
        
        if( filter_input(INPUT_COOKIE, 'PHPSESSID') )
        {
            // if( session_status() !== PHP_SESSION_ACTIVE ){
            //     session_start();
            // }
            
            // if( empty($_SESSION[ $this->namespace ]) ){
            //     $_SESSION[ $this->namespace ] = [];
            // }

            self::init( $this->namespace );
            $this->active = true;
        }
    }
    
    private static function init( $namespace ): void
    {
        if( session_status() !== PHP_SESSION_ACTIVE ){
            session_start();
        }
        
        if( empty($_SESSION[ $namespace ]) ){
            $_SESSION[ $namespace ] = [];
        }

        return;
    }

    function write( string $name, mixed $value ): self
    {
        if( !$this->active ){
            self::init( $this->namespace );
        }

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
        if( !$this->active ){
            return false;
        }

        $value      = $_SESSION[ $this->namespace ][ $name ] ?? false;
        $objectHash = $_SESSION[ $this->namespace ][ 'wwObjectsHashArray' ][ $name ] ?? false;
        
        if( $objectHash && hash_hmac('sha256', $value, session_id()) === $objectHash ){
            return unserialize($value);
        }
        
        return $value;
    }
    
    function delete( string $name ): self
    {
        if( $this->active )
        {
            $_SESSION[ $this->namespace ][ 'wwObjectsHashArray' ][ $name ]    = null;
            $_SESSION[ $this->namespace ][ $name ]                            = null;
        }
        
        return $this;
    }
    
    function unset(): self
    {
        if( $this->active ){
            $_SESSION[ $this->namespace ] = null;
        }
        
        return $this;
    }
    
    function kill(): self
    {
        if( $this->active ){
            session_unset();
        }
        
        return $this;
    }
    
    function destroy(): self
    {
        if( !$this->active ){
            return $this;
        }

        if( ini_get("session.use_cookies") ) 
        {
            $params = session_get_cookie_params();
            setcookie(
                session_name(), 
                '', 
                time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }

        session_destroy();
        
        return $this;
    }
    
    function pushTo( string $name, mixed $value ): self
    {
        if( !$this->active ){
            self::init( $this->namespace );
        }

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
        if( !$this->active ){
            self::init( $this->namespace );
        }

        $array = $_SESSION[ $this->namespace ][ $name ] ?? [];
        
        if( !is_array($array) ){
            $array = [ $array ];
        }
        
        $_SESSION[ $this->namespace ][ $name ] = array_replace_recursive($array, $value);
        
        return $this;
    }

    function removeFrom( string $name, mixed $value ): self
    {
        if( !$this->active ){
            self::init( $this->namespace );
        }

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
