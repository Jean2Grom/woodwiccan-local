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
