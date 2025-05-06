<?php

$values = [];
$base   = [ 'href' => "", 'text' => "", 'external' => "" ];
$max    = -1;
foreach( $this->values["hrefs"] as $i => $href )
{
    if( !isset($values[$i]) )
    {   $values[$i] = $base;   }
    
    $values[$i]['href'] = $href;
    
    if( $max < $i )
    {   $max = $i;  }
}

foreach( $this->values["texts"] as $i => $text )
{
    if( !isset($values[$i]) )
    {   $values[$i] = $base;   }
    
    $values[$i]['text'] = $text;
    
    if( $max < $i )
    {   $max = $i;  }
}

for( $i=0; $i <= $max; $i++ )
{   $values[$i]['external'] = $this->values["externals"][$i];   }

include $this->module->getViewFile('attributes/edit/links.php');