<?php 
/**
 * @var string $dom
 * @var array $attributes: [ attributeName => attributeValue ]
 * @var string $inner
 */
?>

<<?= $dom ?>
    <?php foreach( $attributes ?? [] as $name => $value ): ?>
        <?=$name.'="'.addcslashes( $value, '"' ).'"'?>
    <?php endforeach; ?>>
    <?=$inner ?? '' ?>
</<?= $dom ?>>