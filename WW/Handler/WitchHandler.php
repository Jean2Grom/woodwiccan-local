<?php 
namespace WW\Handler;

use WW\Cauldron;
use WW\WoodWiccan;
use WW\Witch;
use WW\DataAccess\WitchDataAccess as DataAccess;
use WW\Datatype\ExtendedDateTime;

class WitchHandler
{
    const MAX_INT_ID_LENGTH = 10;

    /**
     * Witch factory class, implements witch whith data provided
     * @param WoodWiccan $ww
     * @param array $data
     * @return Witch
     */
    static function instanciate(  WoodWiccan $ww, array $data ): Witch
    {
        $witch      = new Witch();
        $witch->ww  = $ww;
        
        foreach( Witch::FIELDS as $field ){
            $witch->properties[ $field ] = NULL;
        }

        $witch->properties = $data;
        
        self::readProperties( $witch );
        
        if( $witch->depth === 0 ){
            $witch->mother = null;
        }
        
        return $witch;
    }


    /**
     * Update Object properties based of object var "properties"
     * @var Witch $witch
     * @return void
     */
    static function readProperties( Witch $witch ): void
    {
        $witch->id = null;
        if( isset($witch->properties['id']) 
            && ctype_digit(strval($witch->properties['id'])) 
        ){
            $witch->id = (int) $witch->properties['id'];
        }

        $witch->name = null;
        if( isset($witch->properties['name']) ){
            $witch->name = $witch->properties['name'];
        }
        
        $witch->data = null;
        if( isset($witch->properties['data']) ){
            $witch->data = $witch->properties['data'];
        }

        $witch->site = null;
        if( isset($witch->properties['site']) ){
            $witch->site = $witch->properties['site'];
        }

        $witch->url = null;
        if( isset($witch->properties['url']) ){
            $witch->url = $witch->properties['url'];
        }

        $witch->statusLevel = 0;
        $witch->status      = null;
        if( isset($witch->properties['status']) 
            && ctype_digit(strval($witch->properties['status'])) 
        ){
            $witch->statusLevel = (int) $witch->properties['status'];
        }
        
        $witch->invoke = null;
        if( isset($witch->properties['invoke']) ){
            $witch->invoke = $witch->properties['invoke'];
        }
        
        $witch->cauldronId = null;
        if( isset($witch->properties['cauldron']) 
            && ctype_digit(strval($witch->properties['cauldron'])) 
        ){
            $witch->cauldronId = (int) $witch->properties['cauldron'];
        }

        if( $witch->cauldronId !== $witch->cauldron?->id ){
            $witch->cauldron   = null;
        }

        $witch->cauldronPriority = null;
        if( isset($witch->properties['cauldron_priority']) 
            && ctype_digit(strval($witch->properties['cauldron_priority'])) 
        ){
            $witch->cauldronPriority = (int) $witch->properties['cauldron_priority'];
        }
        
        $witch->context = null;
        if( isset($witch->properties['context']) ){
            $witch->context = $witch->properties['context'];
        }
        
        $witch->datetime = null;
        if( isset($witch->properties['datetime']) ){
            $witch->datetime = new ExtendedDateTime($witch->properties['datetime']);
        }
        
        $witch->priority = 0;
        if( isset($witch->properties['priority']) 
            && ctype_digit(strval($witch->properties['priority'])) 
        ){
            $witch->priority = (int) $witch->properties['priority'];
        }

        $witch->position = [];
        for( $i=1; $i<=$witch->ww->depth; $i++ ){
            if( isset($witch->properties[ 'level_'.$i ]) 
                && ctype_digit( strval($witch->properties[ 'level_'.$i ]) ) 
            ){
                $witch->position[ $i ] = (int) $witch->properties[ 'level_'.$i ];
            }
        }
        $witch->depth = count( $witch->position );
        
        return;
    }


    /**
     * Update var "properties" (database direct fields) based on Object current state 
     * @var Witch $witch
     * @return void
     */
    static function writeProperties( Witch $witch ): void
    {
        $id = null;
        if( isset($witch->id) && is_int($witch->id) ){
            $id = $witch->id;
        }

        $witch->properties = [
            'id'                => $id,
            'name'              => $witch->name ?? null,
            'data'              => $witch->data ?? null,
            'site'              => $witch->site ?? null,
            'url'               => $witch->url ?? null,
            'status'            => $witch->status ?? 0,
            'invoke'            => $witch->invoke ?? null,
            'cauldron'          => $witch->cauldron?->id ?? $witch->cauldronId ?? null,
            'cauldron_priority' => $witch->cauldronPriority ?? 0,
            'context'           => $witch->context ?? null,
            'datetime'          => $witch->datetime?->format('Y-m-d H:i:s') ?? null,
            'priority'          => $witch->priority ?? 0,
        ];

        for( $i=1; $i<=$witch->ww->depth; $i++ ){
            $witch->properties[ 'level_'.$i ] = $witch->position($i);
        }

        return;
    }

    /**
     * Witch factory class, reads witch data associated whith id
     * @param WoodWiccan $ww
     * @param int $id   witch id to create
     * @return Witch|false implemented Witch object, boolean false if data not found
     */
    static function fetch( WoodWiccan $ww, int $id ): Witch|false
    {
        $data = DataAccess::fetch( $ww, $id );
        
        if( empty($data) ){
            return false;
        }
        
        return self::instanciate( $ww, $data );
    }
    
    
    /**
     * Check new urls validity, add a suffix if it's not
     * @param WoodWiccan $ww
     * @param string $site
     * @param array $urlArray
     * @param ?int $excludedId
     * @return ?string
     */
    static function checkUrls( WoodWiccan $ww, string $site, array $urlArray, ?int $excludedId=null ): ?string
    {
        if( !$urlArray ){
            return null;
        }

        $result = DataAccess::getUrlsData($ww, $site, $urlArray, $excludedId);
        
        if( !$result ){
            return array_values($urlArray)[0] ?? null;
        }

        $indices        = [];
        foreach( $urlArray as $key => $url )
        {
            $usages = [ 0 ];
            $regex  = '/^'. str_replace('/', '\/', $url).'(?:-\d+)?$/';

            foreach( $result as $row )
            {
                $match = [];
                preg_match($regex, $row['url'], $match);

                if( $match ){
                    if( $row['url'] === $url ){
                        $usages[] = 1;
                    }
                    else {
                        $usages[] = (int) substr( $row['url'], (1 + strrpos($row['url'], '-')) );
                    }
                }
            }
            
            $indices[ $key ] = max($usages);
        }

        $minIndice      = null;
        $returnedUrl    = null;
        foreach( $urlArray as $key => $url )
        {
            if( is_null($minIndice) || $indices[ $key ] < $minIndice )
            {
                $minIndice = $indices[ $key ];
                if( $minIndice === 0 ){
                    $returnedUrl = $url;
                }
                else {
                    $returnedUrl = $url.'-'.($minIndice + 1);
                }
            }
        }

        return $returnedUrl;
    }
    

    /**
     * If with creation is at the top leaf of matriarcal arborescence,
     * Add a new level to witches genealogical tree
     * @param WoodWiccan $ww
     * @return bool
     */
    static function addLevel( WoodWiccan $ww ): bool
    {
        $depth = DataAccess::increasePlateformDepth($ww);
        if( $depth == $ww->depth ){
            return false;
        }
        
        $ww->depth = $depth;
        
        return true;
    }

    /**
     * Reorder a witch array based on priority
     * @param array $witchesList
     * @return array
     */
    static function reorderWitches( array $witchesList ): array
    {
        $orderedWitchesIds = [];
        $refMaxPossiblrPriority = 1;
        for( $i=1; $i <= self::MAX_INT_ID_LENGTH; $i++ ){
            $refMaxPossiblrPriority = $refMaxPossiblrPriority*10;
        }

        foreach( $witchesList as $witchItem ) 
        {
            $priority = $refMaxPossiblrPriority - $witchItem->priority;
            
            for( $i=strlen($priority); $i <= self::MAX_INT_ID_LENGTH; $i++ ){
                $priority = "0".$priority;
            }
            
            $orderIndex = $priority."__".mb_strtolower($witchItem->name)."__".$witchItem->id;
            $orderedWitchesIds[ $orderIndex ] = $witchItem->id;
        }
        
        ksort($orderedWitchesIds);
        
        $orderedWitches = [];
        foreach( $orderedWitchesIds as $orderedWitchId ){
            $orderedWitches[ $orderedWitchId ] = $witchesList[ $orderedWitchId ];
        }
        
        return $orderedWitches;
    }

    /**
     * 
     */
    static function recursiveTree( Witch $witch, $sitesRestrictions=false, ?int $currentId=null, $maxStatus=false, ?array $hrefCallBack=null )
    {
        if( !is_null($witch->site) 
            && is_array($sitesRestrictions)
            && !in_array($witch->site, $sitesRestrictions) ){
            return false;
        }

        $path       = false;
        if( !is_null($currentId) && $currentId == $witch->id ){
            $path = true;
        }
        
        $daughters  = [];
        if( $witch->id ){
            foreach( $witch->daughters() as $daughterWitch )
            {
                if( $maxStatus !== false && $daughterWitch->statusLevel > $maxStatus ){
                    continue;
                }

                $subTree        = self::recursiveTree( $daughterWitch, $sitesRestrictions, $currentId, $maxStatus, $hrefCallBack );
                if( $subTree === false ){
                    continue;
                }

                if( $subTree['path'] ){
                    $path = true;
                }

                $daughters[ $subTree['id'] ]    = $subTree;
            }
        }

        $tree   = [ 
            'id'                => $witch->id,
            'name'              => $witch->name,
            'site'              => (string) $witch->site ?? "",
            'description'       => $witch->data,
            'cauldron'          => $witch->hasCauldron(),
            'invoke'            => $witch->hasInvoke(),
            'daughters'         => $daughters,
            'daughters_orders'  => array_keys( $daughters ),
            'path'              => $path,
        ];

        if( $hrefCallBack ){
            $tree['href'] = call_user_func( $hrefCallBack, $witch );
        }
        
        return $tree;
    }

    /**
     * Mother witch manipulation
     * @param Witch $descendant
     * @param Witch $mother
     */
    static function setMother(  Witch $descendant, Witch $mother )
    {
        self::unsetMother( $descendant );

        $descendant->mother = $mother;
        if( !in_array($descendant->id, array_keys($mother->daughters ?? [])) ){
            self::addDaughter( $mother, $descendant );
        }
        
        return;
    }
    
    /**
     * Mother witch manipulation
     * @param Witch $witch
     */
    static function unsetMother( Witch $witch )
    {
        if( !empty($witch->mother) && !empty($witch->mother->daughters[ $witch->id ]) ){
            unset($witch->mother->daughters[ $witch->id ]);
        }
        
        $witch->mother = null;
        
        return;
    }
    
    /**
     * Daughter witches manipulation
     * @param Witch $mother
     * @param Witch $daughter
     */
    static function addDaughter( Witch $mother, Witch $daughter ){
        return self::addDaughters( $mother, [$daughter] );
    }
    
    /**
     * Daughter witches manipulation
     * @param Witch $mother
     * @param Witch $daughter
     */
    static function addDaughters( Witch $mother, array $daughters )
    {
        $reorder = false;
        foreach( $daughters as $daughter ){
            if( is_object($daughter) && get_class($daughter) === "WW\\Witch" )
            {
                $mother->daughters[ $daughter->id ] = $daughter;
                $daughter->mother                   = $mother;
                $reorder                            = true;
            }
        }
        
        if( $reorder ){
            $mother->daughters = self::reorderWitches( $mother->daughters );
        }

        return;
    }
    

    /**
     * Daughter witches manipulation
     * @param Witch $mother
     * @param Witch $daughter
     * @return Witch
     */
    static function removeDaughter( Witch $mother, Witch $daughter ): Witch
    {
        if( !empty($mother->daughters[ $daughter->id ]) ){
            unset($mother->daughters[ $daughter->id ]);
        }
        
        if( $daughter->mother->id == $mother->id ){
            $daughter->mother = null;
        }
        
        return $mother;
    }


    /**
     * Sister witches manipulation
     * @param Witch $witch
     * @param Witch $sister
     * @return Witch
     */
    static function addSister( Witch $witch, Witch $sister ): Witch
    {
        if( empty($witch->sisters) ){
            $witch->sisters = [];
        }
        
        if( $sister->id != $witch->id ){
            $witch->sisters[ $sister->id ] = $sister;
        }
        
        return $witch;
    }

    /**
     * Sister witches manipulation
     * @param Witch $witch
     * @param Witch $sister
     * @return Witch
     */
    static function removeSister( Witch $witch, Witch $sister ): Witch
    {
        if( !empty($witch->sisters[ $sister->id ]) ){
            unset($witch->sisters[ $sister->id ]);
        }
        
        if( !empty($sister->sisters[ $witch->id ]) ){
            unset($sister->sisters[ $witch->id ]);
        }
        
        return $witch;
    }

    static function search( WoodWiccan $ww, array $params )
    {
        $result = DataAccess::search( $ww, $params );

        if( $result === false ){
            return false;
        }

        $witches = [];
        foreach( $result as $row )
        {
            $id             =   $row['id'];
            $witches[ $id ] =   $ww->cairn->searchById( $id ) ?? self::instanciate( $ww, $row );
        }
        
        return $witches;    
    }

    static function removeCauldron( Witch $witch )
    {
        $witch->cauldron     = null;
        $witch->cauldronId   = null;
        
        return $witch->edit([
            'cauldron'          => null, 
            'cauldron_priority' => 0
        ]);
    }


    static function save( Witch $witch ): bool
    {
        if( !$witch->exist() )
        {
            self::writeProperties($witch); 

            $params = $witch->properties;
            if( isset($params['id']) ){
                unset($params['id']);
            }

            if( !$result = DataAccess::insert($witch->ww, $params) ){
                return false;
            }

            $witch->id = (int) $result;
        }
        else 
        {
            $properties = $witch->properties;

            self::writeProperties($witch);

            $params = array_diff_assoc($witch->properties, $properties);
            if( isset($params['id']) ){
                unset($params['id']);
            }

            if( count($params) ){
                DataAccess::update( $witch->ww, $params, ['id' => $witch->id] );
            }
        }
        
        return true;
    }
}