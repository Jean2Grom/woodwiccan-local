<?php /** @var WW\Context $this */ ?>

<link   <?php foreach( $attributes as $name => $value ): ?>
            <?=$name.'="'.addcslashes( $value, '"' ).'"'?>
        <?php endforeach; ?>
        
        rel="stylesheet" 
        href="<?=$cssSrc?>" />