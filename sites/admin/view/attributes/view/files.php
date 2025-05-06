<?php

?>

<ul>
    <? foreach( $values as $value ) { ?>
        <li>
            <? if( $value['file'] ) { ?>
                <strong><?=$value['title']?></strong>
                =>
                <a href="<?=$value['file']?>" target="_blank">
                    <?=basename($value['file'])?>
                </a>
            <? } ?>
        </li>
    <? } ?>
</ul>