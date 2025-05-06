<?php

$this->module->addCssFile('imageAttributeView.css');
?>

<? foreach( $values as $i => $value ) { ?>
    <? if( $i > 0 ) { ?>
        <hr class="attribute-image-delimiter" />
    <? } ?>
    <p>
        <img src="<?=$value['file']?>" class="imageAttributeView" />
    </p>

    <p>
        <strong><?=$value['title']?></strong>
        <? if( $value['link'] ) { ?>
            =>
            <a href="<?=$value['link']?>" target="_blank">
                <?=$value['link']?>
            </a>
        <? } ?>
    </p>
<? } ?>

<? if( count($values) == 0 ) { ?>
    <p>
        Pas d'images
    </p>
<? } ?>