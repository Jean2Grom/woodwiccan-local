<?php
namespace WW;

use WW\Handler\WitchHandler as Handler;
use WW\DataAccess\WitchDataAccess as DataAccess;

use WW\Handler\CauldronHandler;
use WW\Trait\PropertiesAccessTrait;

/**
 * A witch is an element of global arborescence, that we call matriarcat. 
 * Each witch except root (ID 1) has a mother witch, and can have daughters witch
 * The structure tree of WW is composed of witches, witch represents the elements of it
 * Each Witch can be associated with a cauldron, a module and an URL to execute, 
 * a visibility status ... 
 * This class is very essential in the WW management
 *
 * @author jean2Grom
 */
class Witch 
{
    use PropertiesAccessTrait;

    const FIELDS = [
        "id",
        "name",
        "data",
        "site",
        "url",
        "status",
        "invoke",
        "cauldron",
        "cauldron_priority",
        "context",
        "datetime",
        "priority",
    ];
    public $properties     = [];
    
    public $id;
    public $name;
    public $datetime;
    
    public int $statusLevel = 0;
    public ?string $status  = null;
    
    public Website|string|null $site = null;
    
    public $depth           = 0;
    public $position        = [];
    public $modules         = [];
    
    /** @var ?self */
    public $mother;
    /** @var ?self[] */
    public $sisters;
    /** @var ?self[] */
    public $daughters = null;

    public $cauldronId;
    public $cauldronPriority;
    
    /**
     * @var ?Cauldron
     */
    public ?Cauldron $cauldron = null;
    
    /** 
     * WoodWiccan container class to allow whole access to Kernel
     * @var WoodWiccan
     */
    public WoodWiccan $ww;
    
    
    /**
     * Name reading
     * @return string
     */
    public function __toString(): string {
        return ($this->id)? $this->name: "";
    }
    

    public function __get(string $name): mixed 
    {
        if( isset($this->properties[ $name ]) ){
            return $this->properties[ $name ];
        }

        if( $this->cauldron() ){
            if( $this->cauldron()->name === $name ){
                return $this->cauldron();
            }
            elseif( $this->cauldron()->content($name) ){
                if( is_a($this->cauldron()->content( $name ), Cauldron::class) ){
                    return $this->cauldron()->content( $name );
                }
                else {
                    return $this->cauldron()->content( $name )->value();
                }
            }
        }
        
        return null;
    }

    /**
     * Is this witch exist in database ?
     * @return bool
     */
    function exist(): bool {
        return !empty($this->id);
    }
    
    
    /**
     * Resolve status label with Website generation
     * @return ?string status label
     */
    function status(): ?string
    {
        if( is_null($this->status) )
        {
            $statusList     = $this->site()?->status ?? $this->ww->configuration->read('global', "status");
            $this->status   = $statusList[ $this->statusLevel ] ?? null;
        }
        
        return $this->status;
    }
    
    
    /**
     * Determine if the witch is associated with a cauldron (ie a content)
     * @return bool
     */
    function hasCauldron(): bool {
        return !empty($this->properties[ 'cauldron' ]);
    }
    
    /**
     * Determine if the witch is associated with a invocation (ie a module)
     * @return bool
     */
    function hasInvoke(): bool {
        return !empty($this->properties[ 'invoke' ]);
    }
    
        
    /**
     * Mother witch test
     * @param self $potentialDaughter
     * @return bool
     */
    function isMotherOf( self $potentialDaughter ): bool
    {
        if( $potentialDaughter->depth != $this->depth + 1 ){
            return false;
        }
        
        $isDaughter = true;        
        for( $i=1; $i<=$this->depth; $i++ ){
            if( $this->{'level_'.$i} != $potentialDaughter->{'level_'.$i} )
            {
                $isDaughter = false;
                break;
            }
        }
        
        return $isDaughter;
    }
    
    /**
     * Read mother witch (get it if needed), 
     * return mother witch or false if witch is root
     * @return self|false
     */
    function mother(): self|false
    {
        if( is_null($this->id) ){
            return false;
        }
        
        if( is_null($this->mother) )
        {
            $motherPosition = $this->position;
            unset( $motherPosition[array_key_last( $motherPosition )] );
            
            $mother = $this->ww->cairn->searchFromPosition($motherPosition);
            if( $mother ){
                Handler::setMother( $this, $mother );
            }
        }
        
        if( is_null($this->mother) ){
            Handler::setMother( $this, DataAccess::fetchAncestors($this->ww, $this->id, true) );
        }
        
        return $this->mother;
    }
    
        
    /**
     * Sister witches manipulation
     * @return array
     */
    function listSistersIds(): array
    {
        $list = [];
        if( !empty($this->sisters) ){
            $list = array_keys($this->sisters);
        }
        
        return $list;
    }
    
    /**
     * Read Sister witches (get them if needed), 
     * return mother witch or false if witch is root
     * @return mixed
     */
    function sisters( ?int $id=null ): mixed
    {
        if( is_null($this->id) ){
            return false;
        }
        
        if( is_null($this->sisters) && $this->mother() ){
            foreach( DataAccess::fetchDescendants($this->ww, $this->mother()->id, true) as $sisterWitch ){
                Handler::addSister( $this, $sisterWitch );
            }
        }
        elseif( is_null($this->sisters) ){
            $this->sisters = false;
        }
        
        if( !$id ){
            return $this->sisters;
        }
        
        return  $this->sisters[ $id ] 
                    ?? Handler::instanciate($this->ww, [ 'name' => "ABSTRACT 404 WITCH", 'invoke' => '404' ]);
    }
    
    
    /**
     * Daughter witches manipulation
     * @return self
     */
    function reorderDaughters(): self
    {
        $daughters                  = $this->daughters;
        $this->daughters            = Handler::reorderWitches( $daughters );
        
        return $this;
    }
    
    
    /**
     * Daughter witches manipulation
     * @return array
     */
    function listDaughtersIds(): array
    {
        $list = [];
        if( !empty($this->daughters) ){
            $list = array_keys($this->daughters);
        }
        
        return $list;
    }
    
    /**
     * Get Daughters witches (get them if needed), 
     * return daughter witchs array 
     * @return self[]
     */
    function daughters(): array
    {
        if( isset($this->daughters) ){
            return $this->daughters;
        }

        if( is_null($this->id) ){
            return [];
        }
        
        $this->daughters = [];
        Handler::addDaughters( 
            $this, 
            DataAccess::fetchDescendants($this->ww, $this->id, true) 
        );
        
        return $this->daughters;
    }
    
    /**
     * Read Daughter witches (get them if needed), 
     * return mother witch or false if witch is root
     * @return self
     */
    function daughter( int $id ): self
    {
        $daughters  = $this->daughters();
        $return     = $daughters[ $id ] ?? null;

        if( !$return ){
            $return = Handler::instanciate(
                $this->ww, 
                [ 'name' => "ABSTRACT 404 WITCH", 'invoke' => '404' ]
            );
        }

        return  $return;
    }
    
    
    /**
     * Invoke the module 
     * @param string|null $assignedModuleName
     * @param bool $isRedirection
     * @return string
     */
    function invoke( ?string $assignedModuleName=null, bool $isRedirection=false, bool $allowContextSetting=false ): string
    {
        if( !empty($assignedModuleName) ){
            $moduleName = $assignedModuleName;
        }
        else 
        {
            $moduleName             = $this->properties["invoke"];
            $allowContextSetting    = true;
        }

        if( empty($moduleName) ){
            return "";
        }

        $module     = new Module( $this, $moduleName );
        $module->setIsRedirection( $isRedirection );
        $module->setAllowContextSetting( $allowContextSetting );
        
        if( !$module->isValid() )
        {
            $this->ww->debug->toResume( "This module is not valid", 'MODULE '.$module->name );
            $this->modules[ $moduleName ]   = false;
            return "";
        }
        
        $permission = $this->isAllowed( $module );
        
        if( !$permission && !empty($module->config['notAllowed']) )
        {
            $this->ww->debug->toResume( "Access denied to for user: \"".$this->ww->user->name."\", redirecting to \"".$module->config['notAllowed']."\"", 'MODULE '.$module->name  );
            $result = $this->invoke( $module->config['notAllowed'], true, $allowContextSetting );
            $this->modules[ $moduleName ] = $this->modules[ $module->config['notAllowed'] ];
            return $result;
        }
        elseif( !$permission )
        {
            $this->ww->debug->toResume( "Access denied for user: \"".$this->ww->user->name."\"", 'MODULE '.$module->name );
            $this->modules[ $moduleName ]   = false;
            return "";
        }
        
        $this->modules[ $moduleName ]   = $module;
        
        return $module->execute();
    }    
    
    /**
     * Is user allowed to execute the module
     * @param Module $module
     * @param User $user
     * @return bool
     */
    function isAllowed( Module $module, ?User $user=null ): bool
    {
        if( empty($user) ){
            $user = $this->ww->user;
        }
        
        if( !empty($module->config['public']) ){
            $permission = true;
        }
        else // Is the current user has permission to access module ?
        {
            $permission = false;
            foreach( $user->policies as $policy )
            {
                if( $policy['module'] != '*' && $policy['module'] != $module->name ){
                    continue;
                }
                
                if( $policy["position"] === false )
                {
                    $permission = true;
                    break;
                }
                
                if( $policy["position_rules"]["self"] && $policy["position"] == $this->position )
                {
                    $permission = true;
                    break;
                }
                
                if( $policy["position_rules"]["ancestors"] && count($this->position) < count($policy["position"]) )
                {
                    $matchPosition = true;
                    foreach( $this->position as $level => $positionID ){
                        if( $policy["position"][ $level ] != $positionID )
                        {
                            $matchPosition = false;
                            break;
                        }
                    }
                }
                
                if( $policy["position_rules"]["descendants"] && count($policy["position"]) < count($this->position) )
                {
                    $matchPosition = true;
                    foreach( $policy["position"] as $level => $positionID ){
                        if( $this->position[ $level ] != $positionID )
                        {
                            $matchPosition = false;
                            break;
                        }
                    }
                }
                
                if( !empty($matchPosition) )
                {
                    $permission = true;
                    break;
                }
            }
        }
        
        return $permission;
    }
    
    /**
     * Read witch module, invoke it if needed
     * @param string|null $invoke
     * @return Module|boolean
     */
    function module( ?string $invoke=null )
    {
        $moduleInvoked  = $invoke ?? $this->properties["invoke"];
        
        if( !$moduleInvoked )
        {
            $this->ww->debug( "Try to reach unnamed module");
            return false;
        }        
        
        if( !isset($this->modules[ $moduleInvoked ]) ){
            $this->invoke( $moduleInvoked );
        }
        
        return $this->modules[ $moduleInvoked ];
    }
    

    /**
     * Read witch module result, invoke it if needed
     * @param string|null $invoke
     * @return string
     */
    function result( ?string $invoke=null )
    {
        $module = $this->module( $invoke );
        
        if( !$module ){
            return "";
        }
        
        return $module->getResult() ?? "";
    }
        
    
    /**
     * Update Witch
     * @param array $params
     * @return int|false
     */
    function edit( array $params ): int|false
    {
        foreach( $params as $field => $value ){
            if( !in_array($field, self::FIELDS) ){
                unset($params[ $field ]);
            }
        }
        
        // Name cannot be set to empty string
        if( !empty($params['name']) ){
            $params['name']   = trim($params['name']);
        }
        
        if( empty($params['name']) ){
            unset($params['name']);
        }
        
        $paramsKeyArray = array_keys($params);
        
        // If invoke is set to null
        if( in_array('invoke', $paramsKeyArray) && is_null($params['invoke']) )
        {
            //$params['site'] = null;
            $params['url']  = null;
        }
        // If invoke is not set but is actually null
        elseif( !in_array('invoke', $paramsKeyArray) && is_null($this->properties['invoke']) )
        {
            //$params['site'] = null;
            $params['url']  = null;
        }
        // If site is set to null
        elseif( in_array('site', $paramsKeyArray) && empty($params['site']) )
        {
            $params['site']     = null;
            $params['url']      = null;
            $params['invoke']   = null;
        }
        // If site is not set but is actually null
        elseif( !in_array('site', $paramsKeyArray) && is_null($this->properties['site']) )
        {
            $params['site']     = null;
            $params['url']      = null;
            $params['invoke']   = null;
        }
        // Invoke and site are valid and URL update is required
        elseif( in_array('url', $paramsKeyArray) )
        {
            $site       = $params['site'] ?? $this->properties['site'];
            $urlArray   = [];
            
            // If url is set to a value (ie not null)
            if( !is_null($params['url']) ){
                $urlArray[] = Tools::urlCleanupString( $params['url'] );
            }
            else 
            {
                $rootUrl    = ""; 
                if( $this->mother() ){
                    $rootUrl    = $this->mother()->getClosestUrl( $site );
                }
                
                if( !empty($rootUrl) ){
                    $rootUrl .= '/';
                }
                else {
                    $urlArray[] = '';
                }
                
                $rootUrl    .=  Tools::cleanupString($params['name'] ?? $this->name);
                $urlArray[] =   $rootUrl;                
            }
            
            if( !empty($urlArray) ){
                $params['url'] = Handler::checkUrls( $this->ww, $site, $urlArray, $this->id );
            }
        }
        
        if( empty($params) ){
            return false;
        }
        
        $updateResult = DataAccess::update($this->ww, $params, ['id' => $this->id]);
        
        if( $updateResult === false ){
            return false;
        }
        
        foreach( $params as $field => $value ){
            $this->properties[$field] = $value;
        }

        Handler::readProperties( $this );

        return $updateResult;
    }
    
    /**
     * Add a new witch daughter 
     * @param array $params 
     * @return self|false 
     */
    function createDaughter( array $params ): self|false
    {
        // Name cannot be set to empty string 
        $params['name'] = trim( $params['name'] ?? "" );
        
        if( empty($params['name']) ){
            return false;
        }
        
        if( $this->depth == $this->ww->depth ){
            Handler::addLevel($this->ww);
        }
        
        $newDaughterPosition                        = $this->position;
        $newDaughterPosition[ ($this->depth + 1) ]  = DataAccess::getNewDaughterIndex($this->ww, $this->position);
        
        if( !isset($params['site']) )
        {
            $params['site'] = $this->site;

            if( !isset($params['status']) ){
                $params['status'] = $this->statusLevel;
            }    
        }

        if( empty($params['site']) )
        {
            $params['url']      = null;
            $params['invoke']   = null;
        }
        elseif( empty($params['invoke']) ){
            $params['url']      = null;
        }        
        else
        {
            $urlArray   = [];
            
            // If url is set to a value (ie not null)
            if( $params['url'] ?? false ){
                $urlArray[] = Tools::urlCleanupString( $params['url'] );
            }
            else 
            {
                $rootUrl    = $this->getClosestUrl( $params['site'] );
                
                if( !empty($rootUrl) ){
                    $rootUrl .= '/';
                }
                else {
                    $urlArray[] = '';
                }
                
                $rootUrl    .=  Tools::cleanupString($params['name']);
                $urlArray[] =   $rootUrl;                
            }

            if( !empty($urlArray) ){
                $params['url'] = Handler::checkUrls( $this->ww, $params['site'], $urlArray, $this->id );
            }
        }        
        
        foreach( $newDaughterPosition as $level => $levelPosition ){
            $params[ "level_".$level ] = $levelPosition;
        }
        
        $newWitchId = DataAccess::create($this->ww, $params);
        if( !$newWitchId ){
            return false;
        }

        $params['id'] = $newWitchId;

        return Handler::instanciate( $this->ww, $params );
    }
        
    
    /**
     * Get closest ancestor url for a given site
     * @param string|null $forSite
     * @return string
     */
    function getClosestUrl( ?string $forSite=null ): string
    {
        $url    = "";
        $site   = $forSite ?? $this->site;

        $ancestorWitch = $this;
        while( $ancestorWitch !== false && $ancestorWitch->depth > 0 )
        {
            if( $ancestorWitch->site == $site ){
                $url = $ancestorWitch->url ?? "";
                break;
            }
            
            $ancestorWitch = $ancestorWitch->mother();
        }
        
        return $url;
    }
    
    
    /**
     * Delete witch if it's not the root,
     * Delete all descendants and their associated cauldron if this is their only witch association
     * @param bool $fetchDescendants
     * @return bool
     */
    function delete( bool $fetchDescendants=true ): bool
    {
        if( is_null($this->id) || $this->mother() === false || $this->depth == 0 ){
            return false;
        }
        
        if( $fetchDescendants ){
            Handler::addDaughters( 
                $this, 
                DataAccess::fetchDescendants($this->ww, $this->id, true) 
            );
        }
        
        $deleteIds = array_keys($this->daughters ?? []);
        foreach( $this->daughters ?? [] as $daughter ){
            if( !$daughter->delete(false) ){
                return false;
            }
        }
        
        if( $this->hasCauldron() ){
            $this->cauldron()->removeWitch( $this );
        }
        
        
        if( $fetchDescendants ){
            $deleteIds[] = $this->id;
        }
        
        return DataAccess::delete($this->ww, $deleteIds);
    }    
    
    /**
     * Delete associated cauldron if this is their only witch association,
     * if not only remove this association
     * @return bool
     */
    function removeCauldron(): bool
    {
        if( !$this->hasCauldron() ){
            return false;
        }
        
        return $this->cauldron()->removeWitch( $this );
    }
    

        
    /**
     * Test if witch is a descendant
     * @param self $potentialDescendant
     * @return bool
     */
    function isParent( self $potentialDescendant ): bool
    {
        $potentialDescendantPasition = $potentialDescendant->position;
        foreach( $this->position as $level => $levelPosition ){
            if( empty($potentialDescendantPasition[ $level ]) ||  $potentialDescendantPasition[ $level ] != $levelPosition ){
                return false;
            }
        }
        
        return true;
    }
    
    
    /**
     * Generate this witch url, 
     * relative if no forcedWebsite is passed, full if there is one
     * @param array|null $queryParams
     */
    function url( ?array $queryParams=null ): ?string
    {
        if( is_null($this->url) ){
            return null;
        }

        $website = $this->site() ?? $this->ww->website;
        
        
        if( $website !==  $this->ww->website ){
            $method = "getFullUrl";
        }
        else {
            $method = "getUrl";
        }
        
        return call_user_func([$website, $method], $this->url, $queryParams ?? null);
    }
    
    
    function moveTo( self $witch )
    {
        $this->ww->db->begin();
        try {
            $this->innerTransactionMoveTo( $witch );
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
    
    private function innerTransactionMoveTo( self $witch, array $urlSiteRewrite=[] )
    {
        $position = $witch->position;
        
        $depth = count($position);
        
        if( $depth == $this->ww->depth + 1 ){
            Handler::addLevel($this->ww);
        }
        
        $newPosition                    = $position;
        $newPosition[ ($depth + 1) ]    = DataAccess::getNewDaughterIndex($this->ww, $position);
        
        $params = [];
        for( $i=1; $i <= $this->ww->depth; $i++ ){
            $params[ "level_".$i ] = NULL;
        }
        
        foreach( $newPosition as $level => $levelPosition ){
            $params[ "level_".$level ] = $levelPosition;
        }

        if( $this->mother() && !empty($this->properties['url']) && !empty($this->properties['site']) )
        {
            $previousUrl = $this->mother()->getClosestUrl();
            if( str_starts_with($this->url, $previousUrl) )
            {
                $url            = substr( $this->url, strlen($previousUrl) );
                $destinationUrl = $urlSiteRewrite[ $this->site ] ?? $witch->getClosestUrl( $this->site );
                $params['url']  = $destinationUrl.$url;
                if( substr($params['url'], 0, 1) === '/' && substr($params['url'], 1, 1) === '/' ){
                    $params['url']  = substr($params['url'], 1);
                }
                $urlSiteRewrite[ $this->site ] = $params['url'];
            }
        }
        
        $daughters      = $this->daughters();
        DataAccess::update($this->ww, $params, ['id' => $this->id]);
        $this->position = $newPosition;
        $this->depth    = count( $this->position );

        if( !empty($daughters) ){
            foreach( $daughters as $daughterWitch )
            {
                $daughterWitch->innerTransactionMoveTo( $this, $urlSiteRewrite );
            }
        }
        
        return;
    }

    function copyTo( self $witch )
    {
        $this->ww->db->begin();
        try {
            $newWitch = $this->innerTransactionCopyTo( $witch );
        } 
        catch( \Exception $e ) 
        {
            $this->ww->log->error($e->getMessage());
            $this->ww->db->rollback();
            return false;
        }
        $this->ww->db->commit();
        
        return $newWitch;        
    }

    private function innerTransactionCopyTo( self $witch, array $urlSiteRewrite=[] )
    {
        $excludeFields = [ "id", "datetime" ];
        $params = [
            "status"            => $this->statusLevel,
            "cauldron"          => $this->cauldronId,
            "cauldron_priority" => 0,
        ];

        foreach( self::FIELDS as $field ){
            if( !in_array($field, $excludeFields) && !in_array($field, array_keys($params)) ){
                $params[ $field ] = $this->$field;
            }
        }
        
        if( $this->mother() && !empty($params['url']) && !empty($params['site']) )
        {
            $previousUrl = $this->mother()->getClosestUrl();
            if( str_starts_with($params['url'], $previousUrl) )
            {
                $url            = substr( $params['url'], strlen($previousUrl) );
                $destinationUrl = $urlSiteRewrite[ $this->site ] ?? $witch->getClosestUrl( $this->site );
                $params['url']  = $destinationUrl.$url;
                $urlSiteRewrite[ $this->site ] = $params['url'];
            }
        }
        
        $newWitch   = $witch->createDaughter( $params );
        $daughters  = $this->daughters();
        
        if( !empty($daughters) ){
            foreach( $daughters as $daughterWitch )
            {
                $daughterWitch->innerTransactionCopyTo( $newWitch, $urlSiteRewrite );
            }
        }
        
        return $newWitch;
    }
    
    /**
     * Cauldron witch content, store it in the Cairn (if exists, only read it)
     * @return ?Cauldron
     */
    function cauldron(): ?Cauldron
    {
        if( !$this->hasCauldron() ){
            return null;
        }

        if( empty($this->cauldron) ){
            CauldronHandler::fetch( 
                $this->ww, 
                [ $this->properties["cauldron"] ]
            );
        }
        
        return $this->cauldron;
    }
    

    /**
     * witch website
     * @return ?Website
     */
    function site(): ?Website
    {
        if( is_object($this->site) ){
            return $this->site;
        }
        
        $site       = $this->site;
        $witchRef   = $this->mother;
        
        while( !$site && $witchRef )
        {
            $site       = $witchRef->site;
            $witchRef   = $witchRef->mother;
        }

        if( !$site ){
            return null;
        }
        elseif( is_object($site) ){
            $this->site = $site;
        }
        elseif( $site === $this->ww->website->name ){
            $this->site = $this->ww->website;
        }
        else {
            $this->site = new Website( $this->ww, $site );
        }

        return $this->site;
    }
    


}
