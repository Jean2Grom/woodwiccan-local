<?php /** @var WW\Context $this */ 

$background =   $this->witch()?->cauldron()?->content('background') 
                    ?? $this->witch('home')?->cauldron()?->content('background');

if( $background ){
    $backgroundCss = "background:url(".$background->file->value().") no-repeat center center";
}
else {
    $backgroundCss = "";
}

$headline   =   $this->witch()?->cauldron()?->content('headline') 
                    ?? $this->witch('home')?->cauldron()?->content('headline');

$body   =   $this->witch()?->cauldron()?->content('body') 
                ?? $this->witch('home')?->cauldron()?->content('body');

$this->view();