<?php /** @var WW\Cauldron $this */ 

$viewFile = $this->ww->website->getFilePath( self::VIEW_DIR."/edit/".$this->type.".php" );

if( !$viewFile ){
    $viewFile = $this->ww->website->getFilePath( self::VIEW_DIR."/edit/default.php" );
}

if( !isset($input) ){
    $input = "content";
}
else {
    $input .= "[content]";
}

if( $viewFile ){
    include $viewFile;
}
