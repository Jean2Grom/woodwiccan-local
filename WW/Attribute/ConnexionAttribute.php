<?php
namespace WW\Attribute;

use WW\Craft;
use WW\DataAccess\User;

/**
 * Class to handle Connexion Attributes
 * 
 * @author Jean2Grom
 */
class ConnexionAttribute extends \WW\Attribute  
{
    const ATTRIBUTE_TYPE    = "connexion";
    const ELEMENTS          = [
        "id" => "INT(11) DEFAULT NULL",
    ];
    const PARAMETERS        = [];
    
    public $password;
    public $password_confirm;
    public $login;
    public $email;
    
    function __construct( \WW\WoodWiccan $ww, string $attributeName, array $params=[], ?Craft $craft=null )
    {
        parent::__construct( $ww, $attributeName, $params, $craft );
        
        $this->values       =   [
            'id'                => null,
            'name'              => "",
            'login'             => "",
            'email'             => "",
            'pass_hash'         => "",
            'profiles'          => [],
        ];
        
        $this->joinTables   =   [
            [
                'table'     =>  "user__connexion",
                'condition' =>  ":user__connexion.`id` = :craft_table.`".$this->name."@connexion#id`",
            ],
            [
                'table'     =>  "user__rel__connexion__profile",
                'condition' =>  ":user__rel__connexion__profile.`fk_connexion` = :user__connexion.`id`",
            ],
        ];
        
        $this->joinFields   =   [
            'name'          =>  ":user__connexion.`name` AS :craft_table|".$this->name."#name`",
            'login'         =>  ":user__connexion.`login` AS :craft_table|".$this->name."#login`",
            'email'         =>  ":user__connexion.`email` AS :craft_table|".$this->name."#email`",
            'pass_hash'     =>  ":user__connexion.`pass_hash` AS :craft_table|".$this->name."#pass_hash`",
            'profile_id'    =>  ":user__rel__connexion__profile.`fk_profile` AS :craft_table|".$this->name."#profile_id`",
        ];
    }
    
    function getEditParams(): array
    {
        return [
            self::getColumnName( $this->type, $this->name, 'id' ),
            self::getColumnName( $this->type, $this->name, 'name' ),
            self::getColumnName( $this->type, $this->name, 'login' ),
            self::getColumnName( $this->type, $this->name, 'email' ),
            self::getColumnName( $this->type, $this->name, 'password' ),
            self::getColumnName( $this->type, $this->name, 'password_confirm' ),
            [
                'name'      => self::getColumnName( $this->type, $this->name, 'profiles' ), 
                'option'    => FILTER_REQUIRE_ARRAY 
            ],
        ];
    }
    
    
    function set( $args )
    {
        foreach( $args as $key => $value ){
            if( $key == 'profile_id' && is_array($value) ){
                foreach( $value as $profileId ){
                    if( !in_array($profileId, $this->values['profiles']) ){
                        $this->values['profiles'][] = $profileId;
                    }
                }
            }
            elseif( $key == 'profile_id' ){
                $this->values['profiles'] = [ $value ];
            }
            else {
                $this->values[ $key ] = $value;
            }
        }
        
        return $this;
    }
    
    function setValue( $key, $value )
    {
        if( in_array($key, array_keys($this->values) ) ){
            if( is_string($value) ){
                $this->values[ $key ] = trim($value);
            }
            else {
                $this->values[ $key ] = $value;
            }
        }
        
        if( $key == 'password' ){
            $this->password = $value;
        }
        elseif( $key == 'password_confirm' ){
            $this->password_confirm = $value;
        }
        elseif( $key == 'login' ){
            $this->login = $value;
        }
        elseif( $key == 'email' ){
            $this->email = $value;
        }
        
        return $this;
    }
    
    function save( ?Craft $craft=null ) 
    {
        parent::save( $craft );
        
        $craftAttributeData = [
            'table' => $craft->structure->table,
            'type'  => $this->type,
            'var'   => 'id',
            'name'  => $this->name,
        ];
        
        if( !empty($this->values['id']) ){
            return User::updateConnexion( $this->ww, $this->values['id'], $this->values, $craftAttributeData );
        }
        
        $this->values['id'] = User::insertConnexion( $this->ww, $this->values, $craftAttributeData );
        
        return 1;            
    }
    
    
    function clone( Craft $craft )
    {
        $clonedAttribute = parent::clone( $craft );
        
        unset($clonedAttribute->values['id']);
        
        return $clonedAttribute;
    }

    function update( array $params )
    {
        parent::update( $params );
        
        if( $this->password && $this->password_confirm ){
            if( $this->password !== $this->password_confirm ){
                $this->ww->user->addAlerts([[
                    'level'     =>  'warning',
                    'message'   =>  "Password mismatch"
                ]]);
            }
            else 
            {
                $this->values['pass_hash'] = password_hash( $this->password, PASSWORD_DEFAULT );
                $this->ww->user->addAlerts([[
                    'level'     =>  'info',
                    'message'   =>  "Password changed"
                ]]);
            }
        }
        
        if( $this->login || $this->email )
        {
            $contentKeyId = $this->craft->structure->type == Craft::TYPES[0]? $this->craft->id: ($this->craft->content_key ?? null);
            
            $checkEmailLoginValidity = User::checkEmailLoginValidity($this->ww, $this->login, $this->email, $contentKeyId);
            
            $this->ww->dump($checkEmailLoginValidity);
            
            if( $checkEmailLoginValidity['login'] > 0 )
            {
                $this->ww->user->addAlerts([[
                    'level'     =>  'warning',
                    'message'   =>  "Login \"".$this->login."\" already in use"
                ]]);
                
                $this->values['login'] = null;
            }
            
            if( $checkEmailLoginValidity['email'] > 0 )
            {
                $this->ww->user->addAlerts([[
                    'level'     =>  'warning',
                    'message'   =>  "Email \"".$this->email."\" already in use"
                ]]);
                
                $this->values['email'] = null;
            }
        }
        else {
            $this->ww->user->addAlerts([[
                'level'     =>  'warning',
                'message'   =>  "At least a login or an email is required"
            ]]);
        }
    }
}