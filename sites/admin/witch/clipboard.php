<?php /** @var WW\Module $this */

use WW\Handler\WitchHandler;
use WW\Tools;

if( !$this->witch('origin') || !$this->witch('destination') )
{
    $this->ww->user->addAlert([
        'level'     =>  'error',
        'message'   =>  "Error, witch unidentified"
    ]);
    
    header( 'Location: '.$this->ww->website->getFullUrl() );
    exit();
}

$destId = $this->witch('destination')->id;

// Has position ?
$orderedIds     = [];
$positionRef    = $this->ww->request->param(
    'positionRef', 
    null, 
    FILTER_VALIDATE_INT
);
$positionRel    = Tools::filterAction(
    $this->ww->request->param('positionRel'),
    [
        'before', 
        'after',
    ], 
);

if( $positionRef && $positionRel ){
    foreach( $this->witch('destination')->daughters() as $daughter ){
        if( $daughter->id === $positionRef ){
            switch( $positionRel ){
                case 'before':
                    $orderedIds[] = $this->witch('origin')->id;
                    $orderedIds[] = $daughter->id;
                break;

                case 'after':
                    $orderedIds[] = $daughter->id;
                    $orderedIds[] = $this->witch('origin')->id;
                break;
            }
        }
        elseif( $daughter !== $this->witch('origin') ){
            $orderedIds[] = $daughter->id;
        }
    }
}

switch( $action = Tools::filterAction(
    $this->ww->request->param('action'),
    [
        'move', 
        'copy',
    ], 
) ){
    case 'move':
        $moveAction = !$this->witch('destination')->isMotherOf( $this->witch('origin') );

        if( $moveAction ){
            if( !$this->witch('origin')->moveTo($this->witch('destination'), $orderedIds) ){
                $this->ww->user->addAlert([
                    'level'     =>  'error',
                    'message'   =>  "Error, move canceled"
                ]);
            }
            else {
                $this->ww->user->addAlert([
                    'level'     =>  'success',
                    'message'   =>  "Witch was moved"
                ]);
            }
        }
        elseif( !$orderedIds ){
            $this->ww->user->addAlert([
                'level'     =>  'warning',
                'message'   =>  "No impact"
            ]);
        }
        else 
        {
            $reorder = WitchHandler::setPriorities($this->witch('destination'), $orderedIds);

            if( $reorder === 0 ){
                $this->ww->user->addAlert([
                    'level'     =>  'warning',
                    'message'   =>  "No impact"
                ]);
            }
            elseif( !$reorder ){
                $this->ww->user->addAlert([
                    'level'     =>  'error',
                    'message'   =>  "Error, reorder canceled"
                ]);
            }
            else {
                $this->ww->user->addAlert([
                    'level'     =>  'success',
                    'message'   =>  "Witches reordered"
                ]);
            }
        }
    break;

    case 'copy':
        if( !$newWitch = $this->witch('origin')->copyTo($this->witch('destination'), $orderedIds) ){
            $this->ww->user->addAlert([
                'level'     =>  'error',
                'message'   =>  "Error, copy canceled"
            ]);
        }
        else 
        {
            $this->ww->user->addAlert([
                'level'     =>  'success',
                'message'   =>  "Witch was copied"
            ]);
            $destId = $newWitch->id;
        }
        
    break;    
}

header( 'Location: '.$this->ww->website->getFullUrl('view', [ 'id' => $destId ]) );
exit();