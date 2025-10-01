<?php
namespace WW;

use WW\DataAccess\WitchDataAccess;
use WW\Handler\CauldronHandler;

/**
 * Class that handles witch summoning and modules invocation
 * 
 * @author Jean2Grom
 */
class Cairn 
{
    const DEFAULT_WITCH = "url";
    
    /** @var Witch[] */
    private $witches;

    /** @var Cauldron[] */
    private $cauldrons;

    public $invokations;
    
    public $configuration;
    
    /** 
     * Class containing website (app) information and aggreging related objects
     * @var Website 
     */
    public Website $website;
    
    /** 
     * WoodWiccan container class to allow whole access to Kernel
     * @var WoodWiccan
     */
    public WoodWiccan $ww;
    
    function __construct( WoodWiccan $ww, array $summoningConfiguration, ?Website $forcedWebsite=null )
    {
        $this->ww       = $ww;
        $this->website  = $forcedWebsite ?? $this->ww->website;
        
        $this->witches      = [];
        $this->cauldrons    = [];
        
        $this->invokations      = [];
        $this->configuration    = self::prepareConfiguration($this->website, $summoningConfiguration);
    }
    
    function __get( string $witchRef ): ?Witch {
        return $this->witches[ $witchRef ] ?? null; 
    }

    static function prepareConfiguration(  Website $website, array $rawConfiguration ): array
    {
        $arboConf = function ($init, $new) {
            if( is_array($new) )
            {
                $innerDepth      = 1;
                $innerCraft = false;
                if( is_array($init) )
                {
                    $innerDepth = $init['depth'];
                    $innerCraft = $init['craft'];
                }

                if( $innerDepth === '*' || $new['depth'] === '*' ){
                    $innerDepth = '*';
                }
                elseif( !empty($new['depth']) && $new['depth'] >= $innerDepth ){
                    $innerDepth = $new['depth'];
                }

                if( $innerCraft === '*' || $new['craft'] === '*' ){
                    $innerCraft = '*';
                }
                elseif( !empty($new['craft']) && $new['craft'] >= $innerCraft ){
                    $innerCraft = $new['craft'];
                }

                return [
                    'depth' => $innerDepth,
                    'craft' => $innerCraft,
                ];
            }
            else {
                return $init;
            }
        };
        
        $conf = [];
        foreach( $rawConfiguration as $entry => $entryConf )
        {
            if( !$entryConf['match'] ){
                continue;
            }

            $index = $entryConf['match'];
            if( is_int($entryConf['match']) ){
                $match = [ 'id' => $entryConf['match'] ];
            }
            elseif( $entryConf['match'] === "url" ){
                $match = $website->getUrlSearchParameters();
            }
            elseif( $entryConf['match'] === "user" ){
                $match = [ 'cauldron' => "user" ];
            }
            else 
            {
                $value = $website->ww->request->param(
                    $entryConf['get'], 
                    'get', 
                    FILTER_VALIDATE_INT
                );

                if( !$value ){
                    continue;
                }
                $index = $value;

                $match = [ 'id' => $index ];
            }

            $entries    = $conf[ $index ]['entries']    ?? [];
            $modules    = $conf[ $index ]['modules']    ?? [];
            $craft      = $conf[ $index ]['craft']      ?? false;

            $parents    = $conf[ $index ]['parents']    ?? false;
            $sisters    = $conf[ $index ]['sisters']    ?? false;
            $children   = $conf[ $index ]['children']   ?? false;

            $conf[ $index ] = [
                'match'         => $match,
                'entries'       => array_merge(
                    $entries, 
                    [ $entry => $entryConf['invoke'] ?? false ]
                ),
                'modules'       => array_merge(
                    $modules, 
                    isset($entryConf['module'])?  [ $entryConf['module'] ]: []
                ),
                'craft'         => ($entryConf['craft'] ?? true) || $craft,

                'parents'   => $arboConf( $parents, $entryConf['parents'] ?? false ),
                'sisters'   => $arboConf( $sisters, $entryConf['sisters'] ?? false ),
                'children'  => $arboConf( $children, $entryConf['children'] ?? false ),
            ];
        }

        foreach( $conf as $index => $confItem ){
            if( !empty($confItem['sisters']) && empty($confItem['parents']) ){
                $conf[ $index ]['parents'] = [
                    "depth" => 1,
                    "craft" => false
                ];
            }
        }

        return $conf;
    }
    
    private function getCauldrons()
    {
        $cauldronsConf = [];
        foreach( $this->configuration as $witchConf )
        {
            $refWitch = array_keys($witchConf['entries'])[0];
            
            if( !$this->witch($refWitch) ){
                continue;
            }
            
            $permission = false;
            foreach( $witchConf['entries'] as $invoke )
            {
                if( $invoke === false ){
                    $permission = true;
                }
                elseif( $invoke == true && $this->witch($refWitch)->hasInvoke() )
                {
                    $module     = new Module( $this->witch($refWitch), $this->witch($refWitch)->invoke );
                    $permission = $this->witch( $refWitch )->isAllowed( $module );
                }
                else 
                {
                    $module     = new Module( $this->witch( $refWitch ), $invoke );
                    $permission = $this->witch( $refWitch )->isAllowed( $module );
                }
                
                if( $permission ){
                    break;
                }
            }
            
            if( !$permission ){
                continue;
            }
            
            if( (!isset( $witchConf['craft'] ) || !empty( $witchConf['craft'] )) 
                && $this->witch( $refWitch )->hasCauldron()
            ){
                $cauldronsConf[] = $this->witch( $refWitch )->cauldronId;
            }
            
            if( !empty($witchConf['parents']['craft']) ){
                $cauldronsConf = array_merge_recursive( 
                    $cauldronsConf, 
                    self::getParentsCraftData( $this->witch($refWitch), $witchConf['parents']['craft'] )
                );
            }

            if( !empty($witchConf['sisters']['craft']) && !empty($witches[ $refWitch ]->sisters) ){
                foreach( $this->witch($refWitch)->sisters as $sisterWitch ){
                    $cauldronsConf = array_merge_recursive( 
                        $cauldronsConf, 
                        self::getChildrenCraftData( $sisterWitch, $witchConf['sisters']['craft'] )
                    );
                }
            }

            if( !empty($witchConf['children']['craft']) ){
                $cauldronsConf = array_merge_recursive( 
                    $cauldronsConf, 
                    self::getChildrenCraftData( $this->witch($refWitch), $witchConf['children']['craft'] )
                );
            }
        }
        
        return $cauldronsConf? CauldronHandler::fetch($this->ww, array_unique($cauldronsConf), false): [];
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



    
    function summon()
    {
        $this->addWitches(  WitchDataAccess::summon($this->ww, $this->configuration) );
        $this->addCauldrons( $this->getCauldrons() );

        return $this;
    }
    

    function sabbath()
    {
        foreach( $this->configuration as $witchConf ){
            foreach( $witchConf['entries'] as $refWitch => $invoke ){
                if( $this->witch( $refWitch ) ){
                    if( empty($invoke) ){
                        continue;
                    }
                    elseif( is_string($invoke) ){
                        $this->invokations[ $refWitch ] = $this->witch( $refWitch )->invoke( $invoke );
                    }
                    else {
                        $this->invokations[ $refWitch ] = $this->witch( $refWitch )->invoke();
                    }
                }
            }
        }
        
        return true;
    }
    
    function invokation( ?string $witchConfRef=null ): string
    {
        $ref = $witchConfRef ?? self::DEFAULT_WITCH;
        
        return  $this->invokations[ $ref ] ?? "";
    }
    
    /**
     * @param Cauldron[] $cauldrons
     * @return self
     */
    function addCauldrons( array $cauldrons ): self
    {
        $this->cauldrons = $cauldrons;

        foreach( $this->cauldrons as $cauldron )
        {
            foreach( $this->searchByCauldronId($cauldron->id) as $witch )
            {
                $witch->cauldron                    = $cauldron;
                $cauldron->witches[ $witch->id ]    = $witch;
            }
        
            $cauldron->orderWitches();
        }
                
        return $this; 
    }

    /**
     * @param ?string $witchRef : name of reference witch to read or add
     * @param ?Witch $witch : if set, instance of Witch to add to cairn
     */
    function witch( ?string $witchRef=null, ?Witch $witch=null )
    {
        if( $witch && !$witchRef ){
            return false;
        }
        elseif( $witch ){
            return $this->addWitch( $witchRef, $witch );
        }

        return $this->getWitch( $witchRef );
    }

    /**
     * @param string $name
     * @param Witch $witch
     * @return bool
     */
    function addWitch( string $name, Witch $witch ): bool
    {
        if( $witch instanceof Witch )
        {
            $this->witches[ $name ] = $witch;
            return true;
        }
        
        return false; 
    }

    /**
     * @param ?string $witchRef ref of required witch, if null get default one
     * @return ?Witch 
     */
    function getWitch( ?string $witchRef=null ): ?Witch {
        return  $this->witches[ $witchRef ?? self::DEFAULT_WITCH ] ?? null;
    }


    /**
     * @param ?Witch[] $witches : associative array [ $witchRef => $witch ] to add, 
     * if null, function will read cairn witches
     */
    function witches( ?array $witches=null ) 
    {
        if( !is_null($witches) ){
            return $this->addWitches( $witches );
        }

        return $this->getWitches(); 
    }
    
    /** 
     * @param Witch[] $witches : associative array [ $witchRef => $witch ]
     * @return self
     */    
    function addWitches( array $witches ): self
    {
        foreach( $witches as $witchRef => $witch ){
            $this->addWitch( $witchRef, $witch );
        }
        
        return $this; 
    }

    /**
     * @return Witch[]
     */
    function getWitches(): array {
        return $this->witches; 
    }
    
    
    // SEARCH methodes
    function searchFromPosition( array $position ): ?Witch
    {
        foreach( $this->witches as $witch )
        {
            if( $witch->position === $position ){
                return $witch;
            }
            $witchBuffer    = $witch;
            $continue       = true;
            while( $continue && $witchBuffer )
            {
                $continue = false;
                foreach( $witchBuffer->position as $level => $value ){
                    if( !isset($position[ $level ]) || $position[ $level ] != $value )
                    {
                        $witchBuffer    = $witchBuffer->mother;
                        $continue       = true;
                        break;
                    }
                }
                
                if( $witchBuffer && $witchBuffer->position == $position ){
                    return $witchBuffer;
                }
                elseif( $continue ){
                    continue;
                }                
                
                foreach( $witchBuffer->daughters as $daughter ){
                    if( $daughter->position == $position ){
                        return $daughter;
                    }
                    else
                    {
                        $level = $witchBuffer->depth + 1;
                        if( $daughter->position[ $level ] == $position[ $level ] ) 
                        {
                            $witchBuffer    = $daughter;
                            $continue       = true;
                            break;
                        }
                    }
                }
                
                if( $witchBuffer->position == $position ){
                    return $witchBuffer;
                }
            }
        }
        
        return null;
    }

    function searchById( int $id ): ?Witch
    {
        foreach( $this->witches as $witch )
        {
            if( $witch->id == $id ){
                return $witch;
            }

            $witchRoot  = $witch;
            while( $witchRoot->mother ){
                $witchRoot    = $witchRoot->mother;
            }

            if( $witchRoot->id == $id ){
                return $witchRoot;
            }
        
            $result = $this->recursiveSearchById( $witchRoot, $id );
            if( $result ){
                return $result;
            }
        }
        
        return null;
    }

    private function recursiveSearchById( Witch $witch, int $id ): ?Witch
    {
        if( in_array($id, array_keys($witch->daughters())) ){
            return $witch->daughters[ $id ];
        }

        foreach( $witch->daughters() as $daugther )
        {
            $result =  $this->recursiveSearchById( $daugther, $id );
            if( $result ){
                return $result;
            }
        }

        return null;
    }

    /**
     * @param int $cauldronId
     * @return Witch[]
     */
    function searchByCauldronId( int $cauldronId ): array
    {
        $return = [];
        foreach( $this->witches as $witch )
        {
            if( $witch->cauldronId == $cauldronId ){
                $return[] = $witch;
            }

            $witchRoot  = $witch;
            while( $witchRoot->mother ){
                $witchRoot    = $witchRoot->mother;
            }

            if( $witchRoot->cauldronId == $cauldronId ){
                $return[] = $witchRoot;
            }
            
            $return = array_merge( 
                $return, 
                $this->recursiveSearchByCauldronId( $witchRoot, $cauldronId )
            );
        }
        
        return $return;
    }

    /**
     * @param Witch $witch
     * @param int $cauldronId
     * @return Witch[]
     */
    private function recursiveSearchByCauldronId( Witch $witch, int $cauldronId ): array
    {
        $return = [];
        foreach( $witch->daughters() as $daugther )
        {
            if( $daugther->cauldronId == $cauldronId ){
                $return[] = $daugther;
            }
            
            $return = array_merge( 
                $return, 
                $this->recursiveSearchByCauldronId( $daugther, $cauldronId )
            );
        }
        
        return $return;
    }
}
