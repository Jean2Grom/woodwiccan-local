<?php

?>

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta   
    name="viewport" 
    content="width=device-width, initial-scale=1, minimum-scale=0.25, maximum-scale=5.0, target-densitydpi=device-dpi" />
<title>
    <?=$contextData['meta-title']?>
</title>
<meta name="description" content="<?=$contextData['meta-description']?>">
<meta name="keywords" content="<?=$contextData['meta-keywords']?>">

<? foreach( $module->getCssFiles() as $cssFile ) { ?>
    <link   rel="stylesheet" 
            type="text/css" 
            href="<?=$cssFile?>" />
<? } ?>

<link   rel="stylesheet" 
        media="screen and (max-width: 800px)" 
        href="/sites/woodwiccan/view/css/responsive.css" />