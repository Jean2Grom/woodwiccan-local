<?php

?>

<ul>
    <? foreach( $values as $i => $value ) { ?>
        <li>
            <strong><?=$value['text']?></strong>
            =>
            <a href="<?=$value['href']?>" target="_blank">
                <?=$value['href']?>
            </a>
            <br/>
            <?=$externalDisplay?>
        </li>
    <? } ?>
</ul>