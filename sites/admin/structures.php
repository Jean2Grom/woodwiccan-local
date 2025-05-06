<?php /** @var WW\Module $this */

use WW\Structure;
use WW\Attribute;
use WW\Datatype\ExtendedDateTime;
use WW\Tools;

if( $this->ww->request->param("publishStructure") ){
    $action = "publishStructure";
}
elseif( $this->ww->request->param("deleteStructures") ){
    $action = "deleteStructures";
}
elseif( $this->ww->request->param("view", 'get') ){
    $action = "viewStructure";
}
elseif( $this->ww->request->param("createStructure") 
        || $this->ww->request->param("currentAction") === "creatingStructure"
){
    $action = "createStructure";
}
elseif( $this->ww->request->param("edit", 'get')
        ||  $this->ww->request->param("deleteAttribute")
        ||  $this->ww->request->param("addAttribute")
){
    $action = "editStructure";
}
else {
    $action = "listStructures";
}

$messages = [];
if( $action === "publishStructure" )
{
    $structureName      = $this->ww->request->param("edit", 'get');
    $structure          = new Structure( $this->ww,  $structureName );    
    $attributesPost     = $this->ww->request->param("attributes", 'post', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
    $attributesList     = Attribute::list();
    
    $attributes = [];
    foreach( $attributesPost as $attributesPostData )
    {
        $attributeType  = $attributesPostData['type'];
        $attributeClass = $attributesList[ $attributeType ];
        
        $parameters = [];
        if( isset($attributesPostData['parameters']) && is_array($attributesPostData['parameters']) ){
            $parameters = $attributesPostData['parameters'];
        }
        
        $attributeName  = Tools::cleanupString($attributesPostData['name']);
        
        $attribute      = new $attributeClass(
                                $this->ww,
                                $attributeName,
                                $parameters
                        );
        
        $attributes[ $attributeName ] = $attribute;
    }
        
    if( !$structure->update($attributes) )
    {
        $messages[] = "Publication failed, please try again";
        $action     = "editStructure";
    }
    else 
    {
        $messages[] = "Publication of structure ".$structure->name." successfull";
        $action     = "listStructures";
    }
}

if( strcmp($action, "createStructure") == 0 )
{
    $structuresData = Structure::listStructures( $this->ww );
    
    if( $this->ww->request->param("currentAction") === "creatingStructure" )
    {
        $nextStep = true;
        $namePost = $this->ww->request->param("name");
        
        if( !$namePost )
        {
            $nextStep = false;
            $messages[] = "Vous devez saisir un nom valide pour votre structure.";
        }
        
        if( $nextStep )
        {
            $name   = Tools::cleanupString( $namePost );
            
            if( in_array($name, array_keys($structuresData)) )
            {
                $nextStep = false;
                $messages[] = "Le nom que vous avez saisi est déjà utilisé, veuillez en saisir un autre.";
            }
        }
        
        if( $nextStep )
        {
            Structure::create($this->ww, $name);
            
            $queryParams = [ "edit" => $name ];
            
            $structureCopyPost = $this->ww->request->param("structureCopy");
            
            if( $structureCopyPost ){
                $queryParams = [ "base" => $structureCopyPost ];
            }
            
            header( 'Location: '.$this->witch->url($queryParams, $this->ww->website) );
            exit;
        }
    }
    
    $this->view('structures/create.php');
}

if( $action === "editStructure" )
{
    $structureName  = $this->ww->request->param("edit", 'get');
    
    // TODO Conf reading ?
    $attributesList = Attribute::list();
    
    $attributes = [];
    if( $this->ww->request->param("currentAction") !== "editingStructure" )
    {
        $baseStructure = $this->ww->request->param("base", 'get');
        
        if( $baseStructure ){
            $structure = new Structure( $this->ww, $baseStructure );
        }
        else {
            $structure  = new Structure( $this->ww, $structureName );
        }
        
        foreach( $structure->attributes() as $attributeName => $attributeData ){
            $attributes[ $attributeName ] = $attributeData;
        }
    }
    else
    {
        $deleteAttributePost    =   $this->ww->request->param("deleteAttribute", 'post', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY) ?? [];
        $attributesPost         =   $this->ww->request->param("attributes", 'post', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY) ?? [];
        
        foreach( $attributesPost as $indice => $attributePostData ){
            if( !isset($deleteAttributePost[ $indice ]) )
            {
                $attributeType  = $attributePostData['type'];
                $attributeClass = $attributesList[ $attributeType ];
                
                if( isset($attributePostData['parameters']) ){
                    $parameters = $attributePostData['parameters'];
                }
                else {
                    $parameters = [];
                }
                
                $attributes[ $attributePostData['name'] ] = [ 'class' => $attributeClass ];
            }
        }
        
        if( $this->ww->request->param("addAttribute") )
        {
            $attributeType  = $this->ww->request->param("addAttributType");
            $attributeClass = $attributesList[ $attributeType ];
            
            $attributes[ "Nouvel Attribut ".$attributeType ] = [ 'class' => $attributeClass ];
        }
    }
    
    $viewHref   = $this->witch->url([ 'view' => $structureName ]);
    
    $this->view('structures/edit.php');
}

if( $action === "viewStructure" )
{
    $structureName      = $this->ww->request->param('view');
    $structure          = new Structure( $this->ww, $structureName );
    
    $creationDateTime   = $structure->getLastModificationTime();
    $attributes         = $structure->attributes();
    $archivedAttributes = [];
    
    $modificationHref   = $this->witch->url([ 'edit' => $structure->name ]);
    
    $this->view('structures/view.php');
}

if( $action === "deleteStructures" )
{
    $structureName = $this->ww->request->param("structure");
    
    if( $structureName )
    {            
        $structure = new Structure( $this->ww,  $structureName );
        
        if( !$structure->delete() ){
            $messages[] = "Deletion of ".$structureName." failed";
        }
        else {
            $messages[] = "Structure ".$structureName." successfully deleted";              
        }
    }
    
    $action = "listStructures";
}

if( $action === "listStructures" )
{
    $structures = Structure::listStructures( $this->ww, true );
    $count      = count($structures);
    
    foreach( $structures as $key => $value )
    {
        $structures[ $key ]['viewHref']  =   $this->witch->url([ 'view' => $value['name'] ]);
        $structures[ $key ]['creation']  =   new ExtendedDateTime($value['created']);
    }
    
    $this->view();
}
