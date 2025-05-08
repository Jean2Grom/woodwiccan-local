<?php
namespace WW;

use WW\DataAccess\User as DataAccess;

/**
 * Class handeling User information and security access policies
 * 
 * @author Jean2Grom
 */
class User 
{
    public $id;
    public $name;
    public $profiles;
    public $policies;
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
        
        $this->connexion    = false;
        $this->id           = 0;
        $this->name         = '';
        $this->profiles     = [];
        $this->policies     = [];
        
        // If previous page is login page
        if( $this->ww->request->param('action') === 'login' )
        {
            $loginFailure       = false;
            $userName           = $this->ww->request->param('username');
            $userConnexionData  = DataAccess::getUserLoginData( $this->ww, $userName );
            
            if( count($userConnexionData) == 0 )
            {
                $loginFailure           = true;
                $this->loginMessages[]  = "Unknown username";
                $this->ww->debug->dump('Login failed : unknown username');
            }
            elseif( count($userConnexionData) > 1 ) 
            {
                $loginFailure           = true;
                $this->loginMessages[]  = "Problem whith this username: multiple match ";
                $this->loginMessages[]  = "Please contact administrator";
                $this->ww->log->error('Login failed : multiple username match');
            }
            
            if( !$loginFailure )
            {
                $connexionData  = array_values($userConnexionData)[0];                
                
                if( !password_verify( $this->ww->request->param('password'), $connexionData['pass_hash'] ) )
                {
                    $loginFailure           = true;
                    $this->loginMessages[]  = "Wrong password, please try again";
                    $this->ww->debug->dump('Login failed : wrong password for login: '.$userName);
                }                
            }
            
            if( !$loginFailure )
            {
                $this->connexion        = true;
                $this->profiles         = $connexionData['profiles'];
                $this->id               = $connexionData['id'];
                $this->name             = $connexionData['name'];
                $this->connexionData    = $connexionData;
                
                foreach( $connexionData['profiles'] as $profileData ){
                    foreach( $profileData['policies'] as $policyId => $policyData ){
                        if( empty($this->policies[ $policyId ]) ){
                            $this->policies[ $policyId ] = $policyData;
                        }
                    }
                }
                
                $this->session->write(
                    'user', 
                    [
                        'connexionID'   => $this->id,
                        'name'          => $this->name,
                        'profiles'      => $this->profiles,
                        'policies'      => $this->policies,
                        'connexionData' => $this->connexionData,
                    ]
                );
            }
        }
        
        // Get last connexion 
        $sessionData = $this->session->read('user');
        if( !$this->connexion && $sessionData )
        {
            $this->profiles         = $sessionData['profiles'];
            $this->policies         = $sessionData['policies'];
            $this->id               = $sessionData['connexionID'] ?? false;
            $this->name             = $sessionData['name'] ?? array_values($this->profiles)[0] ?? '';
            $this->connexionData    = $sessionData['connexionData'] ?? false;
            $this->connexion        = (bool) ($this->id);
        }
        elseif( !$this->connexion ) // No user log in, get default user (="public user") from configuration
        {
            $this->name     = $this->ww->configuration->read('system', 'publicUser') ?? "Public";
            $publicProfile  = $this->ww->configuration->read('system', 'publicUserProfile') ?? 'public';
            
            //$getProfilePolicies = DataAccess::getProfilePolicies( $ww, $publicProfile );
            //$this->profiles = $getProfilePolicies['profiles'];
            //$this->policies = $getProfilePolicies['policies'];

            $this->profiles = [ $publicProfile ];
            $this->policies = DataAccess::getProfilePolicies( $ww, $publicProfile );
            
            $this->session->write(
                'user', 
                [
                    'name'          => $this->name,
                    'profiles'      => $this->profiles,
                    'policies'      => $this->policies,
                    'connexionID'   => false,
                    'connexionData' => false,
                ]
            );            
        }
        
        if( empty($this->policies) )
        {
            $this->session->destroy();
            $this->loginMessages[] = "Problem whith this system: unable to log user";
            $this->loginMessages[] = "Please contact administrator";
            $this->ww->log->error('Login failed : accessing policies impossible', true);
        }
    }
    
    function connectTo( string $login )
    {
        $userConnexionData = DataAccess::getUserLoginData( $this->ww, $login );
        
        if( count($userConnexionData) == 0 )
        {
            $this->loginMessages[] = "Unknown username";
            $this->ww->debug->dump('Login failed : unknown username');
            return false;
        }
        elseif( count($userConnexionData) > 1 ) 
        {
            $this->loginMessages[] = "Problem whith this username: multiple match ";
            $this->loginMessages[] = "Please contact administrator";
            $this->ww->log->error('Login failed : multiple username match');
            
            return false;
        }
        
        $connexionData = array_values($userConnexionData)[0];
        
        $this->connexion        = true;
        $this->profiles         = $connexionData['profiles'];
        $this->id               = $connexionData['id'];
        $this->name             = $connexionData['name'];
        $this->connexionData    = $connexionData;
        
        foreach( $connexionData['profiles'] as $profileData ){
            foreach( $profileData['policies'] as $policyId => $policyData ){
                if( empty($this->policies[ $policyId ]) ){
                    $this->policies[ $policyId ] = $policyData;
                }
            }
        }
        
        $this->session->write(
            'user', 
            [
                'connexionID'   => $this->id,
                'name'          => $this->name,
                'profiles'      => $this->profiles,
                'policies'      => $this->policies,
                'connexionData' => $this->connexionData,
            ]
        );
        
        return true;
    }
    
    function disconnect()
    {
        $this->session->destroy();
        $this->connexion = false;
        
        $this->name     = $this->ww->configuration->read('system', 'publicUser') ?? "Public";
        $publicProfile  = $this->ww->configuration->read('system', 'publicUserProfile') ?? 'public';
        
        // $getProfilePolicies = DataAccess::getProfilePolicies( $this->ww, $publicProfile );
        // $this->profiles = $getProfilePolicies['profiles'];
        // $this->policies = $getProfilePolicies['policies'];

        $this->profiles = [ $publicProfile ];
        $this->policies = DataAccess::getProfilePolicies( $this->ww, $publicProfile );
        
        $this->session->write(
            'user', 
            [
                'name'          => $this->name,
                'profiles'      => $this->profiles,
                'policies'      => $this->policies,
                'connexionID'   => false,
                'connexionData' => false,
            ]
        );            
        
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

    function addAlert( array $newAlert ) {
        return $this->addAlerts([ $newAlert ]);
    }
    
    function getSessionData( string $varname ){
        return $this->session->read( $varname );
    }
    
    function setSessionData( string $varname, mixed $varvalue )
    {
        $this->session->write( $varname, $varvalue );
        
        return $this;
    }
    
    function addToSessionData( string $varname, mixed $varvalue )
    {
        $this->session->pushTo( $varname, $varvalue );
        
        return $this;
    }    
}
