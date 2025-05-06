<?php /** @var WW\Context $this */ ?>

<script <?php foreach( $attributes as $name => $value ): ?>
            <?=$name.'="'.addcslashes( $value, '"' ).'"'?>
        <?php endforeach; ?>
            
        src="<?=$jsSrc?>"></script>