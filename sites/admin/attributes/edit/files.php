<?php

$values = [];
$base   = [ 'file' => "", 'title' => "" ];
foreach( $this->values["files"] as $i => $filename )
{
    if( !isset($values[$i]) )
    {   $values[$i] = $base;   }
    
    $values[$i]['file'] = $this->getFile($filename);
}

foreach( $this->values["titles"] as $i => $title )
{
    if( !isset($values[$i]) )
    {   $values[$i] = $base;   }
    
    $values[$i]['title'] = $title;
}

include $this->module->getViewFile('attributes/edit/files.php');