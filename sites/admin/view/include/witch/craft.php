<?php 
/**
 * @var WW\Witch $witch
 * @var array $structuresList
 * @var bool $deletion
 */
?>
<div class="box view__craft">
    <?php if( empty($witch->craft()) ): ?>
        <h3>
            <i class="fa fa-feather-alt"></i>
            No craft
        </h3>
        <form method="post" 
              action="<?=$witch->ww->website->getUrl('edit?id='.$witch->id) ?>"
              id="witch-add-new-content">
            <select name="witch-content-structure" id="witch-content-structure">
                <option value="">
                    Select new craft structure
                </option>
                <?php foreach( $structuresList as $structureData ): ?>
                    <option value="<?=$structureData['name']?>">
                        <?=$structureData['name']?>
                    </option>
                <?php endforeach; ?>
            </select>
            
            <input type="hidden" id="imported-craft-witch" name="imported-craft-witch" value="" />
        </form>
        
        <div class="box__actions">
            <button id="import-craft-action" 
                    class="trigger-action"
                    style="display: none;"
                    data-action="import-craft"
                    data-target="witch-add-new-content">Import craft</button>
            <button id="witch-get-existing-craft">
                <i class="fa fa-project-diagram"></i>
                Get existing craft
            </button>
            <button id="witch-create-craft" disabled
                    class="trigger-action"
                    data-action="create-craft"
                    data-target="witch-add-new-content">
                <i class="fa fa-plus"></i>
                Create craft
            </button>
        </div>

    <?php else: ?>
        <h3>
            <i class="fa fa-feather-alt"></i>
            <?=$witch->craft()->name ?>
        </h3>        
        <h4>
            <?=ucfirst($witch->craft()->structure->name) ?>
            <em>[<?=$witch->craft()->structure->type ?> <?=$witch->craft()->id ?>]</em>
        </h4>
        
        <p><em>Craft (content) associated with this witch</em></p>
        
        <?php foreach( $witch->craft()->attributes as $attribute ): ?>
            <fieldset>
                <legend><?=$attribute->name?> [<?=$attribute->type?>]</legend>
                    <?php $attribute->display() ?>
            </fieldset>
            <div class="clear"></div>
        <?php endforeach; ?>
        
        <p>
            <?php if( $witch->craft()->created ): ?>
                <em>Created by <?=$witch->craft()->created->actor?>: <?=$witch->craft()->created->format( \DateTimeInterface::RFC2822 )?></em>
            <?php endif; ?>
            <?php if( $witch->craft()->modified && $witch->craft()->created != $witch->craft()->modified ): ?>
                <br/> 
                <em>Modified by <?=$witch->craft()->modified->actor?>: <?=$witch->craft()->modified->format( \DateTimeInterface::RFC2822 )?></em>
            <?php endif; ?>
        </p>
        
        <div class="box__actions">
            <button class="trigger-action"
                    data-confirm="Warning ! You are about to remove this content"
                    data-action="remove-craft"
                    data-target="view-craft-action">
                <?php if( $deletion ): ?>
                    <i class="fa fa-trash"></i>
                    Delete
                <?php else: ?>
                    <i class="fa fa-times"></i>
                    Remove
                <?php endif;?>
            </button>
            <?php if( $witch->craft()->structure->type === WW\Craft\Content::TYPE ): ?>
                <button class="trigger-action"
                        data-confirm="Are you sure to archive this content ?"
                        data-action="archive-craft"
                        data-target="view-craft-action">
                    <i class="fa fa-archive"></i>
                    Archive
                </button>
            <?php endif; ?>
            <button class="trigger-href" 
                    data-href="<?=$witch->ww->website->getUrl("edit-content?id=".$witch->id) ?>"
                    id="content__edit">
                <i class="fa fa-pencil"></i>
                Edit
            </button>
        </div>
    <?php endif; ?>
</div>

<form method="post" 
      action="<?=$witch->ww->website->getUrl('edit?id='.$witch->id) ?>"
      id="view-craft-action"></form>
