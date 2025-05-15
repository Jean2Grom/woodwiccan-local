<?php
namespace WW;

use WW\Handler\UserHandler as Handler;
use WW\Trait\PropertiesAccessTrait;

/**
 * Class handeling User information and security access policies
 * 
 * @author Jean2Grom
 */
class User 
{
    use PropertiesAccessTrait;

    public ?int $id         = null;
    public string $name     = '';
    public array $profiles  = [];
    public array $policies  = [];

    public $connexion           = false;
    public ?array $properties   = null;

    public $loginMessages   = [];
    
    /** 
     * PHP Session WW Custom Handler 
     * @var Session
     */
    public Session $session;
    
    /** 
     * WoodWiccan container class to allow whole access to Kernel
     * @var WoodWiccan
     */
    public WoodWiccan $ww;
    
    function __construct( WoodWiccan $ww )
    {
        $this->ww           = $ww;
        $this->session      = new Session($this->ww);
        
        // Get last connexion 
        $sessionUser = $this->session->read('user');
        if( $sessionUser )
        {
            $this->properties   = $sessionUser;

            $this->profiles     = $this->properties['profiles'];
            $this->policies     = Handler::listPolicies( $this->profiles );

            $this->id           = $this->properties['id']? (int) $this->properties['id']: null;
            $this->name         = $this->properties['name'] ?? array_values($this->profiles)[0] ?? '';

            $this->connexion    = (bool) ($this->id);
        }

        else // No user log in, get default user (="public user") from configuration
        {
            $publicProfile  = Handler::getPublicProfile( $this->ww );

            $this->name     = $publicProfile['name'];
            $this->profiles = $publicProfile['profiles'];
            $this->policies = $publicProfile['policies'];
        }

        // if( empty($this->policies) )
        // {
        //     $this->session->destroy();
        //     $this->loginMessages[] = "Problem whith this system: unable to log user";
        //     $this->loginMessages[] = "Please contact administrator";
        //     $this->ww->log->error('Login failed : accessing policies impossible', true);
        // }
    }
    
    
    function init( array $loginData ): bool
    {
        $this->loginMessages =  $loginData['errors'] ?? [];

        if( !$loginData['data'] ){
            return false;
        }

        $this->connexion        = true;
        $this->properties       = $loginData['data'];

        $this->id               = $this->properties['id'];
        $this->name             = $this->properties['name'];
        $this->profiles         = $this->properties['profiles'];
        
        $this->policies         = Handler::listPolicies( $this->profiles );        
        $this->session->write( 'user', $this->properties );
        
        return true;
    }
    
    
    function disconnect(): self
    {
        $this->session->unset();
        $this->connexion = false;
        
        $publicProfile  = Handler::getPublicProfile( $this->ww );
        
        $this->name     = $publicProfile['name'];
        $this->profiles = $publicProfile['profiles'];
        $this->policies = $publicProfile['policies'];
        
        return $this;
    }
    
    function getAlerts(): array
    {
        $alerts = $this->session->read('alerts');
        $this->session->delete('alerts');
        
        if( !$alerts ){
            return [];
        }
        
        return $alerts;
    }
    
    function addAlerts( array $newAlerts ): self
    {
        foreach( $newAlerts as $newAlertItem ){
            $this->session->pushTo('alerts', $newAlertItem);
        }
        
        return $this;
    }

    function addAlert( array $newAlert ){
        return $this->addAlerts([ $newAlert ]);
    }
    
    function getSessionData( string $varname ){
        return $this->session->read( $varname );
    }
    
    function setSessionData( string $varname, mixed $varvalue ): self
    {
        $this->session->write( $varname, $varvalue );
        
        return $this;
    }
    
    function addToSessionData( string $varname, mixed $varvalue ): self
    {
        $this->session->pushTo( $varname, $varvalue );
        
        return $this;
    }    
}
