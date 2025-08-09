<?php
namespace WW;

use WW\DataAccess\Summoning;

/**
 * Class that handles witch summoning and modules invocation
 * 
 * @author Jean2Grom
 */
class Cairn 
{
    const DEFAULT_WITCH = "current";
    
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
        
        $ids    = [];
        $urls   = [];
        $user   = [];        
        foreach( $rawConfiguration as $refWitchName => $refWitchSummoning )
        {
            if( !empty($refWitchSummoning['id']) )
            {
                $index = 'id_'.$refWitchSummoning['id'];
                
                $entries    = [];
                $modules    = [];
                $craft      = $refWitchSummoning['craft'] ?? true;
                
                $parents    = false;
                $sisters    = false;
                $children   = false;
                if( isset($ids[ $index ]) )
                {
                    $entries    = $ids[ $index ]['entries'];
                    $modules    = $ids[ $index ]['modules'];
                    $craft      = $craft || $ids[ $index ]['craft'];
                    
                    $parents    = $ids[ $index ]['parents'];
                    $sisters    = $ids[ $index ]['sisters'];
                    $children   = $ids[ $index ]['children'];
                }
                
                $ids[ $index ] = [
                    'id'        => (int) $refWitchSummoning['id'],
                    'entries'   => array_merge($entries, [ $refWitchName => $refWitchSummoning['invoke'] ?? false ]),
                    'modules'   => (empty($refWitchSummoning['module']) || $modules === false)? false:  array_merge($modules, [ $refWitchSummoning['module'] ]),
                    'craft'     => $craft,
                    'parents'   => $arboConf( $parents, $refWitchSummoning['parents'] ?? false ),
                    'sisters'   => $arboConf( $sisters, $refWitchSummoning['sisters'] ?? false ),
                    'children'  => $arboConf( $children, $refWitchSummoning['children'] ?? false ),
                ];
            }
            
            if( !empty($refWitchSummoning['get']) )
            {
                $paramValue = $website->ww->request->param($refWitchSummoning['get'], 'get', FILTER_VALIDATE_INT);
                if( $paramValue )
                {
                    $index = 'id_'.$paramValue;
                    
                    $entries    = [];
                    $modules    = [];
                    $craft      = $refWitchSummoning['craft'] ?? true;

                    $parents    = false;
                    $sisters    = false;
                    $children   = false;
                    if( isset($ids[ $index ]) )
                    {
                        $entries    = $ids[ $index ]['entries'];
                        $modules    = $ids[ $index ]['modules'];
                        $craft      = $craft || $ids[ $index ]['craft'];

                        $parents    = $ids[ $index ]['parents'];
                        $sisters    = $ids[ $index ]['sisters'];
                        $children   = $ids[ $index ]['children'];
                    }

                    $ids[ $index ] = [
                        'id'        => (int) $paramValue,
                        'entries'   => array_merge($entries, [ $refWitchName => $refWitchSummoning['invoke'] ?? false ]),
                        'modules'   => (empty($refWitchSummoning['module']) || $modules === false)? false:  array_merge($modules, [ $refWitchSummoning['module'] ]),
                        'craft'     => $craft,
                        'parents'   => $arboConf( $parents, $refWitchSummoning['parents'] ?? false ),
                        'sisters'   => $arboConf( $sisters, $refWitchSummoning['sisters'] ?? false ),
                        'children'  => $arboConf( $children, $refWitchSummoning['children'] ?? false ),
                    ];
                }
            }
            
            if( !empty($refWitchSummoning['url']) )
            {
                $urlData = $website->getUrlSearchParameters();
                if( !empty($refWitchSummoning['site']) ){
                    $urlData['site'] = $refWitchSummoning['site'];
                }
                if( is_string($refWitchSummoning['url']) ){
                    $urlData['url'] = $refWitchSummoning['url'];
                }
                
                $index = md5($urlData['site'].':'.$urlData['url']);
                
                $entries    = [];
                $modules    = [];
                $craft      = $refWitchSummoning['craft'] ?? true;
                
                $parents    = false;
                $sisters    = false;
                $children   = false;
                if( isset($urls[ $index ]) )
                {
                    $entries    = $urls[ $index ]['entries'];
                    $modules    = $urls[ $index ]['modules'];
                    $craft      = $craft || $urls[ $index ]['craft'];
                    
                    $parents    = $urls[ $index ]['parents'];
                    $sisters    = $urls[ $index ]['sisters'];
                    $children   = $urls[ $index ]['children'];
                }
                
                $urls[ $index ] = [
                    'site'      => $urlData['site'],
                    'url'       => $urlData['url'],
                    'entries'   => array_merge($entries, [ $refWitchName => $refWitchSummoning['invoke'] ?? false ]),
                    'modules'   => (empty($refWitchSummoning['module']) || $modules === false)? false:  array_merge($modules, [ $refWitchSummoning['module'] ]),
                    'craft'     => $craft,
                    'parents'   => $arboConf( $parents, $refWitchSummoning['parents'] ?? false ),
                    'sisters'   => $arboConf( $sisters, $refWitchSummoning['sisters'] ?? false ),
                    'children'  => $arboConf( $children, $refWitchSummoning['children'] ?? false ),
                ];
            }
            
            if( !empty($refWitchSummoning['user']) )
            {
                $entries    = [];
                $modules    = [];
                $craft      = $refWitchSummoning['craft'] ?? true;
                
                $parents    = false;
                $sisters    = false;
                $children   = false;
                if( !empty($user) )
                {
                    $entries    = $user['entries'];
                    $modules    = $user['modules'];
                    $craft      = $craft || $user['craft'];
                    
                    $parents    = $user['parents'];
                    $sisters    = $user['sisters'];
                    $children   = $user['children'];
                }
                
                $user = [
                    'entries'   => array_merge($entries, [ $refWitchName => $refWitchSummoning['invoke'] ?? false ]),
                    'modules'   => (empty($refWitchSummoning['module']) || $modules === false)? false:  array_merge($modules, [ $refWitchSummoning['module'] ]),
                    'craft'     => $craft ,
                    'parents'   => $arboConf( $parents, $refWitchSummoning['parents'] ?? false ),
                    'sisters'   => $arboConf( $sisters, $refWitchSummoning['sisters'] ?? false ),
                    'children'  => $arboConf( $children, $refWitchSummoning['children'] ?? false ),
                ];
            }
        }
        
        $configuration = [
            'user'  => $user,
            'url'   => $urls,
            'id'    => $ids,
        ];
        
        foreach( $configuration as $type => $typeConfiguration ){
            foreach( $typeConfiguration as $refWitchName => $refWitchSummoning ){
                if( !empty($refWitchSummoning['sisters']) && empty($refWitchSummoning['parents']) ){
                    $configuration[ $type ][ $refWitchName ]['parents'] = [
                        "depth" => 1,
                        "craft" => false
                    ];
                }
            }
        }
        
        return $configuration;
    }
    
    
    function summon()
    {
        $this->addWitches( Summoning::witches($this->ww, $this->configuration) );
        $this->addCauldrons( Summoning::cauldrons($this->ww, $this->configuration) );

        return $this;
    }
    

    function sabbath()
    {
        foreach( $this->configuration as $type => $typeConfiguration )
        {
            if( $type === 'user' ){
                $witchRefConfJoins = [ 'user' => $typeConfiguration ];
            }
            else {
                $witchRefConfJoins = $typeConfiguration;
            }
            
            foreach( $witchRefConfJoins as $witchConf ){
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
