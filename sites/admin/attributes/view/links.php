<?php

$maxLenght  = 75;

$values = [];
$base   = [ 'href' => "", 'text' => "", 'external' => "" ];
foreach( $this->values["hrefs"] as $i => $href )
{
    if( !isset($values[$i]) )
    {   $values[$i] = $base;   }
    
    $indice         = strpos( substr($href, $maxLenght), " " ) + $maxLenght;
    $hrefDisplay    = substr($href, 0, $indice);
    
    if( strlen($href) > strlen($hrefDisplay) )
    {   $hrefDisplay .= " (...)";   }
    
    $values[$i]['href'] = $hrefDisplay;
}

foreach( $this->values["texts"] as $i => $text )
{
    if( !isset($values[$i]) )
    {   $values[$i] = $base;   }
    
    $indice         = strpos( substr($text, $maxLenght), " " ) + $maxLenght;
    $textDisplay    = substr($text, 0, $indice);
    if( strlen($text) > strlen($textDisplay) )
    {   $textDisplay .= " (...)";  }

    $values[$i]['text'] = $textDisplay;
}

foreach( $this->values["externals"] as $i => $external )
{
    if( !isset($values[$i]) )
    {   $values[$i] = $base;   }
    
    if( $external )
    {   $externalDisplay = "Ouverture en fenêtre externe";   }
    else
    {   $externalDisplay = "Ouverture dans la même fenêtre";}
    
    $values[$i]['external'] = $externalDisplay;
}

include $module->getViewFile('attributes/view/links.php');