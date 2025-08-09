<?php /** @var WW\Module $this */ 

if( $this->ww->request->param("action") === "login" && $this->ww->user->connexion )
{
    header( 'Location: '.$this->ww->website->getFullUrl() );
    exit();
}

$alerts = $this->ww->user->getAlerts();
foreach( $this->ww->user->loginMessages as $message ){
    $alerts[] = [
        'level'     =>  'warning',
        'message'   =>  $message,
    ];
}

$this->ww->user->disconnect();

$this->view();