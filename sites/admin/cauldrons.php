<?php /** @var WW\Module $this */

use WW\Cauldron;
use WW\Handler\CauldronHandler;

$obj = new class {
    public $baseUrl;

    public function href( Cauldron $cauldron )
    {
        $witchId = $cauldron->witches()[0]?->id;        
        return $this->baseUrl.($witchId? '?id='.$witchId: "");
    }
};

$obj->baseUrl       = $this->ww->website->getUrl("view");

$tree = recursiveTree( 
    CauldronHandler::fetch($this->ww, [1])[1], 
    $this->ww->website->sitesRestrictions, 
    1, 
    [$obj, "href"]  
);
$breadcrumb = [ 1 ];

function recursiveTree( Cauldron $cauldron, array|bool $sitesRestrictions=false, ?int $currentId=null, ?array $hrefCallBack=null )
{
    $path       = false;
    if( $currentId && $currentId === $cauldron->id ){
        $path = true;
    }
    
    $daughters  = [];
    foreach( $cauldron->contents() as $content )
    {
        if( $sitesRestrictions 
            && $content->type === "ww-site-folder" 
            && !in_array($content->name, $sitesRestrictions) 
        ){
            continue;
        }

        if( $content->isCauldron() ){
            $subTree = recursiveTree( $content, $sitesRestrictions, $currentId, $hrefCallBack );
        }
        else {
            $subTree = [
                'id'                => $content->type." ".( $content->id ?? uniqid('new_') ),
                'name'              => $content->name,
                'description'       => $content->type,
                'cauldron'          => false,
                'invoke'            => true,
                'daughters'         => [],
                'path'              => false,            
            ];
        }

        $daughters[]    = $subTree;
        $path           = $path || $subTree['path'];
    }
    
    $cauldronIcon   = true;
    $invokeIcon     = false;
    if( in_array($cauldron->type, [ "root", "ww-site-folder", "ww-recipe-folder" ]) )
    {
        $cauldronIcon   = false;
        $invokeIcon     = false;    
    }
    elseif( $cauldron->parent?->type === "ww-recipe-folder" )
    {
        $cauldronIcon   = true;
        $invokeIcon     = true;    
    }
    
    $tree   = [ 
        'id'                => $cauldron->id,
        'name'              => $cauldron->name,
        'description'       => $cauldron->type." [".$cauldron->id."]",
        'cauldron'          => $cauldronIcon,
        'invoke'            => $invokeIcon,
        'daughters'         => $daughters,
        'path'              => $path,
    ];

    if( $cauldronIcon && $invokeIcon && $hrefCallBack ){
        $tree['href'] = call_user_func( $hrefCallBack, $cauldron );
    }
    
    return $tree;
}

$this->display();