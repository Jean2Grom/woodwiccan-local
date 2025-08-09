<?php
namespace WW\User;

use WW\WoodWiccan;
use WW\DataAccess\UserDataAccess as UserDA;
use WW\Website;

/**
 * Class to handle security access profiles
 * 
 * @author Jean2Grom
 */
class Profile 
{
    public $id;
    public $name;
    public $site;
    public $policies;
    
    /** 
     * WoodWiccan container class to allow whole access to Kernel
     * @var WoodWiccan
     */
    public WoodWiccan $ww;
    
    function __construct( WoodWiccan $ww )
    {
        $this->ww = $ww;
        
        $this->id       = false;
        $this->name     = false;
        $this->policies = [];
    }
    
    static function createFromId( WoodWiccan $ww, int $id )
    {
        $profiles = self::listProfiles( $ww, [ '`profile`.`id`' => $id ] );
        
        return $profiles[ $id ] ?? false;
    }
    
    static function createFromData(  WoodWiccan $ww, array $data )
    {
        $profile = new self( $ww );
        
        $profile->id    = $data['id'];
        $profile->name  = $data['name'];
        $profile->site  = $data['site'];
        
        if( $profile->site === '*' ){
            $statusLabels = $ww->configuration->read("global", "status");
        }
        elseif( $ww->website->name === $profile->site ){
            $statusLabels = $ww->website->status;
        }
        else
        {
            $website = new Website( $ww, $profile->site );
            $statusLabels = $website->status;
        }
        
        foreach( $data['policies'] as $policyData )
        {
            if( !is_null($policyData['status']) && !empty($statusLabels[ $policyData['status'] ]) ){
                $policyData['statusLabel'] = $statusLabels[ $policyData['status'] ];
            }
            
            $police = Policy::createFromData( $profile, $policyData );
            $profile->policies[ $police->id ] = $police;
        }
        
        return $profile;
    }
    
    function delete()
    {
        $this->ww->db->begin();
        try {
            if( !empty($this->policies) ){
                UserDA::deletePolicies( $this->ww, array_keys($this->policies) );
            }
            
            UserDA::deleteProfile( $this->ww, $this->id );
        } 
        catch( \Exception $e ) 
        {
            $this->ww->log->error($e->getMessage());
            $this->ww->db->rollback();
            return false;
        }
        $this->ww->db->commit();
        
        return true;
    }
    
    static function listProfiles( WoodWiccan $ww, array $conditions=[] )
    {
        $profiles = [];
        foreach( UserDA::getProfiles($ww, $conditions) as $profileDataItem ){
            $profiles[ $profileDataItem['id'] ] = self::createFromData( $ww, $profileDataItem );
        }
        
        return $profiles;
    }
    
    
    static function createNew( WoodWiccan $ww, array $newProfileData=[] )
    {
        if( empty($newProfileData['name']) || empty($newProfileData['site']) ){
            return false;
        }
        
        $ww->db->begin();
        try {
            $profileId = UserDA::insertProfile($ww, $newProfileData['name'], $newProfileData['site']);
            
            if( !empty($newProfileData['policies']) ){
                UserDA::insertPolicies($ww, $profileId, $newProfileData['policies']);
            }
        } 
        catch( \Exception $e ) 
        {
            $ww->log->error($e->getMessage());
            $ww->db->rollback();
            return false;
        }
        $ww->db->commit();
        
        return $profileId;
    }
    
    
    static function edit( WoodWiccan $ww, array $profileData=[] )
    {
        if( empty($profileData['id']) ){
            return false;
        }
        
        $ww->db->begin();
        try {
            UserDA::updateProfile( $ww, $profileData['id'], $profileData );
            
            if( !empty($profileData["policiesToDelete"]) ){
                UserDA::deletePolicies( $ww, $profileData["policiesToDelete"] );
            }
            
            $newPolicies        = [];
            $updatedPolicies    = [];
            foreach( $profileData["policies"] as $policyData ){
                if( !empty($policyData['id']) ){
                    $updatedPolicies[] = $policyData;
                }
                else {
                    $newPolicies[] = $policyData;
                }
            }
            
            if( !empty($newPolicies) ){
                UserDA::insertPolicies($ww, $profileData['id'], $newPolicies);
            }
            
            if( !empty($updatedPolicies) ){
                UserDA::updatePolicies($ww, $profileData['id'], $updatedPolicies);
            }
        } 
        catch( \Exception $e ) 
        {
            $ww->log->error($e->getMessage());
            $ww->db->rollback();
            return false;
        }
        $ww->db->commit();
        
        return true;
    }
}
