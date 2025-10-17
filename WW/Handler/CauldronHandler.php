<?php 
namespace WW\Handler;

use WW\WoodWiccan;
use WW\Cauldron;
use WW\Cauldron\Ingredient;
use WW\DataAccess\CauldronDataAccess AS DataAccess;
use WW\Witch;

class CauldronHandler
{
    const STATUS_ARRAY = [
        'content',
        'draft',
        'archive',
    ];


    /**
     * Fetch cauldrons from configuration array
     * @var WoodWiccan $ww
     * @var array $configuration
     */
    static function fetch( WoodWiccan $ww, array $configuration, bool $getWitches=true )
    {
        $result = DataAccess::cauldronRequest($ww, $configuration, $getWitches);
        
        if( $result === false ){
            return false;
        }
        
        return self::instanciate($ww, $configuration, $result);
    }
    

    /**
     * PRIVATE instanciate Cauldrons and Ingredients from configuration and data access results
     * @var WoodWiccan $ww
     * @var array $configuration
     * @var array $result
     * @return Cauldron[] 
     */
    private static function instanciate( WoodWiccan $ww, array $configuration, array $result ): array
    {
        $return         = [];
        $cauldronsList  = [];
        $witchesList    = [];
        $depthArray     = [];
        foreach( range(0, $ww->cauldronDepth) as $d ){
            $depthArray[ $d ] = [];
        }
        
        foreach( $result as $row )
        {
            $id                     = (int) $row['id'];
            $cauldronsList[ $id ]   = $cauldronsList[ $id ] ?? self::createFromData( $ww, $row );
            
            IngredientHandler::createFromDBRow( $cauldronsList[ $id ], $row );
            if( !in_array($id, $depthArray[ $cauldronsList[ $id ]->depth ]) ){
                $depthArray[ $cauldronsList[ $id ]->depth ][] = $id;
            }

            foreach( $configuration as $conf ){
                if( $conf === $id ){
                    $return[ $conf ] = $cauldronsList[ $id ];
                }
            }

            if(  in_array('user', $configuration) 
                &&  empty($return['user'])
                &&  $row['i_name'] === 'user__connexion'
                &&  $row['i_value'] === $ww->user->id 
            ){
                $return['user'] = $cauldronsList[ $id ];
            }

            // Witch part
            $witchId = $row['w_id'] ?? null;
            if( !$witchId || !empty($witchesList[ $witchId ]) ){
                continue;
            }

            $witch = $ww->cairn->searchById( $witchId );
            if( !$witch )
            {
                $witchData = [];
                foreach( Witch::FIELDS as $field ){
                    $witchData[ $field ] = $row[ "w_".$field ];
                }
                
                foreach( range(1, $ww->depth) as $i ){
                    $witchData[  "level_".$i ] = $row[ "w_level_".$i ];
                }
                
                $witch = WitchHandler::instanciate($ww, $witchData);
            }
            
            $witchesList[ $witchId ] = $witch;
        }
        
        // Link witches and cauldron objects
        foreach( $witchesList as $witch ){
            if( isset($cauldronsList[ $witch->cauldronId ]) )
            {
                $witch->cauldron = $cauldronsList[ $witch->cauldronId ];
                $cauldronsList[ $witch->cauldronId ]->witches = array_replace(
                    $cauldronsList[ $witch->cauldronId ]->witches ?? [],
                    [ $witch->id => $witch ]
                );
            }
        }
        foreach( $cauldronsList as $cauldron ){ 
            $cauldron->orderWitches();
        }
        
        for( $i=0; $i < $ww->cauldronDepth; $i++ ){
            foreach( $depthArray[ $i ] as $potentialParentId ){
                foreach( $depthArray[ ($i+1) ] as $potentialDaughterId ){
                    if( self::isParentPosition( 
                        $cauldronsList[ $potentialParentId ]->position, 
                        $cauldronsList[ $potentialDaughterId ]->position 
                    ) ){
                        self::setParenthood($cauldronsList[ $potentialParentId ], $cauldronsList[ $potentialDaughterId ]);
                    }
                }
            }
        }

        return $return;
    }


    private static function isParentPosition( array $potentialParentPosition, array $potentialChildPosition ): bool
    {
        $potentialParentDepth = count($potentialParentPosition);
        if( count($potentialChildPosition) != $potentialParentDepth + 1 ){
            return false;
        }
        
        $isParent = true;
        for( $i=1; $i<=$potentialParentDepth; $i++ ){
            if( $potentialParentPosition[ $i ] != $potentialChildPosition[ $i ] )
            {
                $isParent = false;
                break;
            }
        }

        return $isParent;
    }


    /**
     * Cauldron factory class, implements Cauldron with data provided
     * @param WoodWiccan $ww
     * @param array $data
     * @return Cauldron
     */
    static function createFromData(  WoodWiccan $ww, array $data ): Cauldron
    {
        $class = $ww->configuration->recipe( $data['recipe'] )?->class;
        if( $class ){
            $cauldron = new $class();
        }
        else {
            $cauldron = new Cauldron();
        }
        
        $cauldron->ww = $ww;
        
        foreach( Cauldron::FIELDS as $field ){
            $cauldron->properties[ $field ] = $data[ $field ] ?? null;
        }
        
        for( $i=1; $i <= $ww->cauldronDepth; $i++ ){
            $cauldron->properties[ 'level_'.$i ] = $data[ 'level_'.$i ] ?? null;
        }

        self::readProperties( $cauldron );

        return $cauldron;
    }  



    /**
     * Update  Object current state based on var "properties" (database direct fields) 
     * @var Cauldron $cauldron
     * @return void
     */
    static function readProperties( Cauldron $cauldron ): void
    {
        $cauldron->id = null;
        if( isset($cauldron->properties['id']) 
            && ctype_digit(strval($cauldron->properties['id'])) 
        ){
            $cauldron->id = (int) $cauldron->properties['id'];
        }
        
        $cauldron->targetID = null;
        if( isset($cauldron->properties['target']) 
            && ctype_digit(strval($cauldron->properties['target'])) 
        ){
            $cauldron->targetID = (int) $cauldron->properties['target'];
        }

        if( $cauldron->targetID !== $cauldron->target?->id ){
            $cauldron->target   = null;
        }

        $cauldron->status = null;
        if( isset($cauldron->properties['status']) 
            && in_array( $cauldron->properties['status'], 
                [Cauldron::STATUS_PUBLISHED, Cauldron::STATUS_DRAFT, Cauldron::STATUS_ARCHIVED] )
        ){
            $cauldron->status = $cauldron->properties['status'];
        }        
        
        $cauldron->name = null;
        if( isset($cauldron->properties['name']) ){
            $cauldron->name = $cauldron->properties['name'];
        }
        
        $cauldron->recipe = null;
        if( isset($cauldron->properties['recipe']) ){
            $cauldron->recipe = $cauldron->properties['recipe'];
        }
        
        $cauldron->data = null;
        if( isset($cauldron->properties['data']) )
        {
            $cauldron->data = json_decode( $cauldron->properties['data'] );
            $cauldron->properties['data'] = json_encode($cauldron->data);
        }

        $cauldron->priority = 0;
        if( isset($cauldron->properties['priority']) 
            && ctype_digit(strval( $cauldron->properties['priority'] )) 
        ){
            $cauldron->priority = (int) $cauldron->properties['priority'];
        }
        
        $cauldron->position    = [];
        for( $i=1; $i<=$cauldron->ww->cauldronDepth; $i++ ){
            if( isset($cauldron->properties[ 'level_'.$i ]) 
                && ctype_digit(strval( $cauldron->properties['level_'.$i] )) 
            ){
                $cauldron->position[ $i ] = (int) $cauldron->properties[ 'level_'.$i ];
            }
        }
        $cauldron->depth = count( $cauldron->position ); 
        
        return;
    }


    /**
     * Update var "properties" (database direct fields) based on Object current state 
     * @var Cauldron $cauldron
     * @return void
     */
    static function writeProperties( Cauldron $cauldron ): void
    {
        $id = null;
        if( isset($cauldron->id) && is_int($cauldron->id) ){
            $id = $cauldron->id;
        }
        
        $status = null;
        if( in_array( $cauldron->status, 
            [Cauldron::STATUS_PUBLISHED, Cauldron::STATUS_DRAFT, Cauldron::STATUS_ARCHIVED] )
        ){
            $status = $cauldron->status;
        }

        $data = null;
        if( isset($cauldron->data) && $jsonData = json_encode( $cauldron->data ) ){
            $data = $jsonData;
        }

        $cauldron->properties= [
            'id'        => $id,
            'target'    => $cauldron->target?->id ?? $cauldron->targetID ?? null,
            'status'    => $status,
            'name'      => $cauldron->name ?? null,
            'recipe'    => $cauldron->recipe ?? null,
            'data'      => $data,
            'priority'  => $cauldron->priority ?? 0,
        ];

        for( $i=1; $i<=$cauldron->ww->cauldronDepth; $i++ ){
            $cauldron->properties[ 'level_'.$i ] = $cauldron->position($i);
        }

        return;
    }


    /**
     * Set a parenthood relation between two cauldrons
     * @var Cauldron $parent
     * @var Cauldron $child
     * @return bool
     */
    static function setParenthood( Cauldron $parent, Cauldron $child ): bool
    {
        if( !in_array($child, $parent->children) )
        {
            $child->parent      = $parent;
            $parent->children[] = $child;
            return true;
        }
        
        return false;
    }


    /**
     * Set ingrdient into a cauldron
     * @var Cauldron $cauldron
     * @var Ingredient $ingredient
     * @return bool
     */
    static function setIngredient( Cauldron $cauldron, Ingredient $ingredient ): bool
    {
        if( !in_array($ingredient, $cauldron->ingredients) )
        {
            $ingredient->cauldron       = $cauldron;
            if( $cauldron->exist() ){
                $ingredient->cauldronID     = $cauldron->id;
            }
            $cauldron->ingredients[]    = $ingredient;
            
            return true;
        }
        
        return false;
    }


    /**
     * Get the draft folder for a dedicated cauldron
     * @var Cauldron $cauldron
     * @return Cauldron 
     */
    static function getDraftFolder( Cauldron $cauldron ): Cauldron {
        return self::getWorkFolder( $cauldron, Cauldron::DRAFT_FOLDER_STRUCT );
    }

    
    /**
     * Get the archive folder for a dedicated cauldron
     * @var Cauldron $cauldron
     * @return Cauldron 
     */
    static function getArchiveFolder( Cauldron $cauldron ): Cauldron {
        return self::getWorkFolder( $cauldron, Cauldron::ARCHIVE_FOLDER_STRUCT );
    }


    /**
     * PRIVATE get work (draft or archive) folder for a dedicated cauldron
     * @var Cauldron $cauldron
     * @var string $folderStruct
     * @return Cauldron 
     */
    private static function getWorkFolder( Cauldron $cauldron, string $folderStruct ): Cauldron
    {
        foreach( $cauldron->children as $child ){
            if( $child->recipe === $folderStruct ){
                return $child;
            }
        }
        
        $workFolderName = mb_strtoupper( $folderStruct );        
        if( substr($workFolderName, 0, 3) === "WW-" ){
            $workFolderName = substr($workFolderName, 3);
        }
        if( substr($workFolderName, -7) === "-FOLDER" ){
            $workFolderName = substr($workFolderName, 0, -7);
        }
        
        $params = [
            'name'      =>  $workFolderName,
            'recipe'    =>  $folderStruct,
        ];
 
        $folder = self::createFromData( $cauldron->ww, $params );
        $cauldron->addCauldron( $folder );
        $folder->save();

        return $folder;
    }
    
 
    /**
     * @var WoodWiccan $ww
     * @return Cauldron|false
     */
    static function getStorageStructure(  WoodWiccan $ww, ?string $site=null, ?string $recipe=null ): Cauldron|false 
    {
        $result = DataAccess::getStorageStructure( $ww );

        if( !$result ){
            return false;
        }

        $data           = self::instanciate($ww, [ 1 ], $result);
        $rootCauldron   = $data[ 1 ] ?? null;

        if( !$rootCauldron ){
            return false;
        }
        elseif( !$site ){
            return $rootCauldron;
        }

        $siteCauldron = false;
        foreach( $rootCauldron->children as $child ){
            if( $child->name === $site )
            {
                $siteCauldron = $child;
                break;
            }
        }
    
        if( !$siteCauldron )
        {
            $params = [
                'name'      =>  $site,
                'recipe'    =>  "ww-site-folder",
            ];

            $siteCauldron = self::createFromData( $ww, $params );
            $rootCauldron->addCauldron( $siteCauldron );
            $siteCauldron->save();
        }
 
        if( !$siteCauldron ){
            return false;
        }
        elseif( !$recipe ){
            return $siteCauldron;
        }

        $recipeCauldron = false;
        foreach( $siteCauldron->children as $child ){
            if( $child->name === $recipe )
            {
                $recipeCauldron = $child;
                break;
            }
        }
    
        if( !$recipeCauldron )
        {
            $params = [
                'name'      =>  $recipe,
                'recipe'    =>  "ww-recipe-folder",
            ];

            $recipeCauldron = self::createFromData( $ww, $params );
            $siteCauldron->addCauldron( $recipeCauldron );
            $recipeCauldron->save();
        }
 
        if( !$recipeCauldron ){
            return false;
        }
 
        return $recipeCauldron;
    }   

    static function getWitches( Cauldron $cauldron ){
        return WitchHandler::search( $cauldron->ww, [ 'cauldron' => $cauldron->id ]);
    }


    static function save( Cauldron $cauldron ): bool
    {
        if( $cauldron->depth > $cauldron->ww->cauldronDepth ){
            DataAccess::addLevel( $cauldron->ww );
        }
        
        if( !$cauldron->exist() )
        {
            self::writeProperties($cauldron); 

            $params = $cauldron->properties;
            if( isset($params['id']) ){
                unset($params['id']);
            }

            if( !$result = DataAccess::insert($cauldron->ww, $params) ){
                return false;
            }

            $cauldron->id = (int) $result;
        }
        else
        {
            $properties = $cauldron->properties;

            self::writeProperties($cauldron);

            $params = array_diff_assoc($cauldron->properties, $properties);
            if( isset($params['id']) ){
                unset($params['id']);
            }

            if( count($params) ){
                DataAccess::update( $cauldron->ww, $params, ['id' => $cauldron->id] );
            }
        }
        
        return true;
    }


    /**
     * Generate the "position" attribute
     */
    static function position( Cauldron $cauldron ): array
    {
        if( !$cauldron->parent )
        {
            $cauldron->position = [];
            $cauldron->depth    = 0;
        }
        else 
        {
            $cauldron->position = $cauldron->parent->position();
            $newIndex           = DataAccess::getNewPosition( $cauldron->parent );
            $cauldron->depth    = $cauldron->parent->depth + 1;
            
            $cauldron->position[ $cauldron->depth ] = $newIndex;
        }

        return $cauldron->position;
    }

}