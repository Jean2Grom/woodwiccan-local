<?php 
/**
 * @var WW\Witch $witch
 */
?>
<div class="box view__cauldron">
    <?php if( !$witch->cauldron() ): ?>
        <h3>
            <i class="fa fa-mortar-pestle"></i>
            No cauldron
        </h3>
        <form method="post" id="witch-add-new-cauldron">

            <select name="witch-cauldron-recipe" id="witch-cauldron-recipe">
                <option value="">
                    Select new cauldron recipe
                </option>
                <?php foreach( $witch->ww->configuration->recipes() as $recipe ): ?>
                    <option value="<?=$recipe->name?>">
                        <?=$recipe->name?>
                    </option>
                <?php endforeach; ?>
            </select>
            
            <input type="hidden" id="imported-cauldron-witch" name="imported-cauldron-witch" value="" />
        </form>
        
        <div class="box__actions">
            <button id="import-cauldron-action" 
                    class="trigger-action"
                    style="display: none;"
                    data-action="import-cauldron"
                    data-target="witch-add-new-cauldron">Import cauldron</button>
            <button id="witch-get-existing-cauldron">
                <i class="fa fa-project-diagram"></i>
                Get existing cauldron
            </button>
            <button id="witch-create-cauldron" 
                    class="trigger-action disabled"
                    data-action="create-cauldron"
                    data-target="witch-add-new-cauldron">
                <i class="fa fa-plus"></i>
                Create new cauldron
            </button>
        </div>

    <?php else: ?>
        <h3 title="ID <?=$witch->cauldron()->id ?>">
            <i class="fa fa-mortar-pestle"></i>
            <?=$witch->cauldron()->name ?>
        </h3>
        <h4 title="ID <?=$witch->cauldron()->id ?>">
            <?=$witch->cauldron()->type ?>
            <em>[<?=$witch->cauldron()->isPublished()? 
                        "Published": 
                        ($witch->cauldron()->isDraft()? "Draft": "Archive")?>]</em>
        </h4>

        <p><em>Cauldron is a content associated with the Witch</em></p>
                
        <?php foreach( $witch->cauldron()->contents() as $ingredient ): ?>
            <fieldset>
                <legend><?=$ingredient->name?> [<?=$ingredient->type?>]</legend>                    
                <?php $ingredient->display() ?>
            </fieldset>
        <?php endforeach; ?>
        
        <p>
            <?php if( $witch->cauldron()->created ): ?>
                <em>Created by <?=$witch->cauldron()->created->actor?>: <?=$witch->cauldron()->created->format( \DateTimeInterface::RFC2822 )?></em>
            <?php endif; ?>
            <?php if( $witch->cauldron()->modified && $witch->cauldron()->created != $witch->cauldron()->modified ): ?>
                <br/> 
                <em>Modified by <?=$witch->cauldron()->modified->actor?>: <?=$witch->cauldron()->modified->format( \DateTimeInterface::RFC2822 )?></em>
            <?php endif; ?>
        </p>
        
        <div class="box__actions">
            <button class="trigger-action"
                    data-confirm="Confirm removal"
                    data-action="remove-cauldron"
                    data-target="view-cauldron-action">
                <?php if( count($witch->cauldron()->witches()) === 1 ): ?>
                    <i class="fa fa-trash"></i>
                    Delete
                <?php else: ?>
                    <i class="fa fa-times"></i>
                    Remove
                <?php endif;?>
            </button>
            <?php /* if( !$witch->cauldron()->isArchive() ): ?>
                <button class="trigger-action"
                        data-confirm="Confirm archive"
                        data-action="archive-cauldron"
                        data-target="view-cauldron-action">
                    <i class="fa fa-archive"></i>
                    Archive
                </button>
            <?php endif; */?>
            <button class="trigger-href" 
                    data-href="<?=$witch->ww->website->getUrl("cauldron?id=".$witch->id) ?>">
                <i class="fa fa-pencil"></i>
                Edit
            </button>
        </div>
    <?php endif; ?>
</div>

<form method="post" id="view-cauldron-action"></form>
