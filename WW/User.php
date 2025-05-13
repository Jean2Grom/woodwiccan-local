<?php
namespace WW;

use WW\Handler\UserHandler as Handler;
use WW\DataAccess\UserDataAccess as DataAccess;
use WW\Handler\UserHandler;

/**
 * Class handeling User information and security access policies
 * 
 * @author Jean2Grom
 */
class User 
{
    public ?int $id         = null;
    public string $name     = '';
    public array $profiles  = [];
    public array $policies  = [];

    public $connexion      = false;
    public $connexionData  = false;
    public $loginMessages  = [];
    
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
        $sessionData = $this->session->read('user');
        if( $sessionData )
        {
            $this->id               = $sessionData['ID']? (int) $sessionData['ID']: null;
            $this->name             = $sessionData['name'] ?? array_values($this->profiles)[0] ?? '';
            $this->profiles         = $sessionData['profiles'];
            $this->policies         = Handler::listPolicies( $this->profiles );

            $this->connexionData    = $sessionData['connexionData'] ?? false;
            $this->connexion        = (bool) ($this->id);
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
        $this->connexionData    = $loginData['data'];

        $this->id               = $this->connexionData['id'];
        $this->name             = $this->connexionData['name'];
        $this->profiles         = $this->connexionData['profiles'];
        
        $this->policies         = Handler::listPolicies( $this->profiles );
        
        $this->session->write(
            'user', 
            [
                'ID'            => $this->id,
                'name'          => $this->name,
                'profiles'      => $this->profiles,
                'policies'      => $this->policies,
                'connexionData' => $this->connexionData,
            ]
        );
        
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
