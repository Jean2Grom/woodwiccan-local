<?php /** @var WW\Module $this */

use WW\Tools;

if( !$this->witch("target") )
{
    $this->ww->user->addAlert([
        'level'     =>  'error',
        'message'   =>  "Cauldron Witch not found"
    ]);

    header( 'Location: '.$this->ww->website->getFullUrl() );
    exit();
}
elseif( !$this->witch("target")->cauldron() )
{
    $this->ww->user->addAlert([
        'level'     =>  'error',
        'message'   =>  "Cauldron not found"
    ]);
    
    header( 'Location: '.$this->ww->website->getFullUrl('view?id='.$this->witch("target")->id) );
    exit();
}
elseif( !$this->witch("target")->cauldron()->draft() )
{
    $this->ww->user->addAlert([
        'level'     =>  'error',
        'message'   =>  "Draft can't be read"
    ]);
    
    header( 'Location: '.$this->ww->website->getFullUrl('view?id='.$this->witch("target")->id) );
    exit();
}

// TODO multi draft management

$cauldron       = $this->witch("target")->cauldron();
$return         = false;

switch( Tools::filterAction( 
    $this->ww->request->param('action'),
    [
        'save',
        'save-and-return',
        'publish',
        'delete',
    ]
) ){
    case 'publish':
        if( $cauldron->draft()->readInputs()->publish() === false ){
            $this->ww->user->addAlert([
                'level'     =>  'error',
                'message'   =>  "Error, publication canceled"
            ]);
        }
        else 
        {
            $return = true;
            $this->ww->user->addAlert([
                'level'     =>  'success',
                'message'   =>  "Published"
            ]);
        }
    break;

    case 'save-and-return':
        $return = true;
    case 'save':
        $saved = $cauldron->draft()->readInputs()->save();
        
        if( $saved === false )
        {
            $return = false;
            $this->ww->user->addAlert([
                'level'     =>  'error',
                'message'   =>  "Error, update canceled"
            ]);
        }
        elseif( $saved === 0 ){
            $this->ww->user->addAlert([
                'level'     =>  'warning',
                'message'   =>  "No update"
            ]);
        }
        else {
            $this->ww->user->addAlert([
                'level'     =>  'success',
                'message'   =>  "Draft Updated"
            ]);
        }
        foreach( $cauldron->draft()->errors() as $error ){
            $this->ww->user->addAlert([
                'level'     =>  'warning',
                'message'   =>  $error
            ]);
        }
    break;
    
    case 'delete':
        if( !$cauldron->draft()->delete() ){
            $this->ww->user->addAlert([
                'level'     =>  'error',
                'message'   =>  "Error, remove canceled",
            ]);
        }
        else 
        {
            $return     = true;
            $this->ww->user->addAlert([
                'level'     =>  'success',
                'message'   =>  "Draft removed"
            ]);
        }
    break;    
}

if( $return )
{
    header( 'Location: '.$this->ww->website->getFullUrl('view', [ 'id' => $this->witch("target")->id ]) );
    exit();
}

$this->view();