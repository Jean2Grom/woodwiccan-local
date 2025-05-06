<?php

//use WW\Localisation;

if( $this->values['fk_localisation'] > 0){   
    //$localisationCraft = new Localisation($this->module->ww, $this->values['fk_localisation']);
}
else {
    $localisationCraft = false;
}

$names = [];
foreach( $this->values['crafts'] as $craft ){
    if( !empty($craft['name']) ){
        $names[] = $craft['name'];
    }
    elseif( isset($craft['attributes']["titre"]->values['string']) ){
        $names[] = $craft['attributes']["titre"]->values['string'];
    }
}

include $this->module->getViewFile('attributes/view/get_contents.php');