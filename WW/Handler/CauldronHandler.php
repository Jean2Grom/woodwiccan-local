<?php 
namespace WW\Handler;

use WW\WoodWiccan;
use WW\Cauldron;
use WW\Cauldron\Ingredient;
use WW\DataAccess\CauldronDataAccess AS DataAccess;
use WW\Datatype\ExtendedDateTime;
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
            $cauldron       = new Cauldron();
        }
        
        $cauldron->ww   = $ww;
        
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
     * @var bool $excludePostition
     * @return void
     */
    static function readProperties( Cauldron $cauldron, bool $excludePostition=false ): void
    {
        $cauldron->id = null;
        if( isset($cauldron->properties['id']) && ctype_digit(strval($cauldron->properties['id'])) ){
            $cauldron->id = (int) $cauldron->properties['id'];
        }
        
        $cauldron->status = null;
        if( isset($cauldron->properties['status']) ){
            $cauldron->status = $cauldron->properties['status'];
        }
        
        $cauldron->targetID = null;
        if( isset($cauldron->properties['target']) && ctype_digit(strval($cauldron->properties['target'])) ){
            $cauldron->targetID = (int) $cauldron->properties['target'];
        }
        
        if( $cauldron->targetID !== $cauldron->target?->id ){
            $cauldron->target   = null;
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
        if( isset($cauldron->properties['priority']) ){
            $cauldron->priority = (int) $cauldron->properties['priority'];
        }
        
        $cauldron->datetime = null;
        if( isset($cauldron->properties['datetime']) ){
            $cauldron->datetime = new ExtendedDateTime($cauldron->properties['datetime']);
        }

        if( $excludePostition ){
            return;
        }
        
        $cauldron->position    = [];
        
        $i = 1;
        while( 
            isset($cauldron->properties[ 'level_'.$i ]) 
            && ctype_digit(strval( $cauldron->properties['level_'.$i] )) 
        ){
            $cauldron->position[ $i ] = (int) $cauldron->properties[ 'level_'.$i ];
            $i++;
        }
        $cauldron->depth = $i - 1; 
        
        return;
    }


    /**
     * Update var "properties" (database direct fields) based on Object current state 
     * @var Cauldron $cauldron
     * @return void
     */
    static function writeProperties( Cauldron $cauldron ): void
    {
        $cauldron->properties= [];

        if( isset($cauldron->id) && is_int($cauldron->id) ){
            $cauldron->properties['id'] = $cauldron->id;
        }        
        
        if( in_array( $cauldron->status, 
            [Cauldron::STATUS_PUBLISHED, Cauldron::STATUS_DRAFT, Cauldron::STATUS_ARCHIVED] )
        ){
            $cauldron->properties['status'] = $cauldron->status;
        }
        
        if( $cauldron->target ){
            $cauldron->properties['target'] = $cauldron->target->id;
        }
        elseif( $cauldron->targetID ){
            $cauldron->properties['target'] = $cauldron->targetID;
        }
        else {
            $cauldron->properties['target'] = null;
        }
        
        if( isset($cauldron->name) ){
            $cauldron->properties['name'] = $cauldron->name;
        }
        
        if( isset($cauldron->recipe) ){
            $cauldron->properties['recipe'] = $cauldron->recipe;
        }
        
        if( isset($cauldron->data) )
        {
            $jsonData = json_encode( $cauldron->data );

            if( $jsonData ){
                $cauldron->properties['data'] = $jsonData;
            }
        }

        $cauldron->properties['priority'] = $cauldron->priority ?? 0;
        
        if( isset($cauldron->datetime) ){
            $cauldron->properties['datetime'] = $cauldron->datetime->format('Y-m-d H:i:s');
        }

        $i = 1;
        while( 
            $cauldron->levelPosition($i) 
            && ctype_digit(strval( $cauldron->levelPosition($i) )) 
        ){
            $cauldron->properties[ 'level_'.$i ] = $cauldron->levelPosition($i);
            $i++;
        }
        $cauldron->depth = $i - 1; 
        
        for( $j=$i; $j<=$cauldron->ww->cauldronDepth; $j++ ){
            $cauldron->properties[ 'level_'.$j ] = null;
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
     * Create a draft from a cauldron
     * @var Cauldron $cauldron
     * @return Cauldron
     */
    static function createDraft( Cauldron $cauldron ): Cauldron
    {
        self::writeProperties( $cauldron );

        $draftProperties            = $cauldron->properties;
        $draftProperties['target']  = $cauldron->id;
        $draftProperties['status']  = Cauldron::STATUS_DRAFT;

        unset( $draftProperties['id'] );
        
        $draft          = self::createFromData( $cauldron->ww, $draftProperties );
        $draft->target  = $cauldron;
        
        self::createDraftContent( $cauldron, $draft );
        
        return $draft;
    }


    /**
     * PRIVATE create the draft contents
     * @var Cauldron $cauldron
     * @var Cauldron $draft
     * @return void
     */
    static private function createDraftContent( Cauldron $cauldron, Cauldron $draft ): void
    {
        foreach( $cauldron->contents() as $content )
        {
            // Ingredient case
            //if( get_class($content) !== get_class($cauldron) )
            if( is_a($content, Ingredient::class) )
            {
                IngredientHandler::writeProperties($content);
                $draftContentProperties = $content->properties;
                unset( $draftContentProperties['id'] );
                unset( $draftContentProperties['cauldron_fk'] );

                IngredientHandler::createFromData( $draft, $content->type, $draftContentProperties );
            }
            // Cauldron case            
            else 
            {
                self::writeProperties( $content );
                $draftContentProperties = $content->properties;
                unset( $draftContentProperties['id'] );
                
                $draftContent = self::createFromData( $cauldron->ww, $draftContentProperties );

                self::setParenthood( $draft, $draftContent );
                self::createDraftContent( $content, $draftContent );
            }
        }

        return;
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


}