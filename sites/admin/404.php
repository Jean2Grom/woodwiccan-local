<?php /** @var WW\Module $this */

if( $this->ww->user->connexion ){
    $this->setContext('standard');
}

$this->display();