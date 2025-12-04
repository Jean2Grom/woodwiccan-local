<?php /** @var WW\Cauldron $this */ 

$title = $this->content('filename')?->value() ?? "";

$storagePath    = $this->content('file')?->content('storage-path')?->value() ?? "";
if( $storagePath )
{
    $storagePath = $this->ww->configuration->storage().'/'.$storagePath; 
    
    if( !is_file($storagePath) ){
        $storagePath = "";
    }
}

$filename       = $this->content('file')?->content('filename')?->value() ?? "";

if( !isset($input) ){
    $input = "content";
}
else {
    $input .= "[content]";
}

include $this->formFilePath();