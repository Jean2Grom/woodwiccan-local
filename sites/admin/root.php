<?php /** @var WW\Module $this */

use WW\Tools;

$witch = $this->witch("root") ?? $this->witch();

switch( $action = Tools::filterAction( 
    $this->ww->request->param('action'),
    [
        'edit-priorities',
    ]
) ){
    case 'edit-priorities':
        $priorities =  $this->ww->request->param('priorities', 'post',FILTER_VALIDATE_INT, FILTER_REQUIRE_ARRAY);
        
        $errors     = [];
        $success    = [];
        $daughters  = $witch->daughters();
        foreach( $priorities as $witchId => $witchPriority )
        {
            $editResult = $daughters[ $witchId ]->edit([ 
                'priority' => $witchPriority 
            ])->save();
            
            if( $editResult === false ){
                $errors[] = "<strong>".$daughters[ $witchId ]->name."</strong> priority update failed";
            }
            elseif( $editResult ) {
                $success[] = "<strong>".$daughters[ $witchId ]->name."</strong> priority updated";
            }
        }
        
        if( empty($errors) && empty($success) ){
            $this->ww->user->addAlert([
                'level'     =>  'warning',
                'message'   =>  "No priority update"
            ]);
        }
        elseif( !empty($errors) && !empty($success) ){
            $this->ww->user->addAlerts([
                [
                    'level'     =>  'warning',
                    'message'   =>  "Some errors occured"
                ],
                [
                    'level'     =>  'error',
                    'message'   =>  implode('<br/>', $errors),
                ],
                [
                    'level'     =>  'notice',
                    'message'   =>  implode('<br/>', $success),
                ],
            ]);
        }
        elseif( !empty($errors) ){
            $this->ww->user->addAlerts([
                [
                    'level'     =>  'error',
                    'message'   =>  "Errors occured"
                ],
                [
                    'level'     =>  'notice',
                    'message'   =>  implode('<br/>', $errors),
                ],
            ]);
        }
        elseif( !empty($success) ){
            $this->ww->user->addAlerts([
                [
                    'level'     =>  'success',
                    'message'   =>  "Priorities has been updated"
                ],
                [
                    'level'     =>  'notice',
                    'message'   =>  implode('<br/>', $success),
                ],
            ]);
        }
    break;
}

$this->view();
