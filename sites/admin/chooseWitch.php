<?php /** @var WW\Module $this */

use WW\Handler\WitchHandler;

$currentId  = $this->witch("target")?->id ?? $this->witch()?->id;
$root       = WitchHandler::recursiveTree( $this->witch, $this->ww->website->sitesRestrictions, $currentId, $this->maxStatus );
$tree       = [ $this->witch->id => $root ];
$breadcrumb = [ $this->witch->id ];

$this->view();