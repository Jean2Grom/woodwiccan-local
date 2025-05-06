<?php /** @var WW\Cauldron $this */ 

$storagePath    = $this->content('storage-path')?->value() ?? "";
if( $storagePath )
{
    $storagePath = $this->ww->configuration->storage().'/'.$storagePath; 
    
    if( !is_file($storagePath) ){
        $storagePath = "";
    }
}

$filename       = $this->content('filename')?->value() ?? "";

if( !isset($input) ){
    $input = "content";
}
else {
    $input .= "[content]";
}

include $this->ww->website->getFilePath( self::VIEW_DIR."/edit/ww-file.php" );