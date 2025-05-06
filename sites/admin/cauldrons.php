<?php /** @var WW\Module $this */

use WW\Cauldron;
use WW\Handler\CauldronHandler;

// $conf = [
//     'user',
//     1,
// ];
//$this->ww->dump( $conf );
//$result = CauldronHandler::fetch($this->ww, [1]);
//$this->ww->dump( $result, 'bbb');


$obj = new class {
    public $baseUrl;

    public function href( Cauldron $cauldron )
    {
        $witchId = $cauldron->witches()[0]?->id;        
        return $this->baseUrl.($witchId? '?id='.$witchId."#tab-cauldron-part": "");
    }
};

$obj->baseUrl       = $this->ww->website->getUrl("view");

$tree       = [1 => recursiveTree( 
    CauldronHandler::fetch($this->ww, [1])[1], 
    $this->ww->website->sitesRestrictions, 
    1, 
    [$obj, "href"]  
)];
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

        $daughters[ $content->id ] = $content->isCauldron()? 
                                        recursiveTree( $content, $sitesRestrictions, $currentId, $hrefCallBack ):
                                        [
                                            'id'                => $content->type." ".$content->id,
                                            'name'              => $content->name,
                                            'description'       => $content->type,
                                            'cauldron'          => false,
                                            'invoke'            => true,
                                            'daughters'         => [],
                                            'daughters_orders'  => [],
                                            'path'              => false,            
                                        ];

        $path = $path || $daughters[ $content->id ]['path'];                                            
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
        'daughters_orders'  => array_keys( $daughters ),
        'path'              => $path,
    ];

    if( $cauldronIcon && $invokeIcon && $hrefCallBack ){
        $tree['href'] = call_user_func( $hrefCallBack, $cauldron );
    }
    
    return $tree;
}



 /** @var WW\Module $this */

//$this->addJsLibFile('jquery-3.6.0.min.js');
$this->addCssFile('choose-witch.css');
//$this->addJsFile('choose-cauldron.js');
?>
<div id="cauldrons">
    <h3>
        <span>Cauldrons Navigation</span>
    </h3>
    
    <?php include $this->ww->website->getViewFilePath( 'arborescence.php' ); ?>
</div>

