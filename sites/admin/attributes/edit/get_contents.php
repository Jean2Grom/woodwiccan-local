<?php


$allLocalisations = $this->localisation->getAllData();

foreach( $allLocalisations as $key => $allLocalisations_item )
{
    $level = 0;
    foreach( $allLocalisations_item as $label => $value ){
        if( strcmp("level_", substr($label, 0, 6)) == 0 ){
            if( !is_null($value) ){
                $level = (int) substr($label, 6);
            }
        }
    }

    $spacing = "";
    for( $i=2; $i<=$level; $i++ ){
        $spacing .= "&nbsp;&nbsp;&nbsp;";
    }

    if( $level > 1 ){
        $spacing .= "-&nbsp;";
    }
    
    $allLocalisations[ $key ]['spacing'] = $spacing;
}


include $this->module->getViewFile('attributes/edit/get_contents.php');
