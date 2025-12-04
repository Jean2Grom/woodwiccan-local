<?php /** @var WW\Cauldron $this */ 

if( !isset($input) ){
    $input = "content";
}
else {
    $input .= "[content]";
}

include $this->formFilePath();