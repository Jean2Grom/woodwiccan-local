<?php /** @var WW\Attribute\ConnexionAttribute $this */ 

use WW\User\Profile;

$profiles = Profile::listProfiles( $this->ww );

$sitesProfiles = [];
foreach( $profiles as $profileId => $profileItem )
{
    $siteLabel =  $profileItem->site;
    if( $siteLabel == '*' ){
        $siteLabel =  "Tous les sites";
    }
    
    if( empty($sitesProfiles[ $siteLabel ]) ){
        $sitesProfiles[ $siteLabel ] = [];
    }
    
    $sitesProfiles[ $siteLabel ][ $profileId ] = $profileItem;
}

include $this->ww->website->getFilePath( self::VIEW_DIR."/edit/connexion.php");