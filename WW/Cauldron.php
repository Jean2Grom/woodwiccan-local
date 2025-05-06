<?php 
namespace WW;

use WW\Cauldron\CauldronContentInterface;
use WW\Cauldron\CauldronContentTrait;
use WW\Cauldron\Ingredient;
use WW\Cauldron\Recipe;
use WW\DataAccess\CauldronDataAccess as DataAccess;
use WW\Handler\CauldronHandler as Handler;
use WW\Handler\IngredientHandler;

class Cauldron implements CauldronContentInterface
{
    use CauldronContentTrait;

    const FIELDS = [
        "id",
        "target",
        "status",
        "name",
        "recipe",
        "data",
        "priority",
    ];

    const HISTORY_FIELDS = [
        "creator",
        "created",
        "modificator",
        "modified",
    ];

    const STATUS_PUBLISHED      = null;
    const STATUS_DRAFT          = 0;
    const STATUS_ARCHIVED       = 1;

    const DRAFT_FOLDER_STRUCT   = "ww-drafts-folder";
    const ARCHIVE_FOLDER_STRUCT = "ww-archives-folder";

    const DIR                   = "cauldron";
    const VIEW_DIR              = "view/cauldron";

    public array $properties  = [];

    public ?int $id;
    public ?int $status     = null;
    public ?int $targetID   = null;
    public ?string $name;

    /** @var null|string|Recipe */
    public mixed $recipe;
    public array $allowed = [];

    public ?\stdClass $data;
    public ?int $priority;
    public ?\DateTime $datetime;
    
    public int $depth       = 0;
    public ?array $position = null;

    public ?self $parent;

    /** @var self[] */
    public array $children    = [];
    
    /** @var Ingredient[] */
    public array $ingredients = [];

    /** @var ?Witch[] */
    public ?array $witches = null;

    /** @var CauldronContentInterface[] */
    public array $pendingRemoveContents = [];

    protected $content;

    public ?self $target        = null;
    public ?self $draft         = null;

    /** 
     * WoodWiccan container class to allow whole access to Kernel
     * @var WoodWiccan
     */
    public WoodWiccan $ww;
    
    /**
     * Property setting 
     * @param string $name
     * @param mixed $value
     * @return void
     */
    public function __set( string $name, mixed $value ): void {
        $this->properties[ $name ] = $value;
    }
    
    /**
     * Property reading
     * @param string $name
     * @return mixed
     */
    public function __get( string $name ): mixed 
    {
        if( $name === 'type' ){
            return str_replace(' ', '-', $this->recipe) ?? "cauldron";
        }
        return $this->properties[ $name ] ?? null;
    }
    
    /**
     * Name reading
     * @return string
     */
    public function __toString(): string {
        return $this->name ?? "Cauldron".(isset($this->id)? ": ".$this->id : "");
    }
    
    /**
     * Is this cauldron exist in database ?
     * @return bool
     */
    function exist(): bool {
        return !empty($this->id);
    }
    
    /**
     * @return array [ $level => $int ]
     */
    function position()
    {
        if( !isset($this->position) ){
            if( is_null($this->parent) )
            {
                $this->position = [];
                $this->depth    = 0;
            }
            else
            {
                $this->position = $this->parent->position();
                
                $newIndex       = DataAccess::getNewPosition( $this->parent );
                $this->depth    = $this->parent->depth + 1;

                // $newIndex       = 1;
                // $this->depth    = $this->parent->depth + 1;
                // foreach( $this->parent->children as $child ){
                //     if( $child->levelPosition( $this->depth ) >= $newIndex ){
                //         $newIndex  = $child->levelPosition( $this->depth ) + 1;
                //     }
                // }
                
                $this->position[ $this->depth ] = $newIndex;

            }
        }

        return $this->position;
    }

    function levelPosition( int $level ){
        return $this->position()[ $level ] ?? null;
    }

    /**
     * Is this cauldron a published content ?
     * @return bool
     */
    function isPublished(): bool {
        return $this->status === self::STATUS_PUBLISHED;
    }
    
    /**
     * Is this cauldron a draft ?
     * @return bool
     */
    function isDraft(): bool {
        return $this->status === self::STATUS_DRAFT;
    }
    
    /**
     * Is this cauldron a archive ?
     * @return bool
     */
    function isArchive(): bool {
        return $this->status === self::STATUS_ARCHIVED;
    }
    
    /**
     * Test the status of this cauldron: is it a content (ie not a draft nor an archive)
     * @return bool
     */
    function isContent(): bool
    {
        if( in_array($this->recipe, [ self::DRAFT_FOLDER_STRUCT, self::ARCHIVE_FOLDER_STRUCT ]) ){
            return false;
        }

        return true;
    }

    /**
     * Get Recipe of this cauldron: if not instanciate yet (ie is a simple string), create instance from Conf reading 
     * @return Recipe
     */
    function recipe(): Recipe
    {
        if( !($this->recipe instanceof Recipe) ){
            $this->recipe = $this->ww->configuration->recipe( $this->recipe ) 
                                ?? $this->ww->configuration->recipe('folder');
        }

        return $this->recipe;
    }

    /** 
     * get constituting elements of cauldron based on recipe's reading
     * @return array[] : ["name" => <NAME>,  "type" => <TYPE>, ...]
     */
    function allowedNewElements(): array    
    {
        if( !isset($this->allowed['ingredients']) )
        {
            $this->allowed['elements'] = [];

            foreach( $this->recipe()->composition ?? [] as $element ){
                if( !$this->content($element[ 'name' ]) ){
                    $this->allowed['elements'][] = $element;
                }
            }
        }

        return $this->allowed['elements'];
    }

    /**
     * get allowed types of ingredients based on recipe's reading
     * @return string[]
     */
    function allowedNewIngredients(): array
    {
        if( !isset($this->allowed['ingredients']) ){
            $this->allowed['ingredients'] = $this->recipe()->allowedIngredients();
        }

        return $this->allowed['ingredients'];
    }

    /**
     * get allowed types of recipes based on recipe's reading
     * @return Recipe[]
     */
    function allowedNewRecipes(): array
    {
        if( !isset($this->allowed['recipes']) ){
            $this->allowed['recipes'] = $this->recipe()->allowedRecipes();
        }

        return $this->allowed['recipes'];
    }

    /**
     * read content from name, if cauldron has only one content, name is not mandatory
     * @var string $name
     * @return ?CauldronContentInterface
     */
    function content( string $name="" ): ?CauldronContentInterface
    {
        if( is_null($this->content) ){
            $this->generateContent();
        }

        if( !$name && count($this->content) === 1 ){
            return array_values($this->content)[0];
        }

        foreach( $this->content as $content ){
            if( $content->name === $name ){
                return $content;
            }
        }

        return null;
    }

    /**
     * Display priority ordered array of content Ingredients and children Cauldron
     * @return  CauldronContentInterface[]
     */
    function contents(): array
    {
        if( is_null($this->content) ){
            $this->generateContent();
        }

        return $this->content;
    }


    function value(){
        $this->contents();
    }

    private function generateContent(): void
    {
        $buffer     = [];
        $indice     = 0;

        foreach( $this->ingredients as $ingredient )
        {
            $priority   = $ingredient->priority ?? 0;
            $key        = $indice++;
            $buffer[ $priority ] = array_replace( 
                $buffer[ $priority ] ?? [], 
                [ $key => $ingredient ]
            );
        }
        
        foreach( $this->children as $child )
        {
            if( !$child->isContent() ){
                continue;
            }

            $priority   = $child->priority ?? 0;
            $key        = $indice++;
            $buffer[ $priority ] = array_replace( 
                $buffer[ $priority ] ?? [], 
                [ $key => $child ]
            );
        }

        $this->content = [];

        krsort($buffer);
        foreach( $buffer as $priorityArray )
        {
            ksort($priorityArray);
            foreach( $priorityArray as $contentItem ){
                $this->content[] = $contentItem;
            }
        }

        return;
    }

    function save( bool $transactionMode=true ): bool
    {
        if( !$transactionMode ){
            return $this->saveAction();
        }

        $this->ww->db->begin();
        try {
            if( !$this->saveAction() )
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

        return true;
    }

    protected function saveAction(): bool
    {
        if( !$this->validate() )
        {
            $this->ww->log->error("Not a valid content, can't save ".$this->name);
            return false;
        }

        $this->position();

        if( $this->depth > $this->ww->cauldronDepth ){
            DataAccess::increaseDepth( $this->ww );
        }
        
        if( !$this->exist() )
        {
            Handler::writeProperties($this); 
            $result = DataAccess::insert($this); 
            
            if( $result ){
                $this->id = (int) $result;
            }
        }
        else 
        {
            $properties = $this->properties;

            Handler::writeProperties($this);
            $result = DataAccess::update( $this, array_diff_assoc($this->properties, $properties) );
        }
        
        if( $result === false ){
            return false;
        }
        $result = true;
        
        // Deletion of pending deprecated contents
        $result = $result && $this->purge();

        // Saving contents
        foreach( $this->ingredients as $ingredient ){
            $result = $result && $ingredient->save();
        }

        foreach( $this->children as $child ) 
        {
            if( !$child->isContent() ){
                continue;
            }

            $result = $result && $child->save( false );
        }

        return $result;
    }

    function validate()
    {
        $composition    = $this->recipe()->composition ?? [];

        $contentQtt     = 0;
        foreach( $this->contents() as $content )
        {
            $isCompositionElement = false;
            foreach( $composition as $i => $element ){
                if( $element['name'] === $content->name && $element['type'] === $content->type )
                {
                    $isCompositionElement = true;
                    unset($composition[ $i ]);
                    break;
                }
            }
            
            if( !$isCompositionElement )
            {
                $contentQtt++;
                if( !$this->recipe()->isAllowed($content->type) )
                {
                    $this->ww->log->error( "type error: ".$content->type." is not allowed for ".$this->recipe );
                    return false;
                }
            }
        }

        foreach( $composition as $unmatchedElement ){
            if( $unmatchedElement['mandatory'] ?? null )
            {
                $this->ww->log->error( "cauldron error: missing ".$unmatchedElement['name']." content for ".$this->recipe );
                return false;
            }
        }

        $min        = $this->recipe()->require['min'] ?? 0;
        $max        = $this->recipe()->require['max'] ?? -1;
        if( $min && $contentQtt < $min )
        {
            $this->ww->log->error( "cauldron error: not enough contents for ".$this->recipe );
            return false;
        }
        elseif( $max > -1 && $contentQtt > $max )
        {
            $this->ww->log->error( "cauldron error: too many contents for ".$this->recipe );
            return false;
        }

        return true;
    }

    function purge(): bool 
    {
        $result = true;

        foreach( $this->pendingRemoveContents as $removingContent ){
            if( $removingContent->isIngredient() ){
                $result = $result && $removingContent->delete();
            }
            else {
                $result = $result && $removingContent->delete( false );
            }
        }

        return $result;
    }

    function delete( bool $transactionMode=true ): bool
    {
        if( $this->target?->draft === $this ){
            $this->target->draft = null;
        }

        if( !$transactionMode ){
            return $this->deleteAction();
        }
        $this->ww->db->begin();

        try {
            if( !$this->deleteAction() )
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
        return true;
    }

    protected function deleteAction(): bool
    {
        $result = true;

        foreach( $this->ingredients as $ingredient ){
            $result = $result && $ingredient->delete();
        }

        foreach( $this->children as $child ) 
        {
            if( !is_a($child, __CLASS__) ){
                continue;
            }

            $result = $result && $child->delete( false );
        }

        if( $result === false ){
            return false;
        }

        // Deletion of pending deprecated contents
        if( $this->purge() === false ){
            return false;
        }
        
        if( $this->exist() ){
            return DataAccess::delete( $this ) !== false;
        }
        
        return true;
    }
  
    function add( CauldronContentInterface $content ): bool
    {
        // Cauldron case
        if( is_a($content, __CLASS__) ){
            return $this->addCauldron( $content );
        }

        // Ingredient case
        return $this->addIngredient( $content );
    }

    function addCauldron( Cauldron $cauldron ): bool
    {
        $cauldron->position = null;
        $cauldron->depth    = 0;

        Handler::setParenthood($this, $cauldron);
        $this->content = null;

        foreach( $cauldron->children as $child ){
            $cauldron->addCauldron( $child );
        }

        return true;
    }

    function addIngredient( Ingredient $ingredient ): bool
    {
        Handler::setIngredient($this, $ingredient);
        $this->content = null;

        return true;
    }

    function readInputs( mixed $inputs=null ): self
    {
        $params = $inputs ?? $this->ww->request->inputs();

        if( !$params ){
            return $this;
        }
        
        if( isset($params['name']) ){
            $this->name = htmlspecialchars($params['name']);
        }

        $priorityInterval   = 100;
        $priority           = count($params['content'] ?? []) * $priorityInterval;
        
        $contents               = $this->contents();
        $this->content          = null;
        
        $this->ingredients  = [];
        $this->children     = [];
        $storage            = $this->ww->configuration->storage();
        foreach( $params['content'] ?? [] as $indice => $contentParams )
        {
            if( !($contentParams['type'] ?? null) )
            {
                $this->ww->log->error( "type undefined, ".$indice." entry skipped" );
                continue;
            }
            
            if( $contentParams['$_FILES'] ?? null ) 
            {
                $fileInputs = $_FILES[ $contentParams['$_FILES'] ];
                
                if( $fileInputs 
                    && isset($fileInputs["error" ]) 
                    && $fileInputs["error"] !== 4 
                    && $fileInputs["error"] > 0 
                ){
                    $phpFileUploadErrors = [
                        0 => 'There is no error, the file uploaded with success',
                        1 => 'The uploaded file exceeds the upload_max_filesize directive in php.ini',
                        2 => 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form',
                        3 => 'The uploaded file was only partially uploaded',
                        4 => 'No file was uploaded',
                        6 => 'Missing a temporary folder',
                        7 => 'Failed to write file to disk.',
                        8 => 'A PHP extension stopped the file upload.',     
                    ];
                    
                    $this->ww->log->error( "file upload failed: ".$phpFileUploadErrors[ $fileInputs['error'] ] );
                    $contentParams['value'] = "";

                }
                elseif( $fileInputs 
                        && isset($fileInputs["error" ]) 
                        && $fileInputs["error"] === 0 
                        && $fileInputs[ "tmp_name" ] 
                        && filesize($fileInputs[ "tmp_name" ]) !== false 
                ){
                    $path   =   $fileInputs['type'];
                    $value  =   sha1_file($fileInputs[ "tmp_name" ]);

                    if( !$this->ww->configuration->createFolder($storage.'/'.$path) 
                        || !move_uploaded_file($fileInputs["tmp_name"], $storage.'/'.$path.'/'.$value) 
                    ){
                        $this->ww->log->error( "file upload failed: server local copy error" );
                        $contentParams['value'] = "";
                    }
                    else {
                        $contentParams['value'] = $path.'/'.$value;
                    }
                }
                elseif( !empty($contentParams['value']) && is_file($contentParams['value']) )
                {
                    $path   =   mime_content_type($contentParams['value']);
                    $value  =   sha1_file($contentParams['value']);

                    if( (!is_file( $storage.'/'.$path.'/'.$value ) 
                            || sha1_file( $storage.'/'.$path.'/'.$value ) !== $value)
                        && (!$this->ww->configuration->createFolder( $storage.'/'.$path ) 
                            || !rename( $contentParams['value'], $storage.'/'.$path.'/'.$value ))
                    ){
                        $this->ww->log->error( "file move failed" );
                        $contentParams['value'] = "";
                    }
                    else {
                        $contentParams['value'] = $path.'/'.$value;
                    }
                }
                else {
                    $contentParams['value'] = "";
                }
            }

            $contentParams[ 'priority' ]    =   $priority;
            $priority                       -=  $priorityInterval;

            $content = false;
            if( $indice !== "new" )
            {
                if( isset($contentParams['ID']) ){
                    foreach( $contents as $contentItemKey => $contentItem ){
                        if( $contentParams['type'] === $contentItem->type 
                            && (int) $contentParams['ID'] === $contentItem->id
                        ){
                            $content = $contentItem;
                            unset($contents[ $contentItemKey ]);
                            break;
                        }
                    }
                }

                if( !$content 
                    && $contents[ $indice ]->type === $contentParams['type'])
                {
                    $content = $contents[ $indice ];
                    unset($contents[ $indice ]);
                }
            }

            if( !$content )
            {
                if( !$this->recipe()->isCompositionElement($contentParams['type'], $contentParams['name'] ?? "")
                    && !$this->recipe()->isAllowed($contentParams['type']) 
                ){
                    $this->ww->log->error( "type error: ".$contentParams['type']." is not allowed for ".$this->recipe );
                    continue;
                }

                $content = $this->create( 
                    $contentParams['name'] ?? "", 
                    $contentParams['type'], 
                    [ 
                        'value'     => $contentParams['value'] ?? null,
                        'priority'  => $contentParams['priority'] ?? 0, 
                    ] 
                );

                if( $contentParams['content'] ?? null ){
                    $content->readInputs( $contentParams );
                }
            }
            else {
                $content->readInputs( $contentParams );
            }

            if( $content->isIngredient() && !in_array($content, $this->ingredients) ){
                $this->ingredients[] = $content;
            }
            elseif( $content->isCauldron() && !in_array($content, $this->children) ) {
                $this->children[] = $content;
            }
        }

        foreach( $contents as $unmatchedContent ){
            $this->pendingRemoveContents[] = $unmatchedContent;
        }

        return $this;
    }

    function create( string $name, ?string $type=null, array $initProperties=[] ): CauldronContentInterface
    {
        if( $type && in_array($type, Ingredient::list()) )
        {
            $content            = IngredientHandler::factory($type);
            $content->ww        = $this->ww;
            $content->name      = !empty($name)? $name: $type;

            $content->init( $initProperties['value'] ?? null );
        }
        else 
        {
            $recipe     = $this->ww->configuration->recipe( $type ) 
                            ?? $this->ww->configuration->recipe('folder');
            $content    = $recipe->factory( !empty($name)? $name: $recipe->name, $initProperties );
        }

        $content->priority  =  $initProperties['priority'] ?? 0; 

        $this->add( $content );

        return $content;
    }

    function draft(): self
    {
        if( $this->isDraft() ){
            return $this;
        }
        elseif( $this->draft ){
            return $this->draft;
        }

        $folder = Handler::getDraftFolder( $this );

        foreach( $folder->children as $child ){
            if( $child->isDraft() && $child->targetID === $this->id )
            {
                $child->target  = $this;
                return $child;
            }
        }

        $this->draft  = Handler::createDraft( $this );
        $folder->addCauldron( $this->draft );
        
        return $this->draft;
    }

    function publish( bool $transactionMode=true ): bool
    {
        if( !$transactionMode ){
            return $this->publishAction();
        }
        $this->ww->db->begin();

        try {
            if( !$this->publishAction() )
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
        return true;
    }

    function publishAction(): bool
    {
        $target = false;
        if( $this->target ){
            $target = $this->target;
        }
        elseif( $this->targetID ){
            $target = Handler::fetch( $this->ww, ['id' => $this->targetID] );
        }

        if( $target )
        {
            // Create archive 
            $archiveFolder  = Handler::getArchiveFolder($target);

            Handler::writeProperties($target);
            $archiveProperties              = $target->properties;
            $archiveProperties['target']    = $target->id;
            $archiveProperties['status']    = Cauldron::STATUS_ARCHIVED;
            unset( $archiveProperties['id'] );

            $archive            = Handler::createFromData( $this->ww, $archiveProperties );
            $archive->target    = $target;

            $archiveFolder->addCauldron( $archive );

            // Move published target content to created archive
            foreach( $target->ingredients as $content ){
                $archive->add( $content );
            }
            $target->ingredients = [];

            foreach( $target->children as $key => $child ){
                if( $child->isContent() )
                {
                    $archive->add( $child );
                    unset( $target->children[$key] );
                }
            }

            // Update published target
            $target->name = $this->name;
            $target->data = $this->data;

            // Move this (draft) content to published target
            foreach( $this->ingredients as $content ){
                $target->add( $content );
            }
            $this->ingredients = [];

            foreach( $this->children as $key => $child ){
                if( $child->isContent() )
                {
                    $target->add( $child );
                    unset( $this->children[$key] );
                }
            }

            $target->save( false );
            $archive->save( false );
            $this->delete( false );
        }
        else 
        {
            $this->status = null;
            $this->save( false );
            if( $this->targetID ){
                DataAccess::updateTargetID( $this->ww, $this->targetID, $this->id );
            }
        }

        return true;
    }

    function removeWitch( Witch $witch )
    {
        if( !$this->witches() ){
            return false;
        }

        $witchToRemoveKey = array_search($witch, $this->witches);

        if( $witchToRemoveKey === false ){
            return false;
        }

        unset($this->witches[ $witchToRemoveKey ]);
        
        $witch->removeCauldron();

        if( !$this->witches ){
            $this->delete();
        }

        return true;
    }

    function witches()
    {
        if( !isset($this->witches) )
        {
            $getWitches     = Handler::getWitches( $this );

            if( $getWitches !== false ){
                $this->witches  = $getWitches;
            }
        }
        
        return $this->witches;
    }

    function orderWitches(): void
    {
        if( !$this->witches ){
            return;
        }

        $buffer     = [];
        $defaultId  = 0;

        foreach( $this->witches as $witch )
        {
            $priority   = $witch->cauldronPriority ?? 0;
            $key        = ($witch->name ?? "")."_".($witch->id ?? $defaultId++);
            $buffer[ $priority ] = array_replace( 
                $buffer[ $priority ] ?? [], 
                [ $key => $witch ]
            );
        }

        $this->witches = [];

        krsort($buffer);
        foreach( $buffer as $priorityArray )
        {
            ksort($priorityArray);
            foreach( $priorityArray as $contentItem ){
                $this->witches[] = $contentItem;
            }
        }

        return;
    }

}