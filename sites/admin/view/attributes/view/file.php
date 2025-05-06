<?php /** @var WW\Attribute\FileAttribute $this */ ?>

<?php if( $srcFile ): ?>
    <p>
        <strong><?=$this->values['title']?></strong>
        =>
        <a href="<?=$srcFile?>" target="_blank">
            <?=$this->values['file']?>
        </a>
    </p>
<?php else: ?>
    No file
<?php endif; ?>