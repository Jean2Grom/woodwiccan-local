<?php /** @var WW\Module $this */

$possibleActionsList = [
    'save',
    'save-and-return',
    'publish',
    'delete',
];

$action = $this->ww->request->param('action');
if( !in_array($action, $possibleActionsList) ){
    $action = false;
}

if( !$this->witch("target") )
{
    $this->ww->user->addAlert([
        'level'     =>  'error',
        'message'   =>  "Craft not found"
    ]);
    header( 'Location: '.$this->ww->website->getFullUrl() );
    exit();
}

$craft      = $this->witch("target")->craft() ?? false;
if( !$craft )
{
    $this->ww->user->addAlert([
        'level'     =>  'error',
        'message'   =>  "Craft not found"
    ]);
    header( 'Location: '.$this->ww->website->getFullUrl('view', [ 'id' => $this->witch("target")->id ]) );
    exit();
}

// TODO multi draft management
$draft = $craft->getDraft();

if( empty($draft) ){
    $this->ww->user->addAlert([
        'level'     =>  'error',
        'message'   =>  "Draft can't be read"
    ]);
}

switch( $action )
{
    case 'publish':
        $publish = true;
    case 'save-and-return':
        $return = true;
    case 'save':
        $publish    = $publish ?? false;
        $return     = $return ?? false;
        
        $params = [];
        foreach( $draft->getEditParams() as $param )
        {
            $value = $this->ww->request->param($param['name'] ?? $param, 'post', $param['filter'] ??  FILTER_DEFAULT, $param['option'] ??  0 );                
            
            if( isset($value) ){
                $params[ $param['name'] ?? $param ] = $value;
            }
        }
        
        $saved = $draft->update( $params );

        if( $saved === false )
        {
            $this->ww->user->addAlert([
                'level'     =>  'error',
                'message'   =>  "Error, update canceled"
            ]);
            $return = false;
        }
        elseif( $saved === 0 && !$publish ){
            $this->ww->user->addAlert([
                'level'     =>  'warning',
                'message'   =>  "No update"
            ]);
        }
        elseif( $publish )
        {
            if( $draft->publish() === false )
            {
                $this->ww->user->addAlert([
                    'level'     =>  'error',
                    'message'   =>  "Error, publication canceled"
                ]);
                
                $return = false;
            }
            else {
                $this->ww->user->addAlert([
                    'level'     =>  'success',
                    'message'   =>  "Published"
                ]);                
            }
        }
        else {
            $this->ww->user->addAlert([
                'level'     =>  'success',
                'message'   =>  "Updated"
            ]);
        }
        
        if( $return )
        {
            header( 'Location: '.$this->ww->website->getFullUrl('view', [ 'id' => $this->witch("target")->id ]) );
            exit();
        }
    break;
    
    case 'delete':
        if( !$draft->remove() ){
            $this->ww->user->addAlert([
                'level'     =>  'error',
                'message'   =>  "Error, remove canceled",
            ]);
        }
        else 
        {
            $this->ww->user->addAlert([
                'level'     =>  'success',
                'message'   =>  "Draft removed"
            ]);            
            header( 'Location: '.$this->ww->website->getFullUrl('view', [ 'id' => $this->witch("target")->id ]) );
            exit();
        }
    break;    
}

$cancelHref = $this->ww->website->getUrl("view?id=".$this->witch("target")->id);

$this->view();