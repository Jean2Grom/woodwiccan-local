<?php

?>

<h3><?=$title?></h3>
<select <? foreach( $selectAttributes as $inputAttributeName => $inputAttributeValue ) { ?> 
            <? if( $inputAttributeValue !== false ) { ?>
                <?=$inputAttributeName?>="<?=$inputAttributeValue?>"
            <? } ?>
        <? } ?>
        >
        <? foreach( $optionsAttributes as $optionText => $optionsAttributesItem ) { ?>
            <option <? foreach( $optionsAttributesItem as $inputAttributeName => $inputAttributeValue ) { ?> 
                        <? if( $inputAttributeValue !== false ) { ?>
                            <?=$inputAttributeName?>="<?=$inputAttributeValue?>"
                        <? } ?>
                    <? } ?>
                    >
                <?=$optionText?>
            </option>
        <? } ?>
</select>
