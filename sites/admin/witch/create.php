<?php 
/** @var WW\Module $this */
namespace WW;

use WW\Handler\CauldronHandler;
use WW\Handler\WitchHandler;
//use WW\Cauldron;
//use WW\Tools;
//use WW\Website;

if( !$this->witch("target") ){
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
    if( $site == $this->ww->website->name ){
        $website = $this->ww->website;
    }
    else {
        $website = new Website( $this->ww, $site );
    }
    
    if( $website->site == $website->name ) {
        $websitesList[ $site ] = $website;
    }
}

$status = [ "global" => $this->ww->configuration->read("global", "status") ];
foreach( $websitesList as $site => $website ){
    $status[ $site ] = $website->status;
}

$modules = [];
foreach( $websitesList as $site => $website ){
    $modules[ $site ] = $website->listModules();
}

$this->ww->dump( $_POST );

switch(Tools::filterAction(
    $this->ww->request->param('action'),
    [
        'create-new-witch',
    ], 
))
{
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
                $url    .=  trim( $this->witch("target")->getClosestUrl($site), '/' );
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
            $newCauldron = CauldronHandler::createFromData($this->ww, [
                'name'      =>  $params['name'],
                'recipe'    =>  $recipe,
                'status'    =>  Cauldron::STATUS_DRAFT,
            ]);

            if( !$folderCauldron 
                || !$newCauldron 
                || !$folderCauldron->addCauldron( $newCauldron ) 
                || !$newCauldron->save() 
            ){
                $this->ww->user->addAlert([
                    'level'     =>  'warning',
                    'message'   =>  "Warning, Cauldron creation has failed",
                ]);
                break;
            }

            $params['cauldron'] = $newCauldron->id;
        }

        $newWitch = $this->witch("target")->createDaughter( $params );

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

        $this->ww->db->commit();
        header( 'Location: '.$this->ww->website->getFullUrl($url, [ 'id' => $newWitch->id ]) );
        exit();
    break;    
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
$this->view();