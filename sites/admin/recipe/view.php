<?php /** @var WW\Module $this */

$recipeName = $this->ww->request->param('recipe');
if( $recipeName ){
    $recipe = $this->ww->configuration->recipe( $recipeName );
}

if( !$recipe )
{
    $this->ww->user->addAlert([
        'level'     =>  'error',
        'message'   =>  "Recipe not found"
    ]);
    header( 'Location: '.$this->ww->website->getFullUrl('recipe') );
    exit();
}

$this->display();