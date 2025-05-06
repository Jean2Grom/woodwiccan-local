<?php
namespace WW\User;

use WW\WoodWiccan;
use WW\Website;

/**
 * Class to handle a single security access policy
 * 
 * @author Jean2Grom
 */
class Policy 
{
    public $id;
    public $module;
    public $position;
    public $position_rules;
    public $custom_limitation;
    public $status;
    public $positionName;
    public $positionId;
    public $statusLabel;
    
    /** 
     * Class to handle security access profiles
     * @var Profile
     */
    public Profile $profile;
    
    /** 
     * WoodWiccan container class to allow whole access to Kernel
     * @var WoodWiccan
     */
    public WoodWiccan $ww;
    
    function __construct( WoodWiccan $ww, Profile $profile )
    {
        $this->ww       = $ww;
        $this->profile  = $profile;
    }
    
    static function createFromData(  Profile $profile, array $data, ?Website $website=null )
    {
        $police = new self( $profile->ww, $profile );
        
        $police->id                = $data['id'];
        $police->module            = $data['module'];
        $police->status            = $data['status'] ?? '*';
        $police->position          = $data['position'];
        $police->position_rules    = $data['position_rules'];
        $police->custom_limitation = $data['custom_limitation'];
        $police->positionName      = $data['positionName'];
        $police->positionId        = $data['positionId'];
        
        if( $website )
        {
            $profile->ww->dump( $police->status );
        }  
        
        $police->statusLabel       = $data['statusLabel'] ?? '*';
        
        return $police;
    }
}
