<?php

$values = [];
$base   = [ 'file' => "", 'title' => "", 'link' => "" ];
foreach( $this->values["files"] as $i => $filename )
{
    if( !isset($values[$i]) )
    {   $values[$i] = $base;   }
    
    $values[$i]['file'] = $this->getImageFile($filename);
}

foreach( $this->values["titles"] as $i => $title )
{
    if( !isset($values[$i]) )
    {   $values[$i] = $base;   }
    
    $values[$i]['title'] = $title;
}

foreach( $this->values["links"] as $i => $link )
{
    if( !isset($values[$i]) )
    {   $values[$i] = $base;   }
    
    $values[$i]['link'] = $link;
}

include $this->module->getViewFile('attributes/edit/images.php');