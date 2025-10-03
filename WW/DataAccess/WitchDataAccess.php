<?php
namespace WW\DataAccess;

use WW\WoodWiccan;
use WW\Cairn;
use WW\Witch;
use WW\Handler\WitchHandler as Handler;

/**
 * Class to aggregate Witch related data access functions
 * all these functions are statics
 * 
 * @author Jean2Grom
 */
class WitchDataAccess
{
    const RELATIONSHIPS = [
        'sisters',
        'parents',
        'children',
    ];

    static function getDepth( WoodWiccan $ww, bool $useCache=true ): int
    {
        if( $useCache ){
            $depth = $ww->cache->read( 'system', 'depth' );
        }
        
        if( empty($depth) )
        {
            $query  =   "SHOW COLUMNS FROM `witch` WHERE `Field` LIKE 'level_%'";
            $result =   $ww->db->selectQuery($query);
            $depth  =   count($result);
            
            if( $useCache ){
                $ww->cache->create('system', 'depth', $depth);
            }
        }
        
        return (int) $depth;
    }
    
    static function fetch( WoodWiccan $ww, int $id )
    {
        if( empty($id) ){
            return false;
        }

        $query = "";
        $query  .=  "SELECT * FROM witch ";
        $query  .=  "WHERE id = :id ";

        $data = $ww->db->fetchQuery($query, [ 'id' => $id ]);
        
        return $data;
    }

    static function search( WoodWiccan $ww, array $params, bool $or=false )
    {
        if( !$params ){
            return false;
        }

        $query = "";
        $query  .=  "SELECT * FROM witch ";
        $query  .=  "WHERE ";

        $queryParts = [];
        foreach( array_keys($params) as $key ){
            $queryParts[] = $key." = :".$key." ";
        }

        $query  .=  implode( ($or? "OR ": "AND "), $queryParts );
        
        return $ww->db->selectQuery( $query, $params );
    }
    
    static function update( WoodWiccan $ww, array $params, array $conditions )
    {
        if( empty($params) || empty($conditions) ){
            return false;
        }
        
        $query = "";
        $query  .=  "UPDATE `witch` ";
        
        $separator = "SET ";
        foreach( array_keys($params) as $field )
        {
            $query      .=  $separator.'`'.$ww->db->escape_string($field)."` = :".$field." ";
            $separator  =  ", ";
        }
        
        $separator = "WHERE ";
        foreach( $conditions as $field => $condition )
        {
            $key = "condition_".$field;

            $query      .=  $separator.'`'.$ww->db->escape_string($field)."` = :".$key." ";
            $separator  =  "AND ";

            $params[ $key ] = $condition;
        }

        return $ww->db->updateQuery( $query, $params );
    }
    
    static function updates( WoodWiccan $ww, array $params, array $conditions )
    {
        if( empty($params) 
            || empty($conditions) 
            || array_keys($params) !== array_keys($conditions)
            || empty(array_values( $params )[0] ?? null) 
            || empty(array_values( $conditions )[0] ?? null)  ){
            return false;
        }
        
        $query = "";
        $query  .=  "UPDATE `witch` ";
        
        $separator = "SET ";
        foreach( array_keys( array_values($params)[0] ) as $field )
        {
            $query      .=  $separator.'`'.$ww->db->escape_string($field)."` = :".$field." ";
            $separator  =  ", ";
        }
        
        $separator = "WHERE ";
        foreach( array_keys( array_values($conditions)[0] ) as $field )
        {
            $key = "condition_".$field;

            $query      .=  $separator.'`'.$ww->db->escape_string($field)."` = :".$key." ";
            $separator  =  "AND ";

            foreach( array_keys($params) as $i ){
                $params[ $i ][ $key ] = $conditions[ $i ][ $field ];
            }
        }
        
        return $ww->db->updateQuery( $query, $params, true );
    }
    
    static function create( WoodWiccan $ww, array $params )
    {
        if( isset($params['id']) ){
            unset($params['id']);
        }
        if( isset($params['datetime']) ){
            unset($params['datetime']);
        }
        
        $query = "";
        $query  .=  "INSERT INTO `witch` ";
        
        $separator = "( ";
        foreach( array_keys($params) as $field )
        {
            $query  .=  $separator."`".$field."` ";
            $separator = ", ";
        }
        $query  .=  ") VALUES ";
        
        $separator = "( ";
        foreach( array_keys($params) as $field )
        {
            $query  .=  $separator.":".$field." ";
            $separator = ", ";
        }
        $query  .=  ") ";
        
        return $ww->db->insertQuery($query, $params);
    }
    
    static function increasePlateformDepth( WoodWiccan $ww ): int
    {
        $ww->cache->delete( 'system', 'depth' );
        $newLevelDepth = self::getDepth($ww, false) + 1;
        
        $query  =   "ALTER TABLE `witch` ";
        $query  .=  "ADD `level_".$newLevelDepth."` INT(11) UNSIGNED NULL DEFAULT NULL ";
        $query  .=  ", ADD KEY `IDX_level_".$newLevelDepth."` (`level_".$newLevelDepth."`) ";
        
        $ww->db->alterQuery($query);
        $ww->depth = $newLevelDepth;
        
        return $newLevelDepth;
    }
    
    static function getNewDaughterIndex( WoodWiccan $ww, array $position=[] )
    {
        $depth = count($position) + 1;
        
        $params = [];
        $query  = "SELECT MAX(`level_".$depth."`) AS `maxIndex` FROM `witch` ";
        
        $linkingCondition = "WHERE ";
        foreach($position as $level => $levelPosition )
        {
            $field              =   "level_".$level;
            $query              .=  $linkingCondition."`".$field."` = :".$field." ";
            $params[ $field ]   =   $levelPosition;
            $linkingCondition   =   "AND ";
        }
        
        $result = $ww->db->fetchQuery($query, $params);
        
        if( !$result ){
            return false;
        }
        
        $max = (int) $result["maxIndex"];
        
        return $max + 1;
    }
        
    static function getUrlsData(  WoodWiccan $ww, string $site, array $urls, ?int $excludedId=null )
    {
        $params = [ 
            'site'      => $site,
        ];
        
        foreach( $urls as $i => $url )
        {
            $params['url_'.$i]      = $url;
            $params['regexp_'.$i]   = '^'. $url.'-[0-9]+$';            
        }
        
        $query = "";
        $query  .=  "SELECT `url` ";
        $query  .=  "FROM `witch` ";
        $query  .=  "WHERE `site` = :site ";
        if( $excludedId )
        {
            $query  .=  "AND `id` <> :excludedId ";
            $params['excludedId'] = $excludedId;
        }
        $query  .=  "AND ( ";
        
        $separator = "";
        foreach( array_keys($urls) as $i )
        {
            $query  .=      $separator."`url` = :url_".$i." ";
            $query  .=      "OR `url` REGEXP :regexp_".$i." ";
            $separator =  "OR ";
        }
        $query  .=  ") ";
        
        return $ww->db->selectQuery($query, $params);
    }
    
    static function fetchAncestors( WoodWiccan $ww, int $witchId, bool $toRoot=true, mixed $sitesRestriction=null )
    {
        $depth = 1;
        if( $toRoot ){
            $depth = '*';
        }
        
        $website = clone $ww->website;
        if( $sitesRestriction ){
            $website->sitesRestrictions  = $sitesRestriction;
        }
        
        $witches        = self::summon(
            $ww, 
            Cairn::prepareConfiguration(
                $website, 
                [
                    'fetchAncestors' => [
                        'match' => $witchId,
                        'craft' => false,
                        'parents' => [
                            'depth' => $depth,
                            'craft' => false,
                        ]
                    ]
                ]
            ) 
        );
        
        if( empty($witches['fetchAncestors']) ){
            return false;
        }
        
        return $witches['fetchAncestors']->mother;
    }
    
    static function fetchDescendants(  WoodWiccan $ww, int $witchId, bool $completeSubtree=true, ?array $sitesRestriction=null ): array
    {
        $depth = 1;
        if( $completeSubtree ){
            $depth = '*';
        }

        $website = clone $ww->website;
        if( $sitesRestriction ){
            $website->sitesRestrictions  = $sitesRestriction;
        }
        
        $witches = self::summon(
            $ww, 
            Cairn::prepareConfiguration(
                $website, 
                [
                    'fetchDescendants' => [
                        'match' => $witchId,
                        'craft' => false,
                        'children' => [
                            'depth' => $depth,
                            'craft' => false,
                        ]
                    ]
                ]
            ) 
        );
        
        return $witches['fetchDescendants']->daughters ?? [];
    }
    
    static function delete( WoodWiccan $ww, array $witchesToDeleteIds ): bool
    {
        if( empty($witchesToDeleteIds) ){
            return true;
        }
        
        $params = [];
        foreach( $witchesToDeleteIds as $i => $id ){
            $params[ 'id'.$i ] = $id;
        }
        
        $query = "";
        $query  .=  "DELETE FROM `witch` ";
        $query  .=  "WHERE `id` IN ( ";
        $query  .=  ":".implode(", :", array_keys($params));
        $query  .=  " ) ";
        
        return $ww->db->deleteQuery($query, $params);
    }

    
    static function summon( WoodWiccan $ww, $configuration )
    {
        $result         = [];
        $invokedModule  = false;
        foreach( $configuration as $index => $conf )
        {
            if( $conf["match"]["cauldron"] ?? "" === "user" 
                && $ww->user->connexion
            ){
                $conf["match"]["cauldron"]                      = (int) $ww->user->cauldron;
                $configuration[ $index ]["match"]["cauldron"]   = $conf["match"]["cauldron"];
            }

            if( !empty($conf['conditions']) ){
                if( !empty($conf['conditions']['invoke']) 
                    && !in_array($invokedModule, $conf['conditions']['invoke']) 
                ){
                    continue;
                }
            }

            $confResult = self::witchesRequest($ww, $conf);
            
            if( !$invokedModule 
                && in_array( Cairn::DEFAULT_WITCH, array_keys($conf['entries']) ) 
            ){
                foreach( $confResult as $row )
                {
                    $match = true;
                    foreach( $conf["match"] as $field => $value ){
                        if( $row[ $field ] !== $value )
                        {
                            $match = false;
                            break;
                        }
                    }

                    if( $match )
                    {
                        $invokedModule = $row['invoke'];
                        break;
                    }
                }
            }
            
            $result = array_merge(
                $result, 
                $confResult 
            );
        }

        return self::witchesInstanciate($ww, $configuration, $result);
    }


    private static function witchesRequest( WoodWiccan $ww, $configuration )
    {
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
        
        $query  .= "FROM `witch` AS `w` ";

        $leftJoin = false;
        foreach( self::RELATIONSHIPS as $relationship ){
            if( !empty($configuration[ $relationship ]) )
            {
                $leftJoin = true;
                break;
            }
        }

        if( $leftJoin )
        {
            $query  .= "LEFT JOIN `witch` AS `ref_witch` ";
            //$query  .=  "ON ( ";
            $query  .=  "ON  `w`.`id` <> `ref_witch`.`id` AND ";

            $separator = "";
            foreach( self::RELATIONSHIPS as $relationship )
            {
                if( empty($configuration[ $relationship ]) ){
                    continue;
                }

                $query .= $separator;
                $separator = "OR ";

                $functionName   = $relationship."Jointure";
                $params         = ['ref_witch', 'w'];

                if( !empty($configuration[ $relationship ]['depth']) ){
                    $params[] = $configuration[ $relationship ]['depth'];
                }

                $query .= call_user_func_array([ __CLASS__, $functionName ], array_merge([$ww], $params) );
            }

        }
        
        $parameters = [];
        $condition  = false;
        foreach( $configuration['match'] as $field => $value )
        {
            $parameters[ $field ]   = $value;

            if( !$condition ){
                $condition .= "( ";
            }
            else {
                $condition .= "AND ";
            }

            $condition  .=  "%s.`".$field."` = :".$field." ";
        }
        $condition .= ") ";


        $separator = "WHERE ( ";


        foreach( ['ref_witch', 'w'] as $replacement )
        {
            $query      .=  $separator;
            $separator  =   "OR ";
            
            $query      .=  str_replace(' %s.', ' `'.$replacement.'`.', $condition);
        }
        $query .=  ") ";
        
        
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
        
        //$jointure = "( `".$mother."`.`id` <> `".$daughter."`.`id` ) ";
        
        //$jointure  .=      "AND ( ";
        $jointure  =        "( ";

        $jointure  .=           "( ".$m(1)." IS NOT NULL AND ".$d(1)." = ".$m(1)." ) ";
        $jointure  .=           "OR ( ".$m(1)." IS NULL AND ".$d(1)." IS NOT NULL ) ";
        $jointure  .=       ") ";
        
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
        
        //$jointure = "( `".$witch."`.`id` <> `".$sister."`.`id` ) ";
        $jointure   = "";
        $separator  = "";

        for( $i=1; $i < $ww->depth; $i++ )
        {
            $jointure  .=  $separator;
            $separator  = "AND ";

            //$jointure  .=  "AND ( ";
            $jointure  .=  "( ";
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
    
    private static function witchesInstanciate( WoodWiccan $ww, $configuration, $result )
    {
        if( !$result ){
            return [];
        }
        
        $witches        = [];
        $witchesList    = [];
        
        $depthArray = [];
        foreach( range(0, $ww->depth) as $d ){
            $depthArray[ $d ] = [];
        }
        
        foreach( $result as $row )
        {
            $id                             = $row['id'];
            if( isset($witchesList[ $id ]) ){
                continue;
            }

            $witch                          =  Handler::instanciate( $ww, $row );
            $depthArray[ $witch->depth ][]  = $id;
            $witchesList[ $id ]             = $witch;
            
            foreach( $configuration as $conf )
            {
                $match = true;
                foreach( $conf['match'] as $field => $value ){
                    if( $row[ $field ] !== $value )
                    {
                        $match = false;
                        break;
                    }
                }

                if( $match ){
                    foreach( array_keys($conf['entries']) as $entry ){
                        $witches[ $entry ] = $witch;
                    }
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
        
        foreach( $configuration as $witchRefConf )
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
        
        
        foreach( $configuration as $witchRefConf )
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
        
        if( empty($witches[ Cairn::DEFAULT_WITCH ]) ){
            $witches[ Cairn::DEFAULT_WITCH ] = Handler::instanciate( $ww, [ 'name' => "ABSTRACT 404 WITCH", 'invoke' => '404' ] ); 
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
}
