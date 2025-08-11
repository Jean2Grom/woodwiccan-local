<?php /** @var WW\Module $this */ 

$menu = [];
foreach( $this->witch()->daughters() as $section ){
    $menu[] = [
        'href' =>   "#".$section->cauldron()->type.$section->cauldron()->id,
        'text' =>   $section->name,
    ];
}

$callToAction   = $this->witch()->cauldron()->content('menu-call-to-action');
$footer         = $this->witch()->cauldron()->content('footer');

$this->view();