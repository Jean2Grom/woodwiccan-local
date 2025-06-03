<?php
namespace WW\Handler;

use WW\WoodWiccan;
use WW\DataAccess\UserDataAccess as DataAccess;

/**
 * Class handeling User information and security access policies
 * 
 * @author Jean2Grom
 */
class UserHandler 
{   
    static function login( WoodWiccan $ww, string $username, ?string $password=null )
    {
        $data   = false;
        $errors = [];

        $user = DataAccess::getUser( $ww, $username );
        $ww->dump( $user );

        $userLoginData  = DataAccess::getUserLoginData( $ww, $username );
        if( count($userLoginData) === 0 )
        {
            $error      = "Unknown username";
            $errors[]   = $error;
            $ww->debug( $error, "Login failed" );
        }
        elseif( count($userLoginData) > 1 ) 
        {
            $error      = "Problem whith this username: multiple match";
            $errors[]   = $error;
            $ww->debug( $error, "Login failed" );

            $errors[]   = "Please contact administrator";
        }
        else 
        {
            $data       = array_values( $userLoginData )[0];

            if( $data['pass_hash'] 
                && (!$password || !password_verify( $password, $data['pass_hash'] ))
            ){
                $data       = false;
                $error      = "Password mismatch";
                $errors[]   = $error;
                $ww->debug( $error, "Login failed" );
            }
        }
        
        return [
            'data'      => $data,
            'errors'    => $errors,
        ];
    }

    static function listPolicies( array $profiles ): array
    {
        $policies = [];
        foreach( $profiles as $profileItem ){
            foreach( $profileItem['policies'] as $policyId => $policyData ){
                if( empty($policies[ $policyId ]) ){
                    $policies[ $policyId ] = $policyData;
                }
            }
        }
        
        return $policies;
    }

    static function getPublicProfile( WoodWiccan $ww ): array
    {
        $publicProfile  = $ww->configuration->read('system', 'publicUserProfile') ?? 'public';
        
        return [
            'name'      => $ww->configuration->read('system', 'publicUser') ?? "Public",
            'profiles'  => [ $publicProfile ],
            'policies'  => DataAccess::getProfilePolicies( $ww, $publicProfile ),
        ];
    }
   
}
