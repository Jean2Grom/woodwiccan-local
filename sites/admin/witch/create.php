<?php /** @var WW\Module $this */

namespace WW;

use WW\Handler\CauldronHandler;
use WW\Handler\WitchHandler;

$destWitch = $this->witch("target") ?? $this->witch("root");

if( !$destWitch ){
    $this->ww->user->addAlert([
        'level'     =>  'error',
        'message'   =>  "Witch not found"
    ]);
    
    header('Location: '.$this->ww->website->getRootUrl() );
    exit();
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

$status = [ "global" => $this->ww->configuration->read("global", "status") ];
foreach( $websitesList as $site => $website ){
    $status[ $site ] = $website->status();
}

$modules = [];
foreach( $websitesList as $site => $website ){
    $modules[ $site ] = $website->listModules();
}

switch( $action = Tools::filterAction(
    $this->ww->request->param('action'),
    [
        'create-new-witch',
    ], 
) ){
    case 'create-new-witch':
        $params         = [];
        $params['name'] = trim($this->ww->request->param('new-witch-name') ?? "");
        if( !$params['name'] )
        {
            $this->ww->user->addAlert([
                'level'     =>  'error',
                'message'   =>  "Witch name is missing"
            ]);
            break;
        }

        $data           = trim($this->ww->request->param('new-witch-data') ?? "");
        if( $data ){
            $params['data'] = $data;
        }

        $priority = $this->ww->request->param('new-witch-priority', 'POST', FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);
        if( !is_null($priority) ){
            $params['priority'] = $priority;
        }

        $status = $this->ww->request->param('new-witch-status', 'POST', FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);
        if( !is_null($status) ){
            $params['status'] = $status;
        }

        $site = trim($this->ww->request->param('new-witch-site') ?? "");
        if( in_array($site, $sites) ){
            $params['site'] = $site;
        }

        $invoke = trim($this->ww->request->param('new-witch-invoke') ?? "");
        if( $params['site'] && in_array($invoke, $modules[ $site ]) ){
            $params['invoke'] = $invoke;
        }

        $autoUrl        = $this->ww->request->param('new-witch-automatic-url', 'POST', FILTER_VALIDATE_BOOL, FILTER_NULL_ON_FAILURE);
        $customFullUrl  = $this->ww->request->param('new-witch-full-url', 'POST', FILTER_VALIDATE_BOOL, FILTER_NULL_ON_FAILURE);
        $customUrl      = $this->ww->request->param('new-witch-url');
        
        if( $params['invoke'] && !$autoUrl )
        {
            $url    =   "";
            if( !$customFullUrl )
            {
                $url    .=  trim( $destWitch->getClosestUrl($site), '/' );
                $url    .=  '/';
            }
            $url    .=  trim( $customUrl, '/' );

            $params['url'] = trim( $url, '/' );
        }


        $importCauldronWitchId  = $this->ww->request->param('imported-cauldron-witch');
        $recipe                 = $this->ww->request->param('new-witch-cauldron-recipe');

        // Cauldron importation
        if( $importCauldronWitchId )
        {
            $importCauldronWitch = WitchHandler::fetch( $this->ww, $importCauldronWitchId );

            if( !$importCauldronWitch 
                || !$importCauldronWitch->hasCauldron()
                || !$importCauldronWitch->cauldronId
            ){
                $this->ww->user->addAlert([
                    'level'     =>  'Error',
                    'message'   =>  "Cauldron couldn't be imported",
                ]);
                break;
            }

            $params['cauldron'] = $importCauldronWitch->cauldronId;    
        }
        // Cauldron creation
        elseif( $recipe && in_array($recipe, array_keys( $this->ww->configuration->recipes() )) )
        {
            $folderCauldron = CauldronHandler::getStorageStructure($this->ww, 
                $this->ww->website->site, 
                $recipe
            );
            $newCauldron    = $this->ww->configuration
                                ->recipe( $recipe )
                                ->factory( $params['name'] );

            if( !$folderCauldron || !$newCauldron ){
                $this->ww->user->addAlert([
                    'level'     =>  'warning',
                    'message'   =>  "Warning, Cauldron creation has failed",
                ]);
                break;
            }

            $newCauldron->status = Cauldron::STATUS_DRAFT;

            if( !$folderCauldron->addCauldron( $newCauldron ) || !$newCauldron->save() ){
                $this->ww->user->addAlert([
                    'level'     =>  'warning',
                    'message'   =>  "Warning, Cauldron creation has failed",
                ]);
                break;
            }

            $params['cauldron'] = $newCauldron->id;
        }

        $newWitch = $destWitch->newDaughter( $params );
        $newWitch->save();

        if( !$newWitch ){
            $this->ww->user->addAlert([
                'level'     =>  'error',
                'message'   =>  "Error, new witch wasn't created"
            ]);
            break;
        }
        
        $this->ww->user->addAlert([
            'level'     =>  'success',
            'message'   =>  "New witch created"
        ]);

        if( isset($newCauldron) ){
            $url = "cauldron";
        }
        else {
            $url = "view";
        }

        header( 'Location: '.$this->ww->website->getFullUrl($url, [ 'id' => $newWitch->id ]) );
        exit();
    break;    
}

$breadcrumb         = [];
$breadcrumbWitch    = $destWitch;
while( !empty($breadcrumbWitch) )
{
    if( $breadcrumbWitch  === $destWitch ){
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
$this->view();