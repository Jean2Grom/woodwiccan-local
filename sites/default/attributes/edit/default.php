<?php /** @var WW\Attribute $this */ 

$viewFile = $this->ww->website->getFilePath( self::VIEW_DIR."/edit/".$this->type.".php");

if( !$viewFile ){
    $viewFile = $this->ww->website->getFilePath( self::VIEW_DIR."/edit/default.php");
}

if( $viewFile ){
    include $viewFile;
}

