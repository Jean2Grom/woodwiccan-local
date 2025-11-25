<?php 
namespace WW\Handler;

use WW\WoodWiccan;
use WW\Witch;
use WW\DataAccess\WitchDataAccess as DataAccess;
use WW\Datatype\ExtendedDateTime;
 
class WitchHandler
{
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

        $witch->site    = null;
        $witch->website = null;
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
        $witch->properties = [
            // 'id'                => $id,
            'name'              => $witch->name ?? null,
            'data'              => $witch->data ?? null,
            'site'              => $witch->site ?? null,
            'url'               => $witch->url ?? null,
            'status'            => $witch->statusLevel ?? 0,
            'invoke'            => $witch->invoke ?? null,
            'cauldron'          => $witch->cauldron?->id ?? $witch->cauldronId ?? null,
            'cauldron_priority' => $witch->cauldronPriority ?? 0,
            'context'           => $witch->context ?? null,
            // 'datetime'          => $witch->datetime?->format('Y-m-d H:i:s') ?? null,
            'priority'          => $witch->priority ?? 0,
        ];

        if( isset($witch->id) && is_int($witch->id) ){
            $witch->properties['id'] = $witch->id;
        }

        if( $dateTime = $witch->datetime?->format('Y-m-d H:i:s') ){
            $witch->properties['dateTime'] = $dateTime;
        }
        
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
        foreach( $urlArray as $key => $url ){
            if( is_null($minIndice) || $indices[ $key ] < $minIndice )
            {
                $minIndice      = $indices[ $key ];
                $returnedUrl    = $url;

                if( $minIndice > 0 )
                {
                    if( $url !== "" ){
                        $returnedUrl .= '-';
                    }

                    $returnedUrl .= ($minIndice + 1);
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
     * @return Witch[]
     */
    static function reorderWitches( array $witchesList ): array
    {
        $prioritiesArray = [];
        foreach( $witchesList as $witch )
        {
            $priority   = $witch->priority ?? 0;
            $name       = $witch->name ?? "";

            $prioritiesArray[ $priority ]           = $prioritiesArray[ $priority ] ?? [];
            $prioritiesArray[ $priority ][ $name ]  = $prioritiesArray[ $priority ][ $name ] ?? [];

            $prioritiesArray[ $priority ][ $name ][]    = $witch;
        }
        krsort($prioritiesArray);

        $return = [];
        foreach( $prioritiesArray as $priority => $alphaOrderArray )
        {
            ksort($alphaOrderArray);
            foreach( $alphaOrderArray as $name => $witches ){
                foreach( $witches as $witch ){
                    $return[] = $witch;
                }
            }
        }
        
        return $return;
    }

    /**
     * Witch's mother manipulation
     * @param Witch $descendant
     * @param Witch $mother
     * @return Witch $descendant
     */
    static function setMother( Witch $descendant, Witch $mother ): Witch
    {
        self::unsetMother( $descendant );

        $descendant->mother = $mother;
        if( !in_array($descendant, $mother->daughters) ){
            self::addDaughter( $mother, $descendant );
        }
        
        return $descendant;
    }
    
    /**
     * Witch's mother manipulation
     * @param Witch $witch
     * @return Witch 
     */
    static function unsetMother( Witch $witch ): Witch
    {
        if( $witch->mother ){
            self::removeDaughter( $witch->mother, $witch );
        }
        return $witch;
    }
    
    /**
     * Witch's daughter manipulation 
     * @param Witch $mother
     * @param Witch $daughter
     */
    static function addDaughter( Witch $mother, Witch $daughter ){
        return self::addDaughters( $mother, [$daughter] );
    }
    
    /**
     * Witch's daughters manipulation 
     * @param Witch $mother
     * @param Witch[] $daughter
     * @return Witch $mother
     */
    static function addDaughters( Witch $mother, array $daughters ): Witch
    {
        $mother->daughters = $mother->daughters ?? [];
        
        $reorder = false;
        foreach( $daughters as $daughter ){
            if( is_object($daughter) 
                && get_class($daughter) === Witch::class 
            ){
                $mother->daughters[]    = $daughter;
                $daughter->mother       = $mother;
                $reorder                = true;
            }
        }
        
        if( $reorder ){
            $mother->daughters = self::reorderWitches( $mother->daughters );
        }

        return $mother;
    }
    

    /**
     * Witch's daughter manipulation 
     * @param Witch $mother
     * @param Witch $daughter
     * @return Witch
     */
    static function removeDaughter( Witch $mother, Witch $daughter ): Witch
    {
        if( $key = array_search($daughter, $mother->daughters) ){
            unset($mother->daughters[ $key ]);
        }

        if( $daughter->mother === $mother ){
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

    /**
     * 
     */
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


    /**
     * Save witch in BDD
     * @param Witch $witch 
     * @return ?bool true for success, false for failure, null for no effect
     */
    static function save( Witch $witch ): ?bool
    {
        if( !$witch->exist() )
        {
            self::writeProperties($witch); 

            $params = $witch->properties;
            if( isset($params['id']) ){
                unset($params['id']);
            }
            if( isset($params['datetime']) ){
                unset($params['datetime']);
            }

            if( !$result = DataAccess::insert($witch->ww, $params) ){
                return false;
            }

            $witch->id = (int) $result;

            return true;
        }

        $properties         = $witch->properties;
        $exitingProperties  = array_keys($properties);

        self::writeProperties($witch);

        $params = [];
        foreach( $witch->properties as $key => $value ){
            if( !in_array($key, $exitingProperties)
                || $value !== $properties[ $key ] 
            ){
                $params[ $key ] = $value;
            }
        }

        if( isset($params['id']) ){
            unset($params['id']);
        }
        
        if( count($params) === 0 ){
            return null;
        }

        $result = DataAccess::update( 
            $witch->ww, 
            $params, 
            [ 'id' => $witch->id ] 
        );

        if( $result === 0 ){
            return null;
        }

        return (bool) $result;
    }

    /**
     * Get ancestors from BDD (make a request)
     * 
     * @param Witch $witch
     * @return Witch|false
     */
    static function fetchAncestors( Witch $witch ): Witch|false
    {
        $conf = [
            "match"     => ['id'    => $witch->id],
            "entries"   => ['fetch' => false],
            "modules"   => [],
            "craft"     => false,
            "parents"   => [
                "depth"    => '*',
                "craft"    => false,
            ],
            "sisters"   => false,
            "children"  => false,
        ];
        
        $witches = DataAccess::summon( $witch->ww, [$conf] );

        return $witches['fetch']?->mother ?? false;
    }

    /**
     * Get descendants from BDD (make a request)
     * 
     * @param Witch $witch
     * @return Witch[]
     */
    static function fetchDescendants( Witch $witch ): array
    {
        $conf = [
            "match"     => ['id'    => $witch->id],
            "entries"   => ['fetch' => false],
            "modules"   => [],
            "craft"     => false,
            "parents"   => false,
            "sisters"   => false,
            "children"  => [
                "depth"     => '*',
                "craft"     => false,
            ],
        ];

        $witches = DataAccess::summon( $witch->ww, [$conf] );

        return $witches['fetch']?->daughters ?? [];
    }

    /**
     * 
     */
    static function setPriorities( Witch $witch, array $idOrder ): false|int
    {
        $params     = [];
        $conditions = [];
        $priority   = 0;
        $interval   = 100;
        foreach( array_reverse($idOrder) as $id )
        {
            $daughter           =   $witch->daughter( $id );
            $daughter->priority =   $priority;
            $priority           +=  $interval;

            $params[]       = [ 'priority' => $priority ];
            $conditions[]   = [ 'id' => $id ];
        }

        $return = DataAccess::updates($witch->ww, $params,  $conditions);

        if( $return === false ){
            return false;
        }
        elseif( $return ){
            $witch->daughters = self::reorderWitches( $witch->daughters );
        }
        
        return $return;
    }

    /**
     * Generate the "position" attribute
     */
    static function position( Witch $witch ): void
    {
        // Case ROOT
        if( !$witch->mother )
        {
            $witch->position = [];
            $witch->depth    = 0;
        }
        else 
        {
            $witch->position = $witch->mother->position();
            $witch->depth    = $witch->mother->depth + 1;

            if( $witch->depth > $witch->ww->depth )
            {
                self::addLevel($witch->ww);
                $witch->position[ $witch->depth ] = 1;
            }
            else {
                $witch->position[ $witch->depth ] = DataAccess::getNewDaughterIndex( 
                    $witch->ww, 
                    $witch->position ?? [] 
                );
            }
        }

        return;
    }

    /**
     * 
     */
    static function listDescendantsWitches( Witch $witch ): array
    {
        $return = [ $witch ];
        foreach( $witch->daughters() as $daughter ){
            $return = array_merge(
                $return,
                self::listDescendantsWitches( $daughter )
            );
        }

        return $return;
    }
    
    static function delete( Witch $witch ): bool
    {
        $descendants =  self::listDescendantsWitches( $witch );
        
        $deleteIds = [];
        foreach( $descendants as $w )
        {
            $deleteIds[] = $w->id;

            if( $w->hasCauldron() )
            {
                if( !$w->cauldron()->witches() ){
                    continue;
                }

                $witchToRemoveKey = array_search($witch, $w->cauldron()->witches());

                if( $witchToRemoveKey === false ){
                    continue;
                }
                unset($w->cauldron()->witches[ $witchToRemoveKey ]);
                
                if( !$w->cauldron()->witches ){
                    $w->cauldron()->delete();
                }
            }
        }        
        
        return DataAccess::delete($witch->ww, $deleteIds);
    }


}