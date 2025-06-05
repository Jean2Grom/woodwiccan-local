<?php
namespace WW\DataAccess;

use WW\Handler\CauldronHandler;
use WW\WoodWiccan;
use WW\Witch;
use WW\Handler\WitchHandler as Handler;
use WW\Module;

/**
 * Class to aggregate Witch's summoning related data access functions
 * all these functions are statics
 * 
 * @author Jean2Grom
 */
class Summoning 
{
    const RELATIONSHIPS = [
        'sisters',
        'parents',
        'children',
    ];
    
    
    private static function witchesInstanciate( WoodWiccan $ww, $configuration, $result )
    {
        $witches        = [];
        $witchesList    = [];
        
        $depthArray = [];
        foreach( range(0, $ww->depth) as $d ){
            $depthArray[ $d ] = [];
        }
        
        $conditions     = [];
        $urlRefWiches   = [];
        foreach( $configuration["url"] as $typeConfiguration ){
            foreach( array_keys($typeConfiguration['entries']) as $witchRef )
            {
                $conditions[ $witchRef ] = [ 
                    'site'  => $typeConfiguration['site'],
                    'url'   => $typeConfiguration['url'],
                ];
                
                $urlRefWiches[] = $witchRef;
            }
        }
        foreach( $configuration["id"] as $typeConfiguration ){
            foreach( array_keys($typeConfiguration['entries']) as $witchRef ){
                $conditions[ $witchRef ] = [ 'id'  => $typeConfiguration['id'] ];
            }
        }
        
        // if( !empty($configuration['user']) && !empty($result[0]['user_cauldron']) ){
        //     foreach( array_keys($configuration['user']['entries']) as $witchRef ){
        //         $conditions[ $witchRef ] = [ 'cauldron' => $result[0]['user_cauldron'] ];
        //     }
        // }
        if( !empty($configuration['user']) ){
            foreach( array_keys($configuration['user']['entries']) as $witchRef ){
                $conditions[ $witchRef ] = [ 'cauldron' => $ww->user->cauldron ];
            }
        }
        
        foreach( $result as $row )
        {
            $id                             = $row['id'];
            $witch                          = Handler::instanciate( $ww, $row );
            $depthArray[ $witch->depth ][]  = $id;
            $witchesList[ $id ]             = $witch;
            
            foreach( $conditions as $witchRef => $conditionsItem )
            {
                $matched = true;
                foreach( $conditionsItem as $field => $value ){
                    
                    if( $row[ $field ] !== $value )
                    {
                        $matched = false;
                        break;
                    }
                }
                
                if( $matched ){
                    $witches[ $witchRef ] = $witch;
                }
            }
        }
        
        for( $i=0; $i < $ww->depth; $i++ ){
            foreach( $depthArray[ $i ] as $potentialMotherId )
            {
                $daughters = [];
                foreach( $depthArray[ ($i+1) ] as $potentialDaughterId ){
                    if( $witchesList[ $potentialMotherId ]->isMotherOf( $witchesList[ $potentialDaughterId ] ) ){
                        $daughters[] = $witchesList[ $potentialDaughterId ];
                    }
                }
                
                Handler::addDaughters( $witchesList[ $potentialMotherId ], $daughters );
            }
        }
        
        foreach( $configuration as $type => $typeConfiguration )
        {
            if( $type === 'user' ){
                $witchRefConfJoins = [ 'user' => $typeConfiguration ];
            }
            else {
                $witchRefConfJoins = $typeConfiguration;
            }
            
            foreach( $witchRefConfJoins as $witchRefConf )
            {
                if( empty($witchRefConf['entries']) ){
                    continue;                    
                }
                
                $witchRef = array_keys($witchRefConf['entries'])[0];
                
                if( !isset($witches[ $witchRef ]) ){
                    continue;
                }
                
                if( !empty($witchRefConf['children']) && !empty($witchRefConf['children']['depth']) )
                {
                    $depthLimit = $ww->depth - $witches[ $witchRef ]->depth;
                    if( $witchRefConf['children']['depth'] !== '*' 
                            && (int) $witchRefConf['children']['depth'] < $depthLimit 
                    ){
                        $depthLimit = (int) $witchRefConf['children']['depth'];
                    }
                    
                    self::initChildren( $witches[ $witchRef ], $depthLimit );
                }
            }
        }
        
        
        foreach( $configuration as $type => $typeConfiguration )
        {
            if( $type === 'user' ){
                $witchRefConfJoins = [ 'user' => $typeConfiguration ];
            }
            else {
                $witchRefConfJoins = $typeConfiguration;
            }

            foreach( $witchRefConfJoins as $witchRefConf )
            {
                if( empty($witchRefConf['entries']) ){
                    continue;                
                }
                
                $witchRef = array_keys($witchRefConf['entries'])[0];
                
                if( !isset($witches[ $witchRef ]) ){
                    continue;
                }

                if( !empty($witchRefConf['sisters']) && !empty($witchRefConf['sisters']['depth']) )
                {
                    $depthLimit = $ww->depth - $witches[ $witchRef ]->depth;
                    if( $witchRefConf['sisters']['depth'] !== '*' 
                            && (int) $witchRefConf['sisters']['depth'] < $depthLimit 
                    ){
                        $depthLimit = (int) $witchRefConf['sisters']['depth'];
                    }

                    if( is_null($witches[ $witchRef ]->sisters) ){
                        $witches[ $witchRef ]->sisters = [];
                    }

                    if( !empty($witches[ $witchRef ]->mother) && !empty($witches[ $witchRef ]->mother->daughters) ){
                        foreach( $witches[ $witchRef ]->mother->daughters as $daughterWitch )
                        {
                            if( $witches[ $witchRef ]->id !== $daughterWitch->id ){
                                Handler::addSister( $witches[ $witchRef ], $daughterWitch );
                            }
                            self::initChildren( $daughterWitch, $depthLimit );
                        }
                    }
                }
            }
        }
        
        foreach( $urlRefWiches as $urlRefWichItem ){
            if( empty($witches[ $urlRefWichItem ]) ){
                $witches[ $urlRefWichItem ] = Handler::instanciate( $ww, [ 'name' => "ABSTRACT 404 WITCH", 'invoke' => '404' ] );
            }
        }
        
        return $witches;
    }
    
    
    private static function initChildren( Witch $witch, int $depthLimit )
    {
        if( $depthLimit < 0 ){
            return;
        }
        
        if( is_null($witch->daughters) ){
            $witch->daughters = [];
        }
        else {
            foreach( $witch->daughters as $daughterWitch ){
                self::initChildren( $daughterWitch, $depthLimit-1 );
            }
        }
        
        return;
    }
    
    
    private static function witchesRequest( WoodWiccan $ww, $configuration )
    {
        $userConnexionJointure = !empty($configuration['user']) && $ww->user->connexion;
        
        // Determine the list of fields in select part of query
        $query = "";
        $separator = "SELECT DISTINCT ";
        foreach( Witch::FIELDS as $field )
        {
            $query      .=  $separator."`w`.`".$field."` ";
            $separator  =   ", ";
        }
        for( $i=1; $i<=$ww->depth; $i++ ){
            $query      .=  $separator."`w`.`level_".$i."` ";
        }
        if( $userConnexionJointure ){
            //$query  .= ",  `connexion`.`cauldron_fk` AS `user_cauldron` ";
            // $query  .= ",  `user`.`cauldron` AS `user_cauldron` ";
        }
        
        $query  .= "FROM ";
        if( $userConnexionJointure ){
            //$query  .= "`ingredient__integer` AS `connexion`, ";
        }
        
        $refWitch           = false;
        foreach( $configuration as $type => $typeConfiguration ) 
        {
            if( $type === 'user' ){
                $witchRefConfJoins = [ 'user' => $typeConfiguration ];
            }
            else {
                $witchRefConfJoins = $typeConfiguration;
            }
            
            foreach( $witchRefConfJoins as $witchRef => $witchRefConf )
            {
                if( !empty($witchRefConf['modules']) ){
                    $query  .= "`witch` AS `ref_witch`, ";
                    $refWitch = true;
                    break;
                }

            }
        }
        
        $query  .= "`witch` AS `w` ";
        
        $leftJoin = [];
        foreach( $configuration as $type => $typeConfiguration ) 
        {
            if( $type === 'user' ){
                $witchRefConfJoins = [ 'user' => $typeConfiguration ];
            }
            else {
                $witchRefConfJoins = $typeConfiguration;
            }
            
            foreach( $witchRefConfJoins as $witchRef => $witchRefConf ) 
            {
                $leftJoin[ $witchRef ] = false;
                foreach( self::RELATIONSHIPS as $relationship ){
                    if( !empty($witchRefConf[ $relationship ]) )
                    {
                        $leftJoin[ $witchRef ] = true;
                        break;
                    }
                }

                if( !$leftJoin[ $witchRef ] ){
                    continue;
                }

                $query  .= "LEFT JOIN `witch` AS `".$witchRef."` ";
                $query  .=  "ON ( ";

                $separator = "";
                foreach( self::RELATIONSHIPS as $relationship )
                {
                    if( empty($witchRefConf[ $relationship ]) ){
                        continue;
                    }

                    $query .= $separator;
                    $separator = "OR ";

                    $functionName   = $relationship."Jointure";
                    $params         = [$witchRef, 'w'];

                    if( !empty($witchRefConf[ $relationship ]['depth']) ){
                        $params[] = $witchRefConf[ $relationship ]['depth'];
                    }

                    $query .= call_user_func_array([ __CLASS__, $functionName ], array_merge([$ww], $params) );
                }

                $query  .=  ") ";            
            }
        }
        
        $parameters = [];
        $separator = "WHERE ( ";
        
        foreach( $configuration['url'] as $witchRef => $witchRefConf )
        {
            $parameters[$witchRef.'_site']  = $witchRefConf['site'];
            $parameters[$witchRef.'_url']   = $witchRefConf['url'];

            $condition  =   "( %s.`site` = :".$witchRef."_site ";
            $condition  .=  "AND %s.`url` = :".$witchRef."_url ) ";

            $query      .=  $separator;
            $separator  =   "OR ";
            $query      .=  str_replace(' %s.', ' `w`.', $condition);
            if( $leftJoin[ $witchRef ] ){
                $query      .=  " OR ".str_replace(' %s.', " `".$witchRef."`.", $condition);
            }
        }
        
        foreach( $configuration['id'] as $witchRef => $witchRefConf )
        {
            $parameters[ $witchRef ]    = (int) $witchRefConf['id'];

            $condition  =   " %s.`id` = :".$witchRef." ";
              
            if( !empty($witchRefConf['modules']) && $refWitch )
            {
                $innerCondition = [];
                foreach( $witchRefConf['modules'] as $module )
                {
                    $parameterKey                   = $witchRef.'_'.$module;
                    $parameters[ $parameterKey ]    = $module;
                    $innerCondition[]               = "`ref_witch`.`invoke` = :".$parameterKey." ";
                }

                $condition  =   "( ".$condition." AND ( ". join("OR ", $innerCondition)." ) ) ";
            }
            
            $query      .=  $separator;
            $separator  =   "OR ";
            $query      .=  str_replace(' %s.', ' `w`.', $condition);
            if( $leftJoin[ $witchRef ] ){
                $query      .=  " OR ".str_replace(' %s.', " `".$witchRef."`.", $condition);
            }
        }
        
        if( $userConnexionJointure )
        {
            $query      .=  $separator;
            $separator  =   "OR ";
            $parameters[ 'user_cauldron' ] = (int) $ww->user->cauldron;
            $query  .= "`w`.`cauldron` = :user_cauldron OR `user`.`cauldron` = :user_cauldron ";

            // $query  .=  "`w`.`cauldron` = `connexion`.`cauldron_fk` ";
            // if( $leftJoin['user'] ){
            //     $query      .=  " OR `user`.`cauldron` = `connexion`.`cauldron_fk` ";
            // }
        }
        
        $query .=  ") ";
        
        if( $refWitch )
        {
            if( empty($parameters['site']) || empty($parameters['url']) ){
                $parameters = array_replace($parameters, $ww->website->getUrlSearchParameters());
            }
            
            $query .=  "AND ( `ref_witch`.`site` = :site ";
            $query .=  "AND `ref_witch`.`url` = :url ) ";
        }
        
        if( $ww->website->sitesRestrictions )
        {
            $sitesRestrictionsParams = [];
            foreach( $ww->website->sitesRestrictions as $sitesRestrictionsKey => $sitesRestrictionsValue )
            {
                $parameterKey                   = 'site_restriction_'.$sitesRestrictionsKey;
                $sitesRestrictionsParams[]      = $parameterKey;
                $parameters[ $parameterKey ]    = $sitesRestrictionsValue;
            }
            
            $query .=  "AND ( `w`.`site` IN ( :".implode(", :", $sitesRestrictionsParams)." ) OR `w`.`site` IS NULL ) ";
        }
        
        if( $userConnexionJointure )
        {
            // $parameters[ 'user_id' ] = (int) $ww->user->id;
            // $query  .= "AND ( `connexion`.`value` = :user_id ";
            // $query  .= "AND `connexion`.`name` = \"user__connexion\" ) ";
        }
        
        $userPoliciesConditions = [];
        foreach( $ww->user->policies as $policyId => $policy )
        {
            $condition = [];
            $policyKeyPrefix = ":policy_".((int) $policyId);
            
            if( isset($policy['status']) && $policy['status'] != "*" )
            {
                $condition[] = "`w`.`status` <= ".$policyKeyPrefix."_status ";
                $parameters[ $policyKeyPrefix.'_status' ] = (int) $policy['status'];
            }
            
            if( !empty($condition) && !empty($policy['position']) )
            {
                if( $policy['position_rules']['ancestors'] xor $policy['position_rules']['descendants'] )
                {
                    $lastLevel = count($policy['position']);
                    if( $policy['position_rules']['self'] ){
                        $lastLevel++;
                    }
                }

                foreach( $policy['position'] as $level => $levelValue ){
                    if( $level <= $lastLevel )
                    {
                        $field                                      = "level_".((int) $level);
                        $condition[]                                = "`w`.`".$field."` = ".$policyKeyPrefix."_".$field." ";
                        $parameters[ $policyKeyPrefix."_".$field ]  = $levelValue;
                    }
                }
                
                if( $policy['position_rules']['ancestors'] ){
                    $condition[] = "`w`.`level_".$lastLevel."` IS NULL ";
                }
                elseif( $policy['position_rules']['descendants'] && !$policy['position_rules']['self']){
                    $condition[] = "`w`.`level_".$lastLevel."` IS NOT NULL ";
                }                
            }
            
            if( !empty($condition) ){
                $userPoliciesConditions[] = $condition;
            }
        }
        
        if( !empty($userPoliciesConditions) )
        {
            $query .= "AND ( ";
            foreach( $userPoliciesConditions as $i => $condition )
            {
                if( count($condition) == 1 ){
                    $query .= array_values($condition)[0];
                }
                else {
                    $query .= "( ".implode("AND ", $condition).") ";
                }
                
                if( ($i + 1) < count($userPoliciesConditions) ){
                    $query .= "OR "; 
                }
            }
            $query .= ") ";
        }
// $ww->db->debugQuery($query, $parameters);
        return $ww->db->selectQuery($query, $parameters);
    }

    private static function childrenJointure( WoodWiccan $ww, $mother, $daughter, $depth=1 )
    {
        $m = function (int $level) use ($mother): string {
            return "`".$mother."`.`level_".$level."`";
        };
        $d = function (int $level) use  ($daughter): string {
            return "`".$daughter."`.`level_".$level."`";
        };
        
        $jointure = "( `".$mother."`.`id` <> `".$daughter."`.`id` ) ";
        
        $jointure  .=      "AND ( ";
        $jointure  .=          "( ".$m(1)." IS NOT NULL AND ".$d(1)." = ".$m(1)." ) ";
        $jointure  .=          "OR ( ".$m(1)." IS NULL AND ".$d(1)." IS NOT NULL ) ";
        $jointure  .=      ") ";
        
        for( $i=2; $i <= $ww->depth; $i++ )
        {
            $jointure  .=  "AND ( ";
            $jointure  .=      "( ".$m($i)." IS NOT NULL AND ".$d($i)." = ".$m($i)." ) ";
            $jointure  .=      "OR ( ".$m($i)." IS NULL AND ".$m($i-1)." IS NOT NULL AND ".$d($i)." IS NOT NULL ) ";
            $jointure  .=      "OR (  ".$m($i)." IS NULL AND ".$m($i-1)." IS NULL ";
            // Apply level
            if( $depth != '*' && ($depth + $i - 1) <= $ww->depth ){
                $jointure  .=       "AND ".$d($depth + $i - 1)." IS NULL ";
            }
            $jointure  .=      ") ";
            $jointure  .=  ") ";
        }
        
        return $jointure;
    }
    
    private static function parentsJointure( WoodWiccan $ww, $daughter, $mother, $depth=1 )
    {
        return self::childrenJointure( $ww, $mother, $daughter, $depth );
    }
    
    private static function sistersJointure( WoodWiccan $ww, $witch, $sister, $depth=1 )
    {
        $w = function (int $level) use ($witch): string {
            return "`".$witch."`.`level_".$level."`";
        };
        $s = function (int $level) use  ($sister): string {
            return "`".$sister."`.`level_".$level."`";
        };
        
        $jointure = "( `".$witch."`.`id` <> `".$sister."`.`id` ) ";
        
        for( $i=1; $i < $ww->depth; $i++ )
        {
            $jointure  .=  "AND ( ";
            $jointure  .=      "( ".$w($i)." IS NOT NULL AND ".$w($i+1)." IS NOT NULL AND ".$s($i)." = ".$w($i)." ) ";
            $jointure  .=      "OR ( ".$w($i)." IS NOT NULL AND ".$w($i+1)." IS NULL AND ".$s($i)." IS NOT NULL ) ";
            
            if( $i == 1 ){
                $jointure  .=      "OR ( ".$w($i)." IS NULL AND ".$s($i)." IS NULL ) ";
            }
            elseif( $depth != '*' && ($i + 1 - $depth) > 0 )
            {
                $jointure  .=      "OR ( ".$w($i)." IS NULL AND ".$w($i + 1 - $depth)." IS NULL AND ".$s($i)." IS NULL ) ";
                $jointure  .=      "OR ( ".$w($i)." IS NULL AND ".$w($i + 1 - $depth)." IS NOT NULL ) ";
                
            }
            else {
                $jointure  .=      "OR ( ".$w($i)." IS NULL ) ";
            }
            
            $jointure  .=  ") ";
        }
        
        $maxDepth = (int) $ww->depth;
        $jointure  .=      "AND ( ";
        $jointure  .=          "( ".$w($maxDepth)." IS NOT NULL AND ".$s($maxDepth)." IS NOT NULL ) ";
        if( $depth != '*' && ($maxDepth + 1 - $depth) > 0 )
        {
            $jointure  .=          "OR ( ".$w($maxDepth)." IS NULL AND ".$w($maxDepth + 1 - $depth)." IS NULL AND ".$s($maxDepth)." IS NULL ) ";
            $jointure  .=          "OR ( ".$w($maxDepth)." IS NULL AND ".$w($maxDepth + 1 - $depth)." IS NOT NULL ) ";
        }
        else {
            $jointure  .=      "OR ( ".$w($maxDepth)." IS NULL ) ";
        }
        $jointure  .=      ") ";
        
        return $jointure;
    }
    
    
    static function witches( WoodWiccan $ww, $configuration )
    {
        if( empty($configuration['id']) 
                && empty($configuration['url'])
                && empty($configuration['user']) 
        ){
            return [];
        }
        
        $result     = self::witchesRequest($ww, $configuration);

        if( $result === false ){
            return false;
        }
        
        return self::witchesInstanciate($ww, $configuration, $result);
    }
    

    static function cauldrons( WoodWiccan $ww, $configuration )
    {
        $cauldronsConf = [];
        foreach( $configuration as $type => $typeConfiguration )
        {
            if( $type === 'user' ){
                $witchRefConfJoins = [ 'user' => $typeConfiguration ];
            }
            else {
                $witchRefConfJoins = $typeConfiguration;
            }

            foreach( $witchRefConfJoins as $witchConf )
            {
                $refWitch = array_keys($witchConf['entries'])[0];
                
                if( !$ww->witch($refWitch) ){
                    continue;
                }
                
                $permission = false;
                foreach( $witchConf['entries'] as $invoke )
                {
                    if( $invoke === false ){
                        $permission = true;
                    }
                    elseif( $invoke == true && $ww->witch($refWitch)->hasInvoke() )
                    {
                        $module     = new Module( $ww->witch($refWitch), $ww->witch($refWitch)->invoke );
                        $permission = $ww->witch( $refWitch )->isAllowed( $module );
                    }
                    else 
                    {
                        $module     = new Module( $ww->witch( $refWitch ), $invoke );
                        $permission = $ww->witch( $refWitch )->isAllowed( $module );
                    }
                    
                    if( $permission ){
                        break;
                    }
                }
                
                if( !$permission ){
                    continue;
                }
                
                if( (!isset( $witchConf['craft'] ) || !empty( $witchConf['craft'] )) 
                    && $ww->witch( $refWitch )->hasCauldron()
                ){
                    $cauldronsConf[] = $ww->witch( $refWitch )->cauldronId;
                }
                
                if( !empty($witchConf['parents']['craft']) ){
                    $cauldronsConf = array_merge_recursive( 
                        $cauldronsConf, 
                        self::getParentsCraftData( $ww->witch($refWitch), $witchConf['parents']['craft'] )
                    );
                }
 
                if( !empty($witchConf['sisters']['craft']) && !empty($witches[ $refWitch ]->sisters) ){
                    foreach( $ww->witch($refWitch)->sisters as $sisterWitch ){
                        $cauldronsConf = array_merge_recursive( 
                            $cauldronsConf, 
                            self::getChildrenCraftData( $sisterWitch, $witchConf['sisters']['craft'] )
                        );
                    }
                }

                if( !empty($witchConf['children']['craft']) ){
                    $cauldronsConf = array_merge_recursive( 
                        $cauldronsConf, 
                        self::getChildrenCraftData( $ww->witch($refWitch), $witchConf['children']['craft'] )
                    );
                }
            }
        }
        
        return $cauldronsConf? CauldronHandler::fetch($ww, array_unique($cauldronsConf), false): [];
    }

    // RECURSIVE READ CRAFT DATA FUNCTIONS
    private static function getChildrenCraftData( Witch $witch, mixed $craftLevel )
    {
        $cauldronsConf = [];
        if( !empty($witch->daughters) ){
            foreach( $witch->daughters as $daughterWitch )
            {
                if( $daughterWitch->hasCauldron() ){
                    $cauldronsConf[] = $daughterWitch->cauldronId;
                }

                if( $craftLevel == "*" ){
                    $craftSubLevel = $craftLevel;
                }
                else 
                {
                    $craftSubLevel = $craftLevel - 1;
                    if( $craftSubLevel == 0 ){
                        continue;
                    }
                }
                
                $cauldronsConf = array_merge_recursive(
                    $cauldronsConf, 
                    self::getChildrenCraftData($daughterWitch, $craftSubLevel) 
                );
            }
        }
        
        return $cauldronsConf;
    }

    private static function getParentsCraftData( Witch $witch, mixed $craftLevel )
    {
        $cauldronsConf = [];
        if( !empty($witch->mother) )
        {
            $motherWitch    = $witch->mother;
            
            if( $motherWitch->hasCauldron() ){
                $cauldronsConf[] = $motherWitch->cauldronId;
            }

            if( $craftLevel == "*" ){
                $craftSubLevel = $craftLevel;
            }
            else {
                $craftSubLevel = $craftLevel - 1;
            }

            if( $craftSubLevel == "*" || $craftSubLevel > 0 ){
                $cauldronsConf = array_merge_recursive(
                    $cauldronsConf, 
                    self::getParentsCraftData($motherWitch, $craftSubLevel) 
                );
            }
        }
        
        return $cauldronsConf;
    }
}
