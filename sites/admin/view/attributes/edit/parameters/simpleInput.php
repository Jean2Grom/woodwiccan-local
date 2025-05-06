<?php

?>

<h3><?=$title?></h3>
<input  <? foreach( $inputAttributes as $inputAttributeName => $inputAttributeValue ) { ?> 
            <? if( $inputAttributeValue !== false ) { ?>
                <?=$inputAttributeName?>="<?=$inputAttributeValue?>"
            <? } ?>
        <? } ?>
        />
