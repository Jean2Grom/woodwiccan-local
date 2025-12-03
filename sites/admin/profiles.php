<?php /** @var WW\Module $this */

use WW\User\Profile;
use WW\Website;
use WW\Tools;

switch( $action = Tools::filterAction( 
    $this->ww->request->param('action'),
    [
        'create-profile',
        'edit-profile',
        'delete-profile',
    ]
) ){
    case 'create-profile':
        $profileData = [
            'name'      =>  trim($this->ww->request->param('profile-name') ?? ""),
        ];
        
        if( empty($profileData['name']) )
        {
            $this->ww->user->addAlert([
                'level'     =>  'error',
                'message'   =>  "Name is mandatory, creation canceled"
            ]);
            break;
        }
        
        $site                   = trim($this->ww->request->param('profile-site') ?? "");
        $profileData['site']    = $site;
        
        $policyIds  = $this->ww->request->param('policy-id', 'post', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY) ?? []; 
        $witches    = $this->ww->request->param('policy-witch-id', 'post', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY) ?? [];
        $custom     = $this->ww->request->param('policy-custom', 'post', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY) ?? [];
        
        $modulesRaw = $this->ww->request->param('policy-module', 'post', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY) ?? []; 
        $modules    = $modulesRaw[ $site ] ?? [];
        
        $statusRaw  = $this->ww->request->param('policy-status', 'post', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY) ?? []; 
        $status     = $statusRaw[ $site ] ?? [];        
        
        $witchesRulesAncestor       = $this->ww->request->param('policy-witch-rules-ancestors', 'post', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY) ?? [];
        $witchesRulesSelf           = $this->ww->request->param('policy-witch-rules-self', 'post', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY) ?? [];
        $witchesRulesDescendants    = $this->ww->request->param('policy-witch-rules-descendants', 'post', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY) ?? [];
        
        $profileData['policies'] = [];
        foreach( $policyIds as $key => $pId )
        {
            if( $pId == -1 ){
                continue;
            }
            
            $data = [
                'module' => $modules[ $key ],
                'status' => $status[ $key ],
                'status' => $status[ $key ],
                'witch'  => $witches[ $key ],
                'custom' => $custom[ $key ],
            ];
            
            if( !empty($data['witch']) ){
                $data['witchRules'] = [
                    'ancestors'     => in_array($pId, $witchesRulesAncestor)? true: false,
                    'self'          => in_array($pId, $witchesRulesSelf)? true: false,
                    'descendants'   => in_array($pId, $witchesRulesDescendants)? true: false,
                ];
            }
            
            $profileData['policies'][] = $data;
        }        
        
        if( empty($profileData['site']) )
        {
            $this->ww->user->addAlert([
                'level'     =>  'error',
                'message'   =>  "Mandatory parameters missing, creation canceled"
            ]);
            break;
        }
        
        $newProfileId = Profile::createNew( $this->ww, $profileData );
        if( !$newProfileId )
        {
            $this->ww->user->addAlert([
                'level'     =>  'error',
                'message'   =>  "Creation failed"
            ]);
            break;
        }
        
        $this->ww->user->addAlert([
            'level'     =>  'success',
            'message'   =>  "Creation succeed"
        ]);
    break;
    
    case 'edit-profile': 
        $profileData = [
            'id'      =>  $this->ww->request->param('profile-id', 'post', FILTER_VALIDATE_INT),
        ];
        
        
        if( empty($profileData['id']) )
        {
            $this->ww->user->addAlert([
                'level'     =>  'error',
                'message'   =>  "Unidentified profile, edition canceled"
            ]);
            break;
        }
        
        $profileData['name']    = trim($this->ww->request->param('profile-name') ?? "");
        $profileData['site']    = trim($this->ww->request->param('profile-site') ?? "");
        
        $site                   = $profileData['site'] !== '*'? $profileData['site']: 'all';
        
        $policyIds  = $this->ww->request->param('policy-id', 'post', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY) ?? []; 
        $witches    = $this->ww->request->param('policy-witch-id', 'post', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY) ?? [];
        $custom     = $this->ww->request->param('policy-custom', 'post', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY) ?? [];
        
        $modulesRaw = $this->ww->request->param('policy-module', 'post', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY) ?? []; 
        $modules    = $modulesRaw[ $site ] ?? [];
        
        $statusRaw  = $this->ww->request->param('policy-status', 'post', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY) ?? []; 
        $status     = $statusRaw[ $site ] ?? [];        
        
        $witchesRulesAncestor       = $this->ww->request->param('policy-witch-rules-ancestors', 'post', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY) ?? [];
        $witchesRulesSelf           = $this->ww->request->param('policy-witch-rules-self', 'post', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY) ?? [];
        $witchesRulesDescendants    = $this->ww->request->param('policy-witch-rules-descendants', 'post', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY) ?? [];
        
        $profileData['policiesToDelete'] = array_filter($this->ww->request->param('policy-deleted', 'post', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY) ?? []);
        
        $profileData['policies'] = [];
        foreach( $policyIds as $key => $pId )
        {
            if( $pId == -1 || in_array($pId, $profileData['policiesToDelete'])){
                continue;
            }
            
            $data = [
                'module' => $modules[ $key ],
                'status' => $status[ $key ],
                'status' => $status[ $key ],
                'witch'  => $witches[ $key ],
                'custom' => $custom[ $key ],
            ];
            
            if( is_numeric($pId) ){
                $data['id'] = $pId;
            }
            
            if( !empty($data['witch']) ){
                $data['witchRules'] = [
                    'ancestors'     => in_array($pId, $witchesRulesAncestor)? true: false,
                    'self'          => in_array($pId, $witchesRulesSelf)? true: false,
                    'descendants'   => in_array($pId, $witchesRulesDescendants)? true: false,
                ];
            }
            
            $profileData['policies'][] = $data;
        }
        
        if( !Profile::edit( $this->ww, $profileData ) )
        {
            $this->ww->user->addAlert([
                'level'     =>  'error',
                'message'   =>  "Edition failed"
            ]);
            break;
        }
        
        $this->ww->user->addAlert([
            'level'     =>  'success',
            'message'   =>  "Profile updated"
        ]);
    break;

    case 'delete-profile':
        $profileId = $this->ww->request->param('profile-id', 'post', FILTER_VALIDATE_INT);
        if( empty($profileId) )
        {
            $this->ww->user->addAlert([
                'level'     =>  'error',
                'message'   =>  "Unidentified profile, deletion canceled"
            ]);
            break;
        }
        
        $profile = Profile::createFromId($this->ww, $profileId);
        if( !$profile )
        {
            $this->ww->user->addAlert([
                'level'     =>  'error',
                'message'   =>  "Unidentified profile, deletion canceled"
            ]);
            break;
        }

        if( !$profile->delete() )
        {
            $this->ww->user->addAlert([
                'level'     =>  'error',
                'message'   =>  "Deletion failed"
            ]);
            break;
        }

        $this->ww->user->addAlert([
            'level'     =>  'success',
            'message'   =>  "Profile ".$profile->name." deleted"
        ]);
    break;
}

$profiles = Profile::listProfiles( $this->ww );

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
ksort($websitesList);

$allSitesModulesListBuffer = [];
foreach( $websitesList as $website ){
    $allSitesModulesListBuffer = array_merge( $allSitesModulesListBuffer, $website->listModules() );
}

$allSitesModulesList = array_unique($allSitesModulesListBuffer);
asort( $allSitesModulesList );

$statusGlobal = $this->ww->configuration->read("global", "status");

$this->display();
