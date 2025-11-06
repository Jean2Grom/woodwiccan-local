<?php /** @var WW\Module $this */

use WW\Cairn;

$currentId  = $this->witch("target")?->id ?? $this->witch()?->id;
$root = Cairn::tree( 
    $this->witch, 
    [
        'site'      => $this->ww->website->sitesRestrictions, 
        'status'    => $this->maxStatus, 
    ],
    $currentId
);

$tree       = [ $root ];
$breadcrumb = [ $this->witch->id ];

$this->view();