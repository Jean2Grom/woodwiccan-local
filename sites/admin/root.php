<?php /** @var WW\Module $this */

use WW\Tools;

switch( $action = Tools::filterAction( 
    $this->ww->request->param('action'),
    [
        'edit-data',
        'edit-priorities',

        ]
) ){
    case 'edit-data':
        $data = $this->ww->request->param('data');
        if( $data == $this->witch->data ){
            $this->ww->user->addAlert([
                'level'     =>  'warning',
                'message'   =>  "Description identique"
            ]);
        }
        elseif( $this->witch->edit([ 'data' => $data ]) ){
            $this->ww->user->addAlert([
                'level'     =>  'success',
                'message'   =>  "Description mise à jour"
            ]);
        }
        else {
            $this->ww->user->addAlert([
                'level'     =>  'error',
                'message'   =>  "Une erreur est survenue, la description n'a pas été mise à jour."
            ]);
        }
    break;
    
    case 'edit-priorities':
        $priorities =  $this->ww->request->param('priorities', 'post',FILTER_VALIDATE_INT, FILTER_REQUIRE_ARRAY);
        
        $errors     = [];
        $success    = [];
        $daughters  = $this->getDaughters();
        foreach( $priorities as $witchId => $witchPriority )
        {
            $editResult = $daughters[ $witchId ]->edit([ 'priority' => $witchPriority ]);
            
            if( $editResult === false ){
                $errors[] = "La priorité de <strong>".$daughters[ $witchId ]->name."</strong> n'a pas été mise à jour.";
            }
            elseif( $editResult > 0 ) {
                $success[] = "La priorité de <strong>".$daughters[ $witchId ]->name."</strong> a été mise à jour.";
            }
        }
                
        if( empty($errors) && empty($success) ){
            $this->ww->user->addAlert([
                'level'     =>  'warning',
                'message'   =>  "Aucune modification des priorités"
            ]);
        }
        elseif( !empty($errors) && !empty($success) ){
            $this->ww->user->addAlerts([
                [
                    'level'     =>  'warning',
                    'message'   =>  "Des erreurs sont survenues"
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
                    'message'   =>  "Une erreur est survenue, les priorités n'ont pas été mise à jour."
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
                    'message'   =>  "Les priorités ont été mises à jour."
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
