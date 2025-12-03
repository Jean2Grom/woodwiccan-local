<?php /** @var WW\Context $this */ ?>

<img    <?php foreach( $attributes as $name => $value ): ?>
            <?=$name.'="'.addcslashes( $value, '"' ).'"'?>
        <?php endforeach; ?>
        
        src="<?=$imageSrc?>" />