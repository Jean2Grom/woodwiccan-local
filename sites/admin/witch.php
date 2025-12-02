<?php /**  @var WW\Module $this */
namespace WW;

use WW\DataAccess\WitchDataAccess;
use WW\Handler\CauldronHandler;
use WW\Handler\WitchHandler;


if( !$this->witch("target") ){
    $this->ww->user->addAlert([
        'level'     =>  'error',
        'message'   =>  "Witch not found"
    ]);
    
    header('Location: '.$this->ww->website->getRootUrl() );
    exit();
}

switch( $action = Tools::filterAction( 
    $this->ww->request->param('action'),
    [
        'move-witch',
        'copy-witch',
        'delete-witch',
        'edit-witch-menu-info',
        'edit-witch-info',
        'edit-priorities',
        'remove-cauldron',
        'create-cauldron',
        'import-cauldron',
        'cauldron-add-witch',
        'cauldron-add-new-witch',
        'switch-cauldron-main-witch',
        'remove-cauldron-witch',
        'delete-cauldron-witch',
    ]
) ){
    case 'move-witch':
        $originWitchId      = $this->ww->request->param('origin-witch', 'post', FILTER_VALIDATE_INT);
        $destinationWitchId = $this->ww->request->param('destination-witch', 'post', FILTER_VALIDATE_INT);
        if( !$originWitchId || !$destinationWitchId )
        {
            $this->ww->user->addAlert([
                'level'     =>  'error',
                'message'   =>  "Error, data missing"
            ]);
            break;
        }
        
        $originWitch        = WitchHandler::fetch( $this->ww, $originWitchId );
        $destinationWitch   = WitchHandler::fetch( $this->ww, $destinationWitchId );        
        if( !$originWitch || !$destinationWitch )
        {
            $this->ww->user->addAlert([
                'level'     =>  'error',
                'message'   =>  "Error, witch unidentified"
            ]);
            break;
        }
        
        if( !$originWitch->moveTo($destinationWitch) )
        {
            $this->ww->user->addAlert([
                'level'     =>  'error',
                'message'   =>  "Error, move canceled"
            ]);
            break;
        }
        
        $this->ww->user->addAlert([
            'level'     =>  'success',
            'message'   =>  "Witch was moved"
        ]);
        
        header( 'Location: '.$this->ww->website->getFullUrl('view', [ 'id' => $destinationWitch->id ]) );
        exit();    
    break;
    
    case 'copy-witch':
        $originWitchId      = $this->ww->request->param('origin-witch', 'post', FILTER_VALIDATE_INT);
        $destinationWitchId = $this->ww->request->param('destination-witch', 'post', FILTER_VALIDATE_INT);
        if( !$originWitchId || !$destinationWitchId )
        {
            $this->ww->user->addAlert([
                'level'     =>  'error',
                'message'   =>  "Error, data missing"
            ]);
            break;
        }
        
        $originWitch        = WitchHandler::fetch( $this->ww, $originWitchId );
        $destinationWitch   = WitchHandler::fetch( $this->ww, $destinationWitchId );        
        if( !$originWitch || !$destinationWitch )
        {
            $this->ww->user->addAlert([
                'level'     =>  'error',
                'message'   =>  "Error, witch unidentified"
            ]);
            break;
        }
        
        if( !$originWitch->copyTo($destinationWitch) )
        {
            $this->ww->user->addAlert([
                'level'     =>  'error',
                'message'   =>  "Error, copy canceled"
            ]);
            break;
        }
        
        $this->ww->user->addAlert([
            'level'     =>  'success',
            'message'   =>  "Witch was copied"
        ]);
        
        header( 'Location: '.$this->ww->website->getFullUrl('view', [ 'id' => $destinationWitch->id ]) );
        exit();    
    break;    

    case 'delete-witch':
        $motherId = $this->witch("target")->mother()?->id;
        if( $motherId && $this->witch("target")->delete() )
        {
            $this->ww->user->addAlert([
                'level'     =>  'success',
                'message'   =>  "Witch removed"
            ]);

            header( 'Location: '.$this->ww->website->getFullUrl('view', [ 'id' => $motherId ]) );
            exit();
        }
        
        $this->ww->user->addAlert([
            'level'     =>  'error',
            'message'   =>  "Error, witch hasn't been removed",
        ]);
    break;
    
    case 'edit-witch-menu-info':
        $witchNewData   = [
            'name'      =>  trim($this->ww->request->param('witch-name') ?? ""),
            'data'      =>  trim($this->ww->request->param('witch-data') ?? ""),
        ];
        
        if( $witchNewData['name'] === "" ){
            $this->ww->user->addAlert([
                'level'     =>  'error',
                'message'   =>  "Witch name is missing"
            ]);
        }
        elseif( is_null(
            $save = $this->witch("target")->edit( $witchNewData )->save()
        ) ){
            $this->ww->user->addAlert([
                'level'     =>  'warning',
                'message'   =>  "No update"
            ]);
        }
        elseif( !$save ){
            $this->ww->user->addAlert([
                'level'     =>  'error',
                'message'   =>  "Error, witch was not updated"
            ]);
        }
        else {
            $this->ww->user->addAlert([
                'level'     =>  'success',
                'message'   =>  "Witch updated"
            ]);
        }
    break;

    case 'edit-witch-info':
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
        
        if( is_null( 
            $edit = $this->witch("target")->edit( $witchNewData )->save()
        ) ){
            $this->ww->user->addAlert([
                'level'     =>  'warning',
                'message'   =>  "No update",
            ]);
        }
        elseif( !$edit ){
            $this->ww->user->addAlert([
                'level'     =>  'error',
                'message'   =>  "Error, witch was not updated"
            ]);
        }
        else {
            $this->ww->user->addAlert([
                'level'     =>  'success',
                'message'   =>  "Witch updated"
            ]);
        }
    break;    
    
    case 'edit-priorities':
        $priorities = $this->ww->request->param('priorities', 'post', FILTER_VALIDATE_INT, FILTER_REQUIRE_ARRAY) ?? [];
        
        $errors     = [];
        $success    = [];
        foreach( $priorities as $witchId => $witchPriority )
        {
            $save = $this->witch("target")->daughter( $witchId )->edit([ 
                'priority' => $witchPriority 
            ])->save();

            if( $save === false ){
                $errors[] = "<strong>".$this->witch("target")->daughter( $witchId )->name."</strong> priority not updated";
            }
            elseif( $save ){
                $success[] = "<strong>".$this->witch("target")->daughter( $witchId )->name."</strong> priority updated";
            }
        }
        
        if( empty($errors) && empty($success) ){
            $this->ww->user->addAlert([
                'level'     =>  'warning',
                'message'   =>  "No priority update"
            ]);
        }
        elseif( empty($errors) ){
            $this->ww->user->addAlert([
                'level'     =>  'success',
                'message'   =>  "Priorities updated"
            ]);
        }
        elseif( empty($success) ){
            $this->ww->user->addAlert([
                'level'     =>  'error',
                'message'   =>  "Error, priorities hasn't been updated"
            ]);
        }
        else {
            $this->ww->user->addAlerts([
                [
                    'level'     =>  'success',
                    'message'   =>  implode('<br/>', $success),
                ],
                [
                    'level'     =>  'notice',
                    'message'   =>  implode('<br/>', $errors),
                ],
            ]);
        }
    break;
    
    case 'remove-cauldron':
        if( !$this->witch("target")->cauldron() ){
            $this->ww->user->addAlert([
                'level'     =>  'error',
                'message'   =>  "Error, cauldron wasn't found",
            ]);
        }
        else if( !$this->witch("target")->removeCauldron() ){
            $this->ww->user->addAlert([
                'level'     =>  'error',
                'message'   =>  "Error occurred, cauldron removal was canceled",
            ]);
        }
        else {
            $this->ww->user->addAlert([
                'level'     =>  'success',
                'message'   =>  "Cauldron removed"
            ]);
        }
    break;

    case 'create-cauldron':
        $recipe      = $this->ww->request->param('witch-cauldron-recipe') ?? "folder";
        if( !in_array($recipe, array_keys( $this->ww->configuration->recipes() )) )
        {
            $this->ww->user->addAlert([
                'level'     =>  'error',
                'message'   =>  "Error, a valid cauldron recipe is missing",
            ]);
            break;
        }         

        $folderCauldron = CauldronHandler::getStorageStructure($this->ww, $this->ww->website->site, $recipe);
        if( !$folderCauldron )
        {
            $this->ww->user->addAlert([
                'level'     =>  'error',
                'message'   =>  "Error, cauldron storage recipe can't be found",
            ]);
            break;
        }

        $cauldron = $this->ww->configuration
                            ->recipe( $recipe )
                            ->factory( $this->witch("target")->name );
                            
        $cauldron->status = Cauldron::STATUS_DRAFT;
        
        if( !$cauldron 
            || !$folderCauldron->addCauldron( $cauldron ) 
            || !$cauldron->save() 
            || !$this->witch("target")->edit([ 'cauldron' => $cauldron->id ])->save() 
        ){
            $this->ww->user->addAlert([
                'level'     =>  'error',
                'message'   =>  "Error occurred during cauldron creation",
            ]);
            break;
        }

        $this->ww->user->addAlert([
            'level'     =>  'success',
            'message'   =>  "Cauldron was created",
        ]);

        header('Location: '.$this->ww->website->getUrl("cauldron?id=".$this->witch("target")->id) );
        exit();    
    break;

    case 'import-cauldron':
        $importedCauldronWitchId = $this->ww->request->param('imported-cauldron-witch', null, FILTER_VALIDATE_INT);
        if( !$importedCauldronWitchId )
        {
            $this->ww->user->addAlert([
                'level'     =>  'error',
                'message'   =>  "Error, import cauldron witch isn't identified",
            ]);
            break;
        }

        $importedCauldronWitch = WitchHandler::fetch( $this->ww, $importedCauldronWitchId );
        if( !$importedCauldronWitch )
        {
            $this->ww->user->addAlert([
                'level'     =>  'error',
                'message'   =>  "Error, import cauldron witch couldn't be loaded",
            ]);
            break;
        }
        elseif( !$importedCauldronWitch->hasCauldron() || !$importedCauldronWitch->cauldronId )
        {
            $this->ww->user->addAlert([
                'level'     =>  'error',
                'message'   =>  "Error, can't find import cauldron",
            ]);
            break;
        }
        elseif( !$this->witch("target")->edit([ 'cauldron' => $importedCauldronWitch->cauldronId ])->save() )
        {
            $this->ww->user->addAlert([
                'level'     =>  'error',
                'message'   =>  "Error occurred, cauldron import failed",
            ]);
            break;
        }

        $this->ww->user->addAlert([
            'level'     =>  'success',
            'message'   =>  "Cauldron was imported",
        ]);
    break;
    
    case 'cauldron-add-witch':
        if( !$this->witch("target")->cauldron() )
        {
            $this->ww->user->addAlert([
                'level'     =>  'error',
                'message'   =>  "Error, no cauldron identified"
            ]);
            break;
        }
        
        $id = $this->ww->request->param('cauldron-new-witch-id', 'post', FILTER_VALIDATE_INT);
        if( !$id )
        {
            $this->ww->user->addAlert([
                'level'     =>  'error',
                'message'   =>  "Error, no witch identified"
            ]);
            break;
        }
        
        $witch = $this->ww->cairn->searchById($id) ?? WitchHandler::fetch($this->ww, $id);
        if( !$witch )
        {
            $this->ww->user->addAlert([
                'level'     =>  'error',
                'message'   =>  "Error, chosen witch unidentified"
            ]);
            break;
        }

        if( !$witch->edit([ 'cauldron' => $this->witch("target")->cauldronId ])->save() )
        {
            $this->ww->user->addAlert([
                'level'     =>  'error',
                'message'   =>  "Error, something went wrong"
            ]);
            break;
        }

        $this->ww->user->addAlert([
            'level'     =>  'success',
            'message'   =>  "Cauldron added to witch"
        ]);

        header( 'Location: '.$this->ww->website->getFullUrl('view', [ 'id' => $witch->id ]) );
        exit();
    break;
    
    case 'cauldron-add-new-witch':
        if( !$this->witch("target")->cauldron() )
        {
            $this->ww->user->addAlert([
                'level'     =>  'error',
                'message'   =>  "Error, no cauldron identified"
            ]);
            break;
        }
        
        $id = $this->ww->request->param('cauldron-new-witch-id', 'post', FILTER_VALIDATE_INT);
        if( !$id )
        {
            $this->ww->user->addAlert([
                'level'     =>  'error',
                'message'   =>  "Error, no witch identified"
            ]);
            break;
        }
        
        $witch = $this->ww->cairn->searchById($id) ?? WitchHandler::fetch($this->ww, $id);
        if( !$witch )
        {
            $this->ww->user->addAlert([
                'level'     =>  'error',
                'message'   =>  "Error, chosen witch unidentified"
            ]);
            break;
        }
        
        $params   = [
            'name'          =>  $this->witch("target")->name,
            'data'          =>  $this->witch("target")->data,
            'cauldron'      =>  $this->witch("target")->cauldronId 
        ];
        
        $newWitch = $witch->newDaughter( $params );
        $newWitch->save();
        
        if( !$newWitch )
        {
            $this->ww->user->addAlert([
                'level'     =>  'error',
                'message'   =>  "Error, new witch wasn't created"
            ]);
            break;
        }
        
        $this->ww->user->addAlert([
            'level'     =>  'success',
            'message'   =>  "New cauldron's witch created"
        ]);
        
        header( 'Location: '.$this->ww->website->getFullUrl('view', [ 'id' => $newWitch->id ]) );
        exit();
    break;
    
    case 'switch-cauldron-main-witch':        
        if( !$this->witch("target")->cauldron() )
        {
            $this->ww->user->addAlert([
                'level'     =>  'error',
                'message'   =>  "Error, no cauldron identified"
            ]);
            break;
        }

        $id = $this->ww->request->param('main', 'post', FILTER_VALIDATE_INT);
        
        $params     = []; 
        $conditions = []; 
        $match      = false;
        foreach( $this->witch("target")->cauldron()->witches() as $witch )
        {
            $conditions[] = [ 'id' => $witch->id ]; 

            if( $id === $witch->id )
            {
                $params[]   = [ 'cauldron_priority' => 200 ]; 
                $match      = true;
                continue;
            }

            $params[] = [ 'cauldron_priority' => 100 ]; 
        }

        if( !$match )
        {
            $this->ww->user->addAlert([
                'level'     =>  'error',
                'message'   =>  "Cauldron's main witch wasn't identified"
            ]);
            break;
        }

        $updatesResult = WitchDataAccess::updates($this->ww, $params, $conditions);

        if( $updatesResult === false )
        {
            $this->ww->user->addAlert([
                'level'     =>  'error',
                'message'   =>  "Cauldron's main witch update failed"
            ]);
            break;
        }
        elseif(  $updatesResult === 0  )
        {
            $this->ww->user->addAlert([
                'level'     =>  'warning',
                'message'   =>  "Cauldron's main witch update had no effect"
            ]);
            break;
        }

        foreach( $this->witch("target")->cauldron()->witches() as $witch )
        {
            if( $id === $witch->id )
            {
                $witch->cauldronPriority = 200;
                $witch->cauldron_priority = 200;
                continue;
            }

            $witch->cauldronPriority = 100;
        }

        $this->witch("target")->cauldron()->orderWitches();
        
        $this->ww->user->addAlert([
            'level'     =>  'success',
            'message'   =>  "Cauldron's main witch updated"
        ]);        
    break;

    case 'remove-cauldron-witch':
        if( !$this->witch("target")->cauldron() )
        {
            $this->ww->user->addAlert([
                'level'     =>  'error',
                'message'   =>  "Error, no cauldron identified"
            ]);
            break;
        }
        
        $id = $this->ww->request->param('cauldron-witch-id', 'post', FILTER_VALIDATE_INT);
        if( !$id )
        {
            $this->ww->user->addAlert([
                'level'     =>  'error',
                'message'   =>  "Error, no witch identified"
            ]);
            break;
        }
        
        $witchToRemove      = false;
        $witchToRemoveKey   = null;
        foreach( $this->witch("target")->cauldron()->witches() as $key => $witch ){
            if( $id === $witch->id )
            {
                $witchToRemove      = $witch;
                $witchToRemoveKey   = $key;
                break;
            }
        }

        if( !$witchToRemove )
        {
            $this->ww->user->addAlert([
                'level'     =>  'error',
                'message'   =>  "Error, chosen witch unidentified"
            ]);
            break;
        }

        $updatesResult = WitchDataAccess::update($this->ww, [ 'cauldron' => 0, 'cauldron_priority' => 0 ], [ 'id' => $id ]);

        if( $updatesResult === false )
        {
            $this->ww->user->addAlert([
                'level'     =>  'error',
                'message'   =>  "Cauldron's witch removal failed"
            ]);
            break;
        }
        elseif(  $updatesResult === 0  )
        {
            $this->ww->user->addAlert([
                'level'     =>  'warning',
                'message'   =>  "Cauldron's witch update had no effect"
            ]);
            break;
        }

        unset($this->witch("target")->cauldron()->witches[ $witchToRemoveKey ]);
        
        $this->ww->user->addAlert([
            'level'     =>  'success',
            'message'   =>  "Cauldron's witch removed"
        ]);
    break;

    case 'delete-cauldron-witch':
        if( !$this->witch("target")->cauldron() )
        {
            $this->ww->user->addAlert([
                'level'     =>  'error',
                'message'   =>  "Error, no cauldron identified"
            ]);
            break;
        }
        
        $id = $this->ww->request->param('cauldron-witch-id', 'post', FILTER_VALIDATE_INT);
        if( !$id )
        {
            $this->ww->user->addAlert([
                'level'     =>  'error',
                'message'   =>  "Error, no witch identified"
            ]);
            break;
        }
        
        $witchToRemove      = false;
        $witchToRemoveKey   = null;
        foreach( $this->witch("target")->cauldron()->witches() as $key => $witch ){
            if( $id === $witch->id )
            {
                $witchToRemove      = $witch;
                $witchToRemoveKey   = $key;
                break;
            }
        }

        if( !$witchToRemove )
        {
            $this->ww->user->addAlert([
                'level'     =>  'error',
                'message'   =>  "Error, chosen witch unidentified"
            ]);
            break;
        }

        if( !$witchToRemove->delete() )
        {
            $this->ww->user->addAlert([
                'level'     =>  'error',
                'message'   =>  "Cauldron's witch removal failed"
            ]);
            break;
        }

        unset($this->witch("target")->cauldron()->witches[ $witchToRemoveKey ]);
        
        $this->ww->user->addAlert([
            'level'     =>  'success',
            'message'   =>  "Cauldron's witch deleted"
        ]);
    break;
}

$sites  = $this->ww->website->sitesRestrictions;
if( !$sites ){
    $sites = array_keys($this->ww->configuration->sites);
}

$websitesList   = [];
foreach( $sites as $site ){
    if( $site === $this->ww->website->name ){
        $website = $this->ww->website;
    }
    else {
        $website = new Website( $this->ww, $site );
    }
    
    if( $website->site === $website->name ) {
        $websitesList[ $site ] = $website;
    }
}

$breadcrumb         = [];
$breadcrumbWitch    = $this->witch("target");
while( !empty($breadcrumbWitch) )
{
    if( $breadcrumbWitch  === $this->witch("target") ){
        $url    = "javascript: location.reload();";
    }
    else {
        $url    = $this->witch->url([ 'id' => $breadcrumbWitch->id ]);
    }
    
    $breadcrumb[]   = [
        "name"  => $breadcrumbWitch->name,
        "data"  => $breadcrumbWitch->data,
        "href"  => $url,
    ];

    if( $this->witch('root') === $breadcrumbWitch ){
        break;
    }
    
    $breadcrumbWitch    = $breadcrumbWitch->mother();
}

$this->addContextVar( 'breadcrumb', array_reverse($breadcrumb) );
$this->display();