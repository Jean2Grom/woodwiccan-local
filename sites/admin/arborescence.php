<?php /** @var WW\Module $this */

use WW\Handler\WitchHandler;

$currentId = $this->witch("target")?->id ?? $this->witch()?->id;

$obj = new class {
    public $baseUrl;
    public $unSafeMode;
    public $currentSite;

    public function href( $witch ) 
    {
        if( !$this->unSafeMode 
            && $witch->invoke 
            && (string) $witch->site === $this->currentSite 
        ){
            return $witch->url();
        }
        
        return $this->baseUrl.'?id='.$witch->id;
    }
};

$obj->baseUrl       = $this->ww->website->getUrl("view");
$obj->unSafeMode    = $this->config['navigationUnSafeMode'] ?? false;
$obj->currentSite   = $this->ww->website->site;

$root = WitchHandler::recursiveTree( 
    $this->witch, 
    $this->ww->website->sitesRestrictions, 
    $currentId, 
    $this->maxStatus, 
    [$obj, "href"] 
);

$tree           = [ $this->witch->id => $root ];
$breadcrumb     = [ $this->witch->id ];
$pathFound      = true;
$daughters      = $root["daughters"];
$draggble       = true;
$clipboardUrl   = $this->ww->website->getUrl('clipboard');
$createUrl      = $this->ww->website->getUrl('create-witch');
$cauldronUrl    = $this->ww->website->getUrl('cauldron');
$urlHash        = 'tab-navigation';

while( $pathFound )
{
    $pathFound  = false;
    
    foreach( $daughters as $daughterWitch ){
        if( $daughterWitch['path'] )
        {
            $pathFound      = true;
            $breadcrumb[]   = $daughterWitch['id'];
            $daughters      = $daughterWitch['daughters'];
            break;
        }
    }
}

$this->view();
