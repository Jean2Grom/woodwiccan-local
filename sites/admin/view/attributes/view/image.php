<?php /** @var WW\Attribute\ImageAttribute $this */ ?>

<?php if( $srcFile ): ?>
    <p>
        <img src="<?=$srcFile?>" class="attribute-image-view" />
    </p>
    <p>
        <strong><?=$this->values['title']?></strong>
    </p>
<?php else: ?>
    No image
<?php endif; ?>