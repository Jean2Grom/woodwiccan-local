<?php /** @var WW\Cauldron\Ingredient $this */ ?>

<?php 
$displayedContent = trim( strip_tags($this) );

if( $displayedContent ):
    echo $displayedContent;
else: ?>
    <code>
        <?=htmlspecialchars($this);?>
    </code>
<?php endif; ?>