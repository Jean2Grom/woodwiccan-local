<?php /** @var WW\Module $this */

use WW\Handler\WitchHandler;

$possibleActionsList = [
    'copy-witch',
    'move-witch',
    'edit-priorities',
    'create-new-witch',
    'delete-witch',

    'save-witch-menu-info',
    'save-witch-info',
];

$action = $this->ww->request->param('action');
if( !in_array($action, $possibleActionsList) ){
    $action = false;
}
    
$alerts         = [];
if( !$this->witch("target") )
{
    $alerts[] = [
        'level'     =>  'error',
        'message'   =>  "Undefined Target Witch"
    ];
    
    $this->ww->user->addAlerts($alerts);
    header( 'Location: '.$this->ww->website->getFullUrl() );
    exit();
}

$urlHash = "";
switch( $action )
{
    case 'copy-witch':
        $originWitchId      = $this->ww->request->param('origin-witch', 'post', FILTER_VALIDATE_INT);
        $destinationWitchId = $this->ww->request->param('destination-witch', 'post', FILTER_VALIDATE_INT);
        if( !$originWitchId || !$destinationWitchId )
        {
            $alerts[] = [
                'level'     =>  'error',
                'message'   =>  "Error, data missing"
            ];
            break;
        }
        
        $originWitch        = WitchHandler::fetch( $this->ww, $originWitchId );
        $destinationWitch   = WitchHandler::fetch( $this->ww, $destinationWitchId );        
        if( !$originWitch || !$destinationWitch )
        {
            $alerts[] = [
                'level'     =>  'error',
                'message'   =>  "Error, witch unidentified"
            ];
            break;
        }
        
        if( !$originWitch->copyTo($destinationWitch) )
        {
            $alerts[] = [
                'level'     =>  'error',
                'message'   =>  "Error, copy canceled"
            ];
            break;
        }
        
        $alerts[] = [
            'level'     =>  'success',
            'message'   =>  "Witch was copied"
        ];
        
        $this->ww->user->addAlerts($alerts);        
        header( 'Location: '.$this->ww->website->getFullUrl('view', [ 'id' => $destinationWitch->id ]) );
        exit();    
    break;    
    
    case 'move-witch':
        $originWitchId      = $this->ww->request->param('origin-witch', 'post', FILTER_VALIDATE_INT);
        $destinationWitchId = $this->ww->request->param('destination-witch', 'post', FILTER_VALIDATE_INT);
        if( !$originWitchId || !$destinationWitchId )
        {
            $alerts[] = [
                'level'     =>  'error',
                'message'   =>  "Error, data missing"
            ];            
            break;
        }
        
        $originWitch        = WitchHandler::fetch( $this->ww, $originWitchId );
        $destinationWitch   = WitchHandler::fetch( $this->ww, $destinationWitchId );        
        if( !$originWitch || !$destinationWitch )
        {
            $alerts[] = [
                'level'     =>  'error',
                'message'   =>  "Error, witch unidentified"
            ];
            break;
        }
        
        if( !$originWitch->moveTo($destinationWitch) )
        {
            $alerts[] = [
                'level'     =>  'error',
                'message'   =>  "Error, move canceled"
            ];
            break;
        }
        
        $alerts[] = [
            'level'     =>  'success',
            'message'   =>  "Witch was moved"
        ];
        
        $this->ww->user->addAlerts($alerts);
        header( 'Location: '.$this->ww->website->getFullUrl('view', [ 'id' => $destinationWitch->id ]) );
        exit();    
    break;
    
    case 'edit-priorities':
        $priorities = $this->ww->request->param('priorities', 'post', FILTER_VALIDATE_INT, FILTER_REQUIRE_ARRAY) ?? [];
        
        $errors     = [];
        $success    = [];
        foreach( $priorities as $witchId => $witchPriority ){
            if( !$this->witch("target")->daughters( $witchId )->edit([ 'priority' => $witchPriority ]) ){
                $errors[] = "<strong>".$this->witch("target")->daughters( $witchId )->name."</strong> priority not updated";
            }
            else {
                $success[] = "<strong>".$this->witch("target")->daughters( $witchId )->name."</strong> priority updated";
            }
        }
        
        if( empty($errors) ){
            $alerts[] = [
                'level'     =>  'success',
                'message'   =>  "Priorities updated"
            ];
        }
        elseif( empty($success) ){
            $alerts[] = [
                'level'     =>  'error',
                'message'   =>  "Error, priorities hasn't been updated"
            ];
        }
        else 
        {
            $alerts[] = [
                'level'     =>  'success',
                'message'   => implode('<br/>', $success),
            ];
            
            $alerts[] = [
                'level'     =>  'notice',
                'message'   => implode('<br/>', $errors),
            ];            
        }
    break;
    
    case 'create-new-witch':
        $newWitchData   = [
            'name'      =>  trim($this->ww->request->param('new-witch-name') ?? ""),
            'data'      =>  trim($this->ww->request->param('new-witch-data') ?? ""),
            'priority'  =>  $this->ww->request->param('new-witch-priority', 'POST', FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE) ?? 0,
        ];
        
        if( $newWitchData['name'] === "" )
        {
            $alerts[] = [
                'level'     =>  'error',
                'message'   =>  "Witch name is missing"
            ];
            break;
        }
        
        $newWitch = $this->witch("target")->createDaughter( $newWitchData );
        
        if( !$newWitch )
        {
            $alerts[] = [
                'level'     =>  'error',
                'message'   =>  "Error, new witch wasn't created"
            ];
            break;
        }
        
        $alerts[] = [
            'level'     =>  'success',
            'message'   =>  "New witch created"
        ];
        
        $this->ww->user->addAlerts($alerts);
        header( 'Location: '.$this->ww->website->getFullUrl('view', [ 'id' => $newWitch->id ]) );
        exit();
    break;    
    
    case 'delete-witch':
        if( $this->witch("target")->mother() && $this->witch("target")->delete() )
        {
            $alerts[] = [
                'level'     =>  'success',
                'message'   =>  "Witch removed"
            ];

            $this->ww->user->addAlerts( $alerts );
            header( 'Location: '.$this->ww->website->getFullUrl('view', [ 'id' => $this->witch("target")->mother()->id ]) );
            exit();
        }
        
        $alerts[] = [
            'level'     =>  'error',
            'message'   =>  "Error, witch hasn't been removed",
        ];
    break;
    
    case 'save-witch-menu-info':        
        $witchNewData   = [
            'name'      =>  trim($this->ww->request->param('witch-name') ?? ""),
            'data'      =>  trim($this->ww->request->param('witch-data') ?? ""),
        ];
        
        if( $witchNewData['name'] === "" ){
            $alerts[] = [
                'level'     =>  'error',
                'message'   =>  "Witch name is missing"
            ];
        }
        else if( !$this->witch("target")->edit( $witchNewData ) ){
            $alerts[] = [
                'level'     =>  'error',
                'message'   =>  "Error, witch was not updated"
            ];
        }
        else{
            $alerts[] = [
                'level'     =>  'success',
                'message'   =>  "Witch updated"
            ];
            
        }
    break;
        
    case 'save-witch-info':
        $witchNewData   = [
            'site'      => null,
            'status'    => 0,
            'invoke'    => null,
            'url'       => null,
            'context'   => null,
        ];

        $site       = trim($this->ww->request->param('witch-site') ?? "");        
        if( !empty($site) ){
            $witchNewData['site'] = $site;
        }
        
        $statusArray = $this->ww->request->param('witch-status', 'post', FILTER_VALIDATE_INT, FILTER_REQUIRE_ARRAY);
        if( $statusArray )
        {
            if( empty($site) && !empty($statusArray[ 'no-site-selected' ]) ){
                $witchNewData['status'] = $statusArray[ 'no-site-selected' ];
            }
            elseif( !empty($statusArray[ $site ]) ){
                $witchNewData['status'] = $statusArray[ $site ];                
            }
            
        }
        
        if( !empty($site) )
        {
            $invokeArray = $this->ww->request->param('witch-invoke', 'post', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
            if( $invokeArray && !empty($invokeArray[ $site ]) ){
                $witchNewData['invoke'] = $invokeArray[ $site ];
            }
            
            if( !empty($witchNewData['invoke']) )
            {
                $contextArray = $this->ww->request->param('witch-context', 'post', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
                if( $contextArray && !empty($contextArray[ $site ]) ){
                    $witchNewData['context'] = $contextArray[ $site ];
                }

                $autoUrl        = $this->ww->request->param('witch-automatic-url', 'POST', FILTER_VALIDATE_BOOL, FILTER_NULL_ON_FAILURE);
                $customFullUrl  = $this->ww->request->param('witch-full-url', 'POST', FILTER_VALIDATE_BOOL, FILTER_NULL_ON_FAILURE);
                $customUrl      = $this->ww->request->param('witch-url');

                if( !$autoUrl )
                {
                    $url    =   "";
                    if( !$customFullUrl )
                    {
                        if( $this->witch("target")->mother() ){
                            $url .= $this->witch("target")->mother()->getClosestUrl( $site );
                        }

                        if( substr($url, -1) != '/' 
                                && substr($customUrl, 0, 1) != '/'  
                        ){
                            $url .= '/';
                        }
                    }

                    $url    .=  $customUrl;

                    $witchNewData['url'] = $url;
                }
            }
        }
        
        $edit = $this->witch("target")->edit( $witchNewData );
        
        if( $edit === 0 ){
            $alerts[] = [
                'level'     =>  'warning',
                'message'   =>  "No update",
            ];
        }
        elseif( !$edit ){
            $alerts[] = [
                'level'     =>  'error',
                'message'   =>  "Error, witch was not updated"
            ];
        }
        else {
            $alerts[] = [
                'level'     =>  'success',
                'message'   =>  "Witch updated"
            ];            
        }
    break;    
}

$this->ww->user->addAlerts($alerts);
header( 'Location: '.$this->ww->website->getFullUrl('view', [ 'id' => $this->witch("target")->id ]).$urlHash );
exit();
