<?php
namespace WW;

use WW\Handler\WitchHandler as Handler;

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

    public array $properties  = [];
    
    public ?int $id                     = null;
    public ?string $name                = null;
    public ?string $data                = null;
    public ?string $site                = null;
    public ?Website $website            = null;
    public ?string $url                 = null;
    public ?string $status              = null;
    public ?string $invoke              = null;
    public ?Cauldron $cauldron          = null;
    public ?int $cauldronPriority       = null;
    public ?string $context             = null;
    public ?\DateTime $datetime         = null;
    public ?int $priority               = null;
    
    public int $statusLevel     = 0;
    public ?int $cauldronId     = null;
    public int $depth           = 0;
    public ?array $position     = null;
    public array $modules       = [];
    
    public ?self $mother        = null;

    /** @var ?self[] */
    public ?array $sisters      = null;

    /** @var ?self[] */
    public ?array $daughters    = null;

    /**  WoodWiccan container class to allow whole access to Kernel */
    public WoodWiccan $ww;
    

    /**
     * Name reading
     */
    public function __toString(): string {
        return $this->name ?? "";
    }

    /**
     * Magic function is dedicated to property reading, 
     * if not property is found it try to read cauldron content
     * @var string $name
     */
    public function __get(string $name): mixed 
    {
        if( array_key_exists($name, $this->properties) ){
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
        if( is_null($this->status) ){
            $this->status = $this->website()?->status( $this->statusLevel );
        }
        
        if( is_null($this->status) )
        {
            $statusList     = $this->ww->configuration->read('global', 'status');
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
        if( is_array($this->position) && count($this->position) === 0 ){
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
        
        if( is_null($this->mother) && !is_null($this->id) ){
            Handler::setMother( $this, Handler::fetchAncestors($this) );
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
     * @return ?array
     */
    function sisters(): ?array
    {
        if( isset($this->sisters) ){
            return $this->sisters;
        }

        if( !$this->mother() ){
            return [];
        }
        
        foreach( $this->mother()->daughters() as $sister ){
            if( $sister !== $this ){
                Handler::addSister( $this, $sister );
            }
        }

        return $this->sisters;
    }
    
    /**
     * Read Sister witches (get them if needed), 
     * return mother witch or false if witch is root
     * @return mixed
     */
    function sister( int $id ): mixed
    {
        if( !$this->sisters() 
            || !isset($this->sisters[ $id ])
        ){
            return Handler::instanciate($this->ww, [ 'name' => "ABSTRACT 404 WITCH", 'invoke' => '404' ]);
        }
        
        return  $this->sisters[ $id ];
    }

    /**
     * Get Daughters witches (get them if needed), 
     * return daughter witchs array 
     * @return self[]
     */
    function daughters(): array
    {
        if( !is_null($this->daughters) ){
            return $this->daughters;
        }

        $this->daughters = [];
        if( !is_null($this->id) ){
            Handler::addDaughters( 
                $this, 
                Handler::fetchDescendants($this)
            );
        }
        
        return $this->daughters;
    }
    
    /**
     * Read Daughter witches (get them if needed), 
     * return mother witch or false if witch is root
     * @param int $id 
     * @return ?self
     */
    function daughter( int $id ): ?self
    {
        foreach( $this->daughters() as $daughter ){
            if( $daughter->id === $id ){
                return $daughter;
            }
        }

        return Handler::instanciate(
            $this->ww, 
            [ 'name' => "ABSTRACT 404 WITCH", 'invoke' => '404' ]
        );
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
     * @param array $inputs
     * @return self
     */
    function edit( array $inputs ): self
    {
        $exitingInputs  = array_keys($inputs);

        if( in_array('name', $exitingInputs)
            && !empty($name = trim( (string) $inputs['name'] )) 
        ){
            $this->name     = $name;
        }
        
        if( in_array('data', $exitingInputs) )
        {
            $this->data = null;
            if( $data = trim( (string) $inputs['data'] ) ){
                $this->data = $data;
            }
        }
        
        if( in_array('site', $exitingInputs) )
        {
            if( is_object( $inputs['site'] ) 
                && is_a( $inputs['site'], Website::class )
            ){
                $this->website  =  $inputs['site'];
                $this->site     =  $this->website->name;
            }
            elseif( $site = $inputs['site'] )
            {
                $this->site = trim( $site );

                if( $this->site !== $this->website?->name ){
                    $this->website  =  null;
                }
            }
            else 
            {
                $this->website  =  null;
                $this->site     =  null;
            } 
        }
        
        if( in_array('status', $exitingInputs) )
        {
            $this->statusLevel  = (int) $inputs['status'];
            $this->status       = null;
        }
        
        if( in_array('invoke', $exitingInputs) )
        {
            $this->invoke = null;
            if( $invoke = trim( (string) $inputs['invoke'] ) ){
                $this->invoke = $invoke;
            }
        }
        
        if( in_array('cauldron', $exitingInputs) )
        {
            $this->cauldronId = null;
            if( isset($inputs['cauldron']) 
                && ctype_digit(strval($inputs['cauldron'])) 
            ){
                $this->cauldronId = (int) $inputs['cauldron'];
            }

            if( $this->cauldronId !== $this->cauldron?->id ){
                $this->cauldron   = null;
            }
        }

        if( in_array('cauldron_priority', $exitingInputs) ){
            $this->cauldronPriority = (int) $inputs['cauldron_priority'];
        }

        if( in_array('context', $exitingInputs) )
        {
            $this->context = null;
            if( $context = trim( (string) $inputs['context'] ) ){
                $this->context = $context;
            }
        }

        if( in_array('datetime', $exitingInputs) )
        {
            if( is_object( $inputs['datetime'] ) 
                    && is_a( $inputs['datetime'], '\DateTime' ) ){
                $this->datetime = $inputs['datetime'];
            }
            else {
                $this->datetime = new \DateTime( $inputs['datetime'] );
            }
        }

        if( in_array('priority', $exitingInputs) ){
            $this->priority = (int) $inputs['priority'];
        }
        
        if( isset($inputs['url']) 
            && $this->invoke
            && $this->site 
        ){
            $this->url = Handler::checkUrls( 
                $this->ww, 
                $this->site, 
                [Tools::urlCleanupString( $inputs['url'] )], 
                $this->id 
            );
        }
        elseif(  
            in_array('url', $exitingInputs)
            && $this->invoke
            && $this->site
        ){
            $urlArray   = [];
            $rootUrl    = ""; 
            if( $this->mother() ){
                $rootUrl    = $this->mother()->getClosestUrl( $this->site );
            }
            
            if( !empty($rootUrl) ){
                $rootUrl .= '/';
            }
            else {
                $urlArray[] = '';
            }
            
            $rootUrl    .=  Tools::cleanupString( $this->name );
            $urlArray[] =   $rootUrl;                
            
            $this->url = Handler::checkUrls( 
                $this->ww, 
                $this->site, 
                $urlArray, 
                $this->id 
            );
        }

        if(
            isset( $this->url )  
            && (!isset( $this->invoke ) || !isset( $this->site ))
        ){
            $this->url = null;
        }

        if(  
            isset( $this->invoke )
            && (!isset( $this->url ) || !isset( $this->site ))
        ){
            $this->invoke = null;
        }

        return $this;
    }

    /**
     * Add a new daughter 
     * @param array $params 
     * @return self
     */
    function newDaughter( array $params ): self
    {
        $witch      = new self();
        $witch->ww  = $this->ww;
        
        $this->daughters();

        $this->daughters[]  = $witch->edit( $params );
        $witch->mother      = $this;
        
        return $witch;
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
     * @return bool
     */
    function delete( ): bool
    {
        if( is_null($this->id) // Not saved
            || $this->depth == 0 // WW Root witch
            || $this->mother() === false // Realtive root witch
        ){
            return false;
        }
        
        return Handler::delete( $this );
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
        foreach( $this->position as $level => $levelPosition ){
            if( !$potentialDescendant->position($level) 
                ||  $potentialDescendant->position($level) != $levelPosition 
            ){
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

        $website    = $this->website() ?? $this->ww->website;
        $method     = "getUrl";

        if( $website->name ===  $this->ww->website->site ){
            $website    = $this->ww->website;
        }
        elseif( $website->name !==  $this->ww->website->site ){
            $method = "getFullUrl";
        }
        
        return call_user_func([$website, $method], $this->url, $queryParams ?? null);
    }

    /**
     * recursive function that apply evolutions of site and/or url
     * to this witch and her descendants
     * @param ?array $siteEvolution [ 'from' => string, 'to' => string ] 
     * @param ?array $urlEvolution [ 'from' => string, 'to' => string ] 
     * @return void
     */    
    private function replaceSiteAndUrl( ?array $siteEvolution=null, ?array $urlEvolution=null ): void
    {
        if( !$siteEvolution && !$urlEvolution ){
            return;
        }

        if( $siteEvolution && $this->site === $siteEvolution['from'] )
        {
            $this->site = $siteEvolution['to'];

            if( $this->website?->name !== $this->site ){
                $this->website = null;
            }
        }

        if( $urlEvolution && str_starts_with($this->url ?? "", $urlEvolution['from']) ){
            $this->url = $urlEvolution['to'].substr( $this->url, strlen($urlEvolution['from']) );
        }

        foreach( $this->daughters() as $daughter ){
            $daughter->replaceSiteAndUrl( $siteEvolution, $urlEvolution );
        }
        
        return;
    }

    /**
     * Move this witch and all her descendants to a new destination witch (new mother witch)
     * @param self $witch is the new mother of this witch
     * @param array $order array of witches id, giving the wanted displayed order 
     * for daughters of destination witch
     * @return bool
     */
    function moveTo( self $witch, array $order=[] ): bool
    {
        $this->ww->db->begin();
        try {
            $evolutions = $this->getSiteUrlEvolutions( $witch );

            $this->replaceSiteAndUrl( $evolutions['site'] ?? null, $evolutions['url'] ?? null );

            $this->innerTransactionMoveTo( $witch );
            $this->save( false );

            if( $order ){
                Handler::setPriorities($this, $order);
            }
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
    
    /**
     * recursive move action to be encapsulated in try/catch bock
     * @param self $witch destination witch (new mother witch)
     * @return void
     */
    private function innerTransactionMoveTo( self $witch ): void
    {
        $this->daughters();

        $this->mother   = $witch;
        $this->position = null;
        $this->depth    = $witch->depth + 1;

        foreach( $this->daughters as $daughter ){
            $daughter->innerTransactionMoveTo( $this );
        }

        return;
    }

    /**
     * generate the evolutions of site ans url in use in copy or move action
     * @param self $destination destination witch (new mother witch)
     * @return array [ 'site' => ['from' => string, 'to' => string], 'url' => ['from' => string, 'to' => string] ]
     */
    private function getSiteUrlEvolutions( self $destination ): array
    {
        $evolutions     = [];

        $prevSite       = $this->site();
        $destSite       = $destination->site();
        if( $prevSite && $destSite ){
            $evolutions['site'] = [ 
                'from'  => $prevSite,
                'to'    => $destSite
            ];
        }

        $init           = $evolutions['site']['from'] ?? null;
        $prevUrl        = $this->mother()?->getClosestUrl( $init );
        $init           = $evolutions['site']['to'] ?? $evolutions['site']['from'] ?? null;
        $destUrl        = $destination->getClosestUrl( $init );
        if( $prevUrl && $destUrl ){
            $evolutions['url'] = [ 
                'from'  => $prevUrl.'/',
                'to'    => $destUrl.'/'
            ];
        }

        return $evolutions;
    }

    /**
     * Copy this witch and all her descendants to a new destination witch (new mother witch)
     * @param self $witch is the mother of the new witch (destination witch)
     * @param array $order array of witches id, giving the wanted displayed order 
     * for daughters of destination witch
     * @return ?self new copied witch or null if failed
     */
    function copyTo( self $witch, array $order=[] ): ?self
    {
        $this->ww->db->begin();
        try {
            $evolutions = $this->getSiteUrlEvolutions( $witch );
            $newWitch   = $this->innerTransactionCopyTo( $witch, $evolutions['site'] ?? null, $evolutions['url'] ?? null );
            $newWitch->save( false );

            if( $order ){
                foreach( $order as $key => $orderId ){
                    if( $orderId === $this->id ){
                        $order[ $key ] = $newWitch->id;
                    }
                }
                Handler::setPriorities($witch, $order);
            }
        } 
        catch( \Exception $e ) 
        {
            $this->ww->log->error($e->getMessage());
            $this->ww->db->rollback();
            return null;
        }
        $this->ww->db->commit();
        
        return $newWitch;        
    }

    /**
     * recursive copy action to be encapsulated in try/catch bock
     * @param self $witch destination witch (new mother witch)
     * @param ?array $siteEvolution [ 'from' => string, 'to' => string ]
     * @param ?array $urlEvolution [ 'from' => string, 'to' => string ]
     * @return self new copied witch
     */
    private function innerTransactionCopyTo( self $witch, ?array $siteEvolution=null, ?array $urlEvolution=null ): self
    {
        Handler::writeProperties( $this );

        $excludeFields = ['id', 'datetime'];
        for( $i=1; $i<=$this->ww->depth; $i++ ){
            $excludeFields[] = 'level_'.$i;
        }

        $params = [];
        foreach( $this->properties as $key => $value ){
            if( !in_array($key, $excludeFields) ){
                $params[ $key ] = $value;
            }
        }

        if( $siteEvolution && $params['site'] === $siteEvolution['from'] ){
            $params['site'] = $siteEvolution['to'];
        }

        if( $urlEvolution && str_starts_with($params['url'], $urlEvolution['from']) ){
            $params['url'] = $urlEvolution['to'].substr( $params['url'], strlen($urlEvolution['from']) );
        }

        $newWitch   = $witch->newDaughter( $params );
        
        foreach( $this->daughters() ?? [] as $daughterWitch ){
            $daughterWitch->innerTransactionCopyTo( $newWitch, $siteEvolution, $urlEvolution );
        }
        
        return $newWitch;
    }

    /**
     * Get witch's cauldron, fetch it if not there
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
     * Get witch site name
     * @return ?string
     */
    function site(): ?string
    {
        if( !$this->site && $this->mother ){
            return $this->mother->site();
        }
        
        return $this->site;
    }
    
    /**
     * Get witch website
     * @return ?Website
     */
    function website(): ?Website
    {
        if( !$this->website ){
            if( $this->site ){
                $this->website = new Website( $this->ww, $this->site );
            }
            elseif( $this->mother ){
                $this->website = $this->mother->website();
            }
        }

        return $this->website;
    }
    
    /**
     * Save witch in BDD
     * @param bool $transactionMode : set to false to escape transactional mode
     * @return ?bool true for success, false for failure, null for no effect
     */
    function save( bool $transactionMode=true ): ?bool
    {
        if( !$transactionMode ){
            return $this->saveAction();
        }

        $this->ww->db->begin();
        try {
            $result = $this->saveAction();

            if( $result === false )
            {
                $this->ww->db->rollback();
                return false;
            }
        } 
        catch( \Exception $e ) 
        {
            $this->ww->log->error($e->getMessage());
            $this->ww->db->rollback();
            return false;
        }
        $this->ww->db->commit();

        return $result;
    }

    /**
     * recursive save action to be encapsulated in try/catch bock
     * save descendants witches if needed
     * @return ?bool true for success, false for failure, null for no effect
     */
    protected function saveAction(): ?bool
    {
        $this->position();
        $this->daughters();

        $updated    = false;
        $result     = Handler::save( $this );

        if( $result === false ){
            return false;
        }
        elseif( $result ){
            $updated = true;
        }

        foreach( $this->daughters ?? [] as $daughter ) 
        {
            $daughterSave = $daughter->save( false );

            if( $daughterSave === false ){
                return false;
            }
            elseif( $result ){
                $updated = true;
            }
        }

        if( $updated === false ){
            return null;
        }

        return $result;
    }

    /**
     * Get witch descendant position or positions array 
     * in genealogic tree
     * @param ?int $level if set, will return value for the this depth level (all positions array if null)
     * @return null|int|int[] 
     */
    function position( ?int $level=null ): array|int|null
    {
        if( is_null($this->position) ){
            Handler::position( $this );
        }

        if( !$level ){
            return $this->position;
        }

        return $this->position[ $level ] ?? null;
    }
}
