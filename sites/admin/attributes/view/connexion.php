<?php /** @var WW\Attribute\ConnexionAttribute $this */ 

use WW\User\Profile;

$profiles       = [];
if( !empty($this->values['profiles']) ){
    $profiles = Profile::listProfiles( $this->ww, [ 'profile.id' => $this->values['profiles'] ] );
}

include $this->ww->website->getFilePath( self::VIEW_DIR."/view/connexion.php" );