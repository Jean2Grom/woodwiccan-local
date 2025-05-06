<?php /** @var WW\Attribute $this */ 

$viewFile = $this->ww->website->getFilePath( self::VIEW_DIR."/view/".$this->type.".php");

if( !$viewFile ){
    $viewFile = $this->ww->website->getFilePath( self::VIEW_DIR."/view/default.php");
}

if( $viewFile ){
    include $viewFile;
}

