<?php
namespace WW\DataAccess;

use WW\WoodWiccan;
use WW\Witch;
use WW\Handler\WitchHandler;
use WW\Cairn;
use WW\Craft;
use WW\Attribute;

/**
 * Class to aggregate User related data access functions
 * all these functions are statics
 * 
 * @author Jean2Grom
 */
class User
{    
    static function getUserLoginData( WoodWiccan $ww, string $login, ?string $site=null  )
    {
        if( empty($login) ){
            return [];
        }
        
        $query = "";
        $query  .=  "SELECT `user__connexion`.`id` AS `connexion_id` ";
        $query  .=  ", `user__connexion`.`name` AS `connexion_name` ";
        $query  .=  ", `user__connexion`.`email` AS `connexion_email` ";
        $query  .=  ", `user__connexion`.`login` AS `connexion_login` ";
        $query  .=  ", `user__connexion`.`pass_hash` AS `connexion_pass_hash` ";
        $query  .=  ", `user__connexion`.`craft_table` AS `connexion_craft_table` ";
        $query  .=  ", `user__connexion`.`craft_attribute` AS `connexion_craft_attribute` ";
        $query  .=  ", `user__connexion`.`craft_attribute_var` AS `connexion_craft_attribute_var` ";
        $query  .=  ", `user__connexion`.`attribute_name` AS `connexion_attribute_name` ";
        
        $query  .=  ", `profile`.`id` AS `profile_id` ";
        $query  .=  ", `profile`.`name` AS `profile_name` ";
        $query  .=  ", `profile`.`site` AS `profile_site` ";
        
        $query  .=  ", `policy`.`id` AS `policy_id` ";
        $query  .=  ", `policy`.`module` AS `policy_module` ";
        $query  .=  ", `policy`.`status` AS `policy_status` ";
        $query  .=  ", `policy`.`position_ancestors` AS `policy_position_ancestors` ";
        $query  .=  ", `policy`.`position_included` AS `policy_position_included` ";
        $query  .=  ", `policy`.`position_descendants` AS `policy_position_descendants` ";
        $query  .=  ", `policy`.`custom_limitation` AS `policy_custom_limitation` ";
        
        foreach( Witch::FIELDS as $field ){
            $query      .=  ", `witch`.`".$field."` ";
        }
        for( $i=1; $i<=$ww->depth; $i++ ){
            $query      .=  ", `witch`.`level_".$i."` ";
        }
        
        $query  .=  "FROM `user__connexion` ";
        $query  .=  "LEFT JOIN `user__rel__connexion__profile` ";
        $query  .=      "ON `user__rel__connexion__profile`.`fk_connexion` = `user__connexion`.`id` ";
        $query  .=  "LEFT JOIN `user__profile` AS `profile` ";
        $query  .=      "ON `profile`.`id` = `user__rel__connexion__profile`.`fk_profile` ";
        $query  .=  "LEFT JOIN `user__policy` AS `policy` ";
        $query  .=      "ON `policy`.`fk_profile` = `profile`.`id` ";
        $query  .=  "LEFT JOIN `witch` ";
        $query  .=      "ON `witch`.`id` = `policy`.`fk_witch` ";

        $query  .=  "WHERE ( `email`= :login OR `login`= :login ) ";
        $query  .=  "AND `user__connexion`.`craft_table` LIKE '".Craft::TYPES[0]."__%' ";
        
        $query  .=  "AND ( `profile`.`site` = :site ";
        $query  .=      "OR `profile`.`site` = '*' ";
        $query  .=  ") ";
        
        $result = $ww->db->multipleRowsQuery($query, [ 
            'login' => trim($login),
            'site'  => $site ?? $ww->website->site,
        ]);
        
        $userConnexionData = [];
        foreach( $result as $row )
        {
            $userConnexionId = $row['connexion_id'];
            if( empty($userConnexionData[ $userConnexionId ]) )
            {
                $craftColumn = Attribute::getColumnName(
                    $row['connexion_craft_attribute'],  
                    $row['connexion_attribute_name'], 
                    $row['connexion_craft_attribute_var']
                );
                
                $userConnexionData[ $userConnexionId ] = [
                    'id'            => $userConnexionId,
                    'name'          => $row['connexion_name'],
                    'email'         => $row['connexion_email'],
                    'login'         => $row['connexion_login'],
                    'pass_hash'     => $row['connexion_pass_hash'],
                    'craft_table'  => $row['connexion_craft_table'],
                    'craft_column' => $craftColumn,
                    'profiles'      => [],
                ];
            }

            $userProfileId = $row['profile_id'];
            if( empty($userConnexionData[ $userConnexionId ]['profiles'][ $userProfileId ]) ){
                $userConnexionData[ $userConnexionId ]['profiles'][ $userProfileId ] = [
                    'id'        =>  $userProfileId,
                    'name'      =>  $row['profile_name'],
                    'policies'  =>  [],
                ];
            }

            $userPolicyId = $row['policy_id'];
            if( empty($userConnexionData[ $userConnexionId ]['profiles'][ $userProfileId ]['policies'][ $userPolicyId ]) )
            {
                $position = false;
                if( !empty($row['id']) )
                {
                    $positionWitch  = WitchHandler::instanciate($ww, $row);
                    $position       = $positionWitch->position;
                }

                $userConnexionData[ $userConnexionId ]['profiles'][ $userProfileId ]['policies'][ $userPolicyId ] = [
                    'id'                =>  $userPolicyId,
                    'module'            => $row['policy_module'],
                    'status'            => $row['policy_status'] ?? '*',
                    'custom_limitation' => $row['policy_custom_limitation'],
                    'position'          => $position,
                    'position_rules'    => [
                        'ancestors'         => (boolean) $row['policy_position_ancestors'],
                        'self'              => (boolean) $row['policy_position_included'],
                        'descendants'       => (boolean) $row['policy_position_descendants'],
                    ],
                ];
            }
        }
        
        return $userConnexionData;
    }
    
    static function getUserWitchFromConnexionData( WoodWiccan $ww, $connexionData ) 
    {
        $savedConnexionData     = $ww->user->connexionData ?? [];
        $savedConnexionValue    = $ww->user->connexion ?? false;
        
        $ww->user->connexionData    = $connexionData;
        $ww->user->connexion        = 1;
        
        $configuration = [
            'target' => [
                'user'  => true,
                'craft' => true,
            ]
        ];
        
        $witches        = Summoning::witches($ww, Cairn::prepareConfiguration($ww->website, $configuration) );
        
        $ww->user->connexionData    = $savedConnexionData;
        $ww->user->connexion        = $savedConnexionValue;
        
        return $witches['target'] ?? false;
    }
    
    
    static function getProfilePolicies(  WoodWiccan $ww, string $profile, ?string $site=null )
    {
        $policies = $ww->cache->read( 'profile', $profile );

        if( $policies ){
            return $policies;
        }

        $query = "";
        $query  .=  "SELECT `user__profile`.`id` AS `profile_id` ";
        $query  .=  ", `user__profile`.`name` AS `profile_name` ";

        $query  .=  ", `policy`.`id` AS `policy_id` ";
        $query  .=  ", `policy`.`module` AS `policy_module` ";
        $query  .=  ", `policy`.`status` AS `policy_status` ";
        $query  .=  ", `policy`.`position_ancestors` AS `policy_position_ancestors` ";
        $query  .=  ", `policy`.`position_included` AS `policy_position_included` ";
        $query  .=  ", `policy`.`position_descendants` AS `policy_position_descendants` ";
        $query  .=  ", `policy`.`custom_limitation` AS `policy_custom_limitation` ";
        $query  .=  ", `policy`.`fk_witch` AS `policy_fk_witch` ";
        
        $query  .=  ", `witch`.* ";
        
        $query  .=  "FROM `user__profile` ";
        $query  .=  "LEFT JOIN `user__policy` AS `policy` ";
        $query  .=      "ON `policy`.`fk_profile` = `user__profile`.`id` ";
        $query  .=  "LEFT JOIN `witch` ";
        $query  .=      "ON `witch`.`id` = `policy`.`fk_witch` ";
        
        $query  .=  "WHERE `user__profile`.`name` = :profile ";
        $query  .=  "AND ( `user__profile`.`site` = :site ";
        $query  .=      "OR `user__profile`.`site` = '*' ";
        $query  .=  ") ";
        
        $result = $ww->db->multipleRowsQuery($query, [ 
            'profile'   => $profile, 
            'site'      => $site ?? $ww->website->site,
        ]);
        
        // $profiles   = [];
        $policies   = [];
        foreach( $result as $row )
        {
            if( $row['policy_fk_witch'] 
                && $row['policy_fk_witch'] !== $row['id'] 
            ){
                continue;
            }
            
            // if( empty($profiles[ $row['profile_id'] ]) ){
            //     $profiles[ $row['profile_id'] ] = $row['profile_name'];
            // }
            
            if( empty($policies[ $row['policy_id'] ]) )
            {
                $position = false;
                if( !empty($row['id']) )
                {
                    $positionWitch  = WitchHandler::instanciate($ww, $row);
                    $position       = $positionWitch->position;
                }

                $policies[ $row['policy_id'] ] = [
                    'module'            => $row['policy_module'],
                    'status'            => $row['policy_status'] ?? '*',
                    'custom_limitation' => $row['policy_custom_limitation'],
                    'position'          => $position,
                    'position_rules'    => [
                        'ancestors'         => (boolean) $row['policy_position_ancestors'],
                        'self'              => (boolean) $row['policy_position_included'],
                        'descendants'       => (boolean) $row['policy_position_descendants'],
                    ],
                ];
            }
        }
        
        $ww->cache->create( 'profile', $profile, $policies );

        // return [ 
        //     'profiles' => $profiles, 
        //     'policies' => $policies 
        // ];

        return $policies;
    }
    
    
    static function getProfiles( WoodWiccan $ww, array $conditions=[] )
    {
        $query = "";
        $query  .=  "SELECT `profile`.`id` AS `profile_id` ";
        $query  .=  ", `profile`.`name` AS `profile_name` ";
        $query  .=  ", `profile`.`site` AS `profile_site` ";
        
        $query  .=  ", `policy`.`id` AS `policy_id` ";
        $query  .=  ", `policy`.`module` AS `policy_module` ";
        $query  .=  ", `policy`.`status` AS `policy_status` ";
        $query  .=  ", `policy`.`position_ancestors` AS `policy_position_ancestors` ";
        $query  .=  ", `policy`.`position_included` AS `policy_position_included` ";
        $query  .=  ", `policy`.`position_descendants` AS `policy_position_descendants` ";
        $query  .=  ", `policy`.`custom_limitation` AS `policy_custom_limitation` ";
        
        foreach( Witch::FIELDS as $field ){
            $query      .=  ", `witch`.`".$field."` ";
        }
        for( $i=1; $i<=$ww->depth; $i++ ){
            $query      .=  ", `witch`.`level_".$i."` ";
        }
        
        $query  .=  "FROM `user__profile` AS `profile` ";
        $query  .=  "LEFT JOIN `user__rel__connexion__profile` ";
        $query  .=      "ON `user__rel__connexion__profile`.`fk_profile` = `profile`.id ";
        $query  .=  "LEFT JOIN `user__policy` AS `policy` ";
        $query  .=      "ON `policy`.`fk_profile` = `profile`.`id` ";
        $query  .=  "LEFT JOIN `witch` ";
        $query  .=      "ON `witch`.`id` = `policy`.`fk_witch` ";
        
        $params = [];
        if( !empty($conditions) )
        {
            $query .= "WHERE ";
            $separator = "";
            foreach( $conditions as $field => $conditionItem )
            {
                $query .= $separator;
                $separator = "AND ";
                
                if( is_array($conditionItem) ){
                    $values = $conditionItem;
                }
                else {
                    $values = [ $conditionItem ];
                }
                
                $query .= "( ";
                $innerSeparator = "";
                foreach( $values as $i => $value )
                {
                    $key = md5($field.$i.$value);
                    $params[ $key ] = $value;

                    $query .= $innerSeparator.$field." = :".$key." ";
                    $innerSeparator = "OR ";
                }
                $query .= ") ";
            }
        }
        
        $query  .=  "ORDER BY `profile_site` ASC, `profile_name` ASC ";
        
        $result = $ww->db->multipleRowsQuery($query, $params);
        
        $profilesData = [];
        foreach( $result as $row )
        {
            $userProfileId = $row['profile_id'];
            if( empty($profilesData[ $userProfileId ]) ){
                $profilesData[ $userProfileId ] = [
                    'id'        =>  $userProfileId,
                    'name'      =>  $row['profile_name'],
                    'site'      =>  $row['profile_site'],
                    'policies'  =>  [],
                ];
            }
            
            $userPolicyId = $row['policy_id'];
            if( empty($profilesData[ $userProfileId ]['policies'][ $userPolicyId ]) )
            {
                $position       = false;
                $positionWitch  = false;
                if( !empty($row['id']) )
                {
                    $positionWitch  = WitchHandler::instanciate($ww, $row);
                    $position       = $positionWitch->position;
                }

                $profilesData[ $userProfileId ]['policies'][ $userPolicyId ] = [
                    'id'                =>  $userPolicyId,
                    'module'            => $row['policy_module'],
                    'status'            => $row['policy_status'] ?? '*',
                    'custom_limitation' => $row['policy_custom_limitation'],
                    'position'          => $position,
                    'position_rules'    => [
                        'ancestors'         => (boolean) $row['policy_position_ancestors'],
                        'self'              => (boolean) $row['policy_position_included'],
                        'descendants'       => (boolean) $row['policy_position_descendants'],
                    ],
                    'positionName'      => $positionWitch->name ?? '',
                    'positionId'        => $positionWitch->id ?? '',
                ];
            }
        }
        
        return $profilesData;
    }
    
    static function insertProfile( WoodWiccan $ww, string $name, string $site )
    {
        if( empty($name) || empty($site) ){
            return false;
        }
        
        $query = "";
        $query  .=  "INSERT INTO user__profile (name, site) ";
        $query  .=  "VALUES ( :name, :site ) ";     
        
        return $ww->db->insertQuery($query, [ 'name' => $name, 'site' => $site ]);
    }
    
    static function insertPolicies( WoodWiccan $ww, int $profileId, array $data )
    {
        if( empty($profileId) || empty($data) ){
            return false;
        }
        
        $query = "";
        $query  .=  "INSERT INTO `user__policy` ";
        $query  .=  "( `fk_profile` ";
        $query  .=  ", `module` ";
        $query  .=  ", `status` ";
        $query  .=  ", `fk_witch` ";
        $query  .=  ", `position_ancestors` ";
        $query  .=  ", `position_included` ";
        $query  .=  ", `position_descendants` ";
        $query  .=  ", `custom_limitation` ";
        $query  .=  ") ";
        
        $query  .=  "VALUES ";
        $query  .=  "( :profile_id ";
        $query  .=  ", :module ";
        $query  .=  ", :status ";
        $query  .=  ", :fk_witch ";
        $query  .=  ", :position_ancestors ";
        $query  .=  ", :position_included ";
        $query  .=  ", :position_descendants ";
        $query  .=  ", :custom_limitation ";
        $query  .=  ") ";
        
        $params = [];
        foreach( $data as $policyData )
        {
            $policyParams = [ 
                'profile_id'            => $profileId,
                'fk_witch'              => null,
                'position_ancestors'    => 0,
                'position_included'     => 0,
                'position_descendants'  => 0,
                'custom_limitation'     => null,
            ];
            foreach( $policyData as $policyField => $policyFieldValue )
            {
                if( $policyField == 'module' ){
                    $policyParams['module'] = $policyFieldValue;
                }
                elseif( $policyField == 'status' ){
                    $policyParams[ 'status' ] = ($policyFieldValue != '*')? $policyFieldValue: null;
                }
                elseif( $policyField == 'witch' && is_numeric($policyFieldValue) ){
                    $policyParams['fk_witch'] = $policyFieldValue;
                }
                elseif( $policyField == 'custom' ){
                    $policyParams['custom_limitation'] = $policyFieldValue;
                }
                elseif( $policyField == 'witchRules' )
                {
                    $policyParams['position_ancestors']     = $policyFieldValue["ancestors"]? 1: 0;
                    $policyParams['position_included']      = $policyFieldValue["self"]? 1: 0;
                    $policyParams['position_descendants']   = $policyFieldValue["descendants"]? 1: 0;
                }
            }
            $params[] = $policyParams;
        }
        
        return $ww->db->insertQuery($query, $params, true);
    }
    
    static function updateProfile( WoodWiccan $ww, int $profileId, array $data )
    {
        if( empty($profileId) || empty($data['name']) || empty($data['site']) ){
            return false;
        }
        
        $query = "";
        $query  .=  "UPDATE `user__profile` ";
        
        $separator  = "SET ";
        $params     = [ 'id' => $profileId ];
        foreach( ['name', 'site'] as $field ){
            if( !empty($data[ $field ]) )
            {
                $query  .=  $separator." `".$field."` = :".$field." ";
                $params[ $field ] = $data[ $field ];
                $separator  = ", ";                
            }
        }
        
        $query  .=  "WHERE `id` = :id ";
        
        return $ww->db->updateQuery($query, $params);
    }
    
    static function deletePolicies( WoodWiccan $ww, array $policiesToDelete ) 
    {
        if( empty($policiesToDelete) ){
            return false;
        }
        
        $query = "";
        $query  .=  "DELETE FROM `user__policy` ";
        $query  .=  "WHERE `id` = :id ";
        
        $params = [];
        foreach( $policiesToDelete as $policyId ){
            $params[] = [ 'id' => (int) $policyId ];
        }
        
        return $ww->db->deleteQuery($query, $params, true);
    }
    
    static function updatePolicies( WoodWiccan $ww, int $profileId, array $data )
    {
        if( empty($profileId) || empty($data) ){
            return false;
        }
        
        $query = "";
        $query  .=  "UPDATE `user__policy` ";
        
        $query  .=  "SET `fk_profile` = :fk_profile ";
        $query  .=  ", `module` = :module ";
        $query  .=  ", `status` = :status ";
        $query  .=  ", `fk_witch` = :fk_witch ";
        $query  .=  ", `position_ancestors` = :position_ancestors ";
        $query  .=  ", `position_included`  = :position_included ";
        $query  .=  ", `position_descendants` = :position_descendants ";
        $query  .=  ", `custom_limitation` = :custom_limitation ";
        
        $query  .=  "WHERE `id` = :id ";
        
        $params = [];
        foreach( $data as $policyData )
        {
            if( !empty($policyData['witchRules']) )
            {
                $ancestors      = $policyData['witchRules']['ancestors']? 1: 0;
                $self           = $policyData['witchRules']['self']? 1: 0;
                $descendants    = $policyData['witchRules']['descendants']? 1: 0;
            }
            else 
            {
                $ancestors      = 0;
                $self           = 0;
                $descendants    = 0;
            }
            
            $params[] = [ 
                'id'                    => $policyData['id'],
                'fk_profile'            => $profileId,
                'module'                => $policyData['module'] ?? '*',
                'status'                => ( isset($policyData['status']) && $policyData['status'] != '*' )? $policyData['status']: null,
                'fk_witch'              => !empty($policyData['witch'])? $policyData['witch']: null,
                'position_ancestors'    => $ancestors,
                'position_included'     => $self,
                'position_descendants'  => $descendants,
                'custom_limitation'     => $policyData['custom'] ?? null,
            ];
        }
        
        return $ww->db->updateQuery($query, $params, true);
    }
    
    
    static function deleteProfile( WoodWiccan $ww, int $profileId ) 
    {
        if( empty($profileId) ){
            return false;
        }
        
        $query = "";
        $query  .=  "DELETE FROM `user__profile` ";
        $query  .=  "WHERE `id` = :id ";
        
        return $ww->db->deleteQuery($query, [ 'id' => $profileId ]);
    }
    
    
    static function insertConnexion( WoodWiccan $ww, array $data, array $craftAttributeData )
    {
        if( empty($data['login']) || empty($data['email']) ){
            return false;
        }
        
        if( empty($craftAttributeData['table']) 
            || empty($craftAttributeData['name'])
            || empty($craftAttributeData['type'])
            || empty($craftAttributeData['var']) ){
            return false;
        }
        
        $userId = $ww->user->id;
        $params = [];
        if( $userId )
        {
            $params["creator"]  = $userId;
            $params["modifier"] = $userId;
        }
        
        $params["name"]         = $data['name'] ?? $data['login'];
        $params["email"]        = $data['email'];
        $params["login"]        = $data['login'];
        $params["pass_hash"]    = $data['pass_hash'] ?? "";
        
        $params["craft_table"]          = $craftAttributeData['table'];
        $params["craft_attribute"]      = $craftAttributeData['type'];
        $params["craft_attribute_var"]  = $craftAttributeData['var'];
        $params["attribute_name"]       = $craftAttributeData['name'];
        
        $query = "";
        $query .=   "INSERT INTO `user__connexion` ";
        $query .=   "( `name`, `email`, `login`, `pass_hash`, ";
        
        if( $userId ){
            $query .=   "`creator`, `modifier`, ";
        }
        
        $query .=   "`craft_table`, `craft_attribute`, `craft_attribute_var`, `attribute_name` ) ";
        
        $query .=   "VALUES ( :name ";
        $query .=   ", :email ";
        $query .=   ", :login ";
        $query .=   ", :pass_hash ";
        
        if( $userId ){
            $query .=   ", :creator, :modifier ";
        }
        
        $query .=   ", :craft_table ";
        $query .=   ", :craft_attribute ";
        $query .=   ", :craft_attribute_var ";
        $query .=   ", :attribute_name ) ";
        
        $newConnexionId = $ww->db->insertQuery($query, $params);
        
        self::insertConnexionProfilesIds( $ww, $newConnexionId, $data['profiles'] ?? [] );
        
        return $newConnexionId;
    }
    
    
    static function updateConnexion( WoodWiccan $ww, int $connexionId, array $data, array $craftAttributeData )
    {
        if( empty($connexionId) || empty($data) ){
            return false;
        }
        
        if( empty($craftAttributeData['table']) 
            || empty($craftAttributeData['name'])
            || empty($craftAttributeData['type'])
            || empty($craftAttributeData['var']) ){
            return false;
        }
        
        $params = [];
        foreach( ["name", "login", "email", "pass_hash"] as $field ){
            if( isset($data[ $field ]) ){
                $params[ $field ] = $data[ $field ];
            }
        }
        
        if( empty($params) ){
            return false;
        }
        
        $userId = $ww->user->id;
        if( $userId ){
            $params["modifier"] = $userId;
        }
        
        $params["craft_table"]          = $craftAttributeData['table'];
        $params["craft_attribute"]      = $craftAttributeData['type'];
        $params["craft_attribute_var"]  = $craftAttributeData['var'];
        $params["attribute_name"]       = $craftAttributeData['name'];
        
        
        $query = "";            
        $query  .=  "UPDATE `user__connexion` ";
        $query  .=  "SET ";
        
        $separator = "";
        foreach( array_keys($params) as $field )
        {
            $query  .=  $separator."`".$field."` = :".$field." ";
            $separator = ", ";
        }

        $params['id'] = $connexionId;
        
        $query  .=  "WHERE `id` = :id ";
        
        $updateCounter = $ww->db->updateQuery($query, $params);
        
        $newProfilesIds = $data['profiles'] ?? [];
        $profilesIds    = self::selectConnexionProfilesIds($ww, $connexionId);
        
        $updateCounter += self::deleteConnexionProfilesIds( $ww, $connexionId, array_diff($profilesIds, $newProfilesIds) );
        $updateCounter += self::insertConnexionProfilesIds( $ww, $connexionId, array_diff($newProfilesIds, $profilesIds) );
        
        return $updateCounter;
    }
    
    static private function selectConnexionProfilesIds( WoodWiccan $ww, int $connexionId ): array
    {
        $query = "";
        $query  .=  "SELECT fk_profile ";
        $query  .=  "FROM user__rel__connexion__profile ";
        $query  .=  "WHERE fk_connexion = :fk_connexion ";
        
        $result = $ww->db->selectQuery($query, [ 'fk_connexion' => $connexionId ]);
        
        $return = [];
        foreach( $result as $row ){
            $return[] = $row['fk_profile'];
        }
        
        return $return;
    }
    
    static private function deleteConnexionProfilesIds( WoodWiccan $ww, int $connexionId, array $profilesIds )
    {
        if( empty($connexionId) ){
            return false;
        }
        
        $params = [];
        foreach( $profilesIds as $profileId ){
            if(is_numeric($profileId) ){
                $params[] = [ 'fk_connexion' => $connexionId, 'fk_profile' => $profileId ];
            }
        }
        
        if( empty($params) ){
            return 0;
        }
        
        $query = "";
        $query  .=  "DELETE FROM `user__rel__connexion__profile` ";
        $query  .=  "WHERE `fk_connexion` = :fk_connexion ";
        $query  .=  "AND `fk_profile` = :fk_profile ";
        
        return $ww->db->deleteQuery($query, $params, true);
    }
    
    static private function insertConnexionProfilesIds( WoodWiccan $ww, int $connexionId, array $profilesIds )
    {
        if( empty($connexionId) ){
            return false;
        }
        
        $params = [];
        foreach( $profilesIds as $profileId ){
            if(is_numeric($profileId) ){
                $params[] = [ 'fk_connexion' => $connexionId, 'fk_profile' => $profileId ];
            }
        }
        
        if( empty($params) ){
            return 0;
        }
        
        $query = "";
        $query  .=  "INSERT INTO `user__rel__connexion__profile` ";
        $query  .=  "( `fk_connexion`, `fk_profile`) ";
        $query  .=  "VALUES ( :fk_connexion, :fk_profile ) ";
        
        return count( $ww->db->insertQuery($query, $params, true) );
    }
    
    
    static function checkEmailLoginValidity( WoodWiccan $ww, string $login, string $email, ?int $contentKeyId=null )
    {
        if( empty($login) && empty($email) ){
            return false;
        }
        
        $params = [ 'login' => $login, 'email' => $email ];
        
        $query = "";
        $query  .=  "SELECT `id` ";
        $query  .=  ", `name` ";
        $query  .=  ", `login` ";
        $query  .=  ", `email` ";
        $query  .=  ", `craft_table` ";
        $query  .=  ", `craft_attribute` ";
        $query  .=  ", `craft_attribute_var` ";
        $query  .=  ", `attribute_name` ";
        $query  .=  "FROM `user__connexion` ";
        $query  .=  "WHERE `craft_table` LIKE 'content_%' ";
        $query  .=  "AND ( `login` = :login ";
        $query  .=      "OR `email` = :email ) ";
        
        $result = $ww->db->selectQuery($query, $params);
        
        $loginCounter = 0;
        $emailCounter = 0;
        foreach( $result as $row )
        {
            $column =   Attribute::getColumnName($row['craft_attribute'], $row['attribute_name'], $row['craft_attribute_var']);
            $params =   [ 'connexion_id' => $row['id'] ];
            
            $query = "";
            $query  .=  "SELECT count(`id`) ";
            $query  .=  "FROM `".$ww->db->escape_string($row['craft_table'])."` ";
            $query  .=  "WHERE `".$ww->db->escape_string($column)."` = :connexion_id ";
            
            if( $contentKeyId )
            {
                $query              .=  "AND `id` <> :self_id ";
                $params['self_id']  =   $contentKeyId;
            }
            
            $result = $ww->db->countQuery($query, $params);
            
            if( $result && $login == $row['login'] ){
                $loginCounter++; 
            }
            if( $result && $email == $row['email'] ){
                $emailCounter++; 
            }
        }
        
        return [
            'login' => $loginCounter, 
            'email' => $emailCounter, 
        ];
    }
    
}
