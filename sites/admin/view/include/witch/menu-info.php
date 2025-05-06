<?php 
/**
 * @var WW\Witch $witch
 */
?>
<div class="view__witch-menu-info">
    <h2 title="<?=$witch->data ?>">
        <?=$witch->name ?>
        <button class="view-edit-menu-info-toggle">
            <i class="fa fa-pencil"></i>
        </button>
    </h2>
    
    <p><em><?=$witch->data ?></em></p>
</div>
