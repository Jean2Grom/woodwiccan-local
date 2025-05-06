<?php /** @var WW\Module $this */

$this->addCssFile('content-edit.css');
$this->addJsFile('triggers.js');
?>
<h1>
    <i class="fa fa-feather-alt"></i>
    <?=$this->witch->name ?>
</h1>
<p><em><?=$this->witch->data?></em></p>
    
<?php $this->include('alerts.php', ['alerts' => $this->ww->user->getAlerts()]); ?>

<h3>[<?=$draft->structure->name ?>] <em><?=$draft->name ?></em></h3>
<p>
    <?php if( $draft->created ): ?>
        <em>Draft created by <?=$draft->created->actor?>: <?=$draft->created->format( \DateTimeInterface::RFC2822 )?></em>
    <?php endif; ?>
    <?php if( $draft->modified && $draft->created != $draft->modified ): ?>
        <br/> 
        <em>Draft modified by <?=$draft->modified->actor?>: <?=$draft->modified->format( \DateTimeInterface::RFC2822 )?></em>
    <?php endif; ?>
</p>

<form id="edit-action" method="post" enctype="multipart/form-data">
    
    <div class="fieldsets-container">
        <fieldset>
            <legend>Craft Name</legend>
            <input type="text" name="name" value="<?=$draft->name ?>" />
        </fieldset>
        
        <?php foreach( $draft->attributes as $attribute ): ?>
            <fieldset>
                <legend><?=$attribute->name?> [<?=$attribute->type?>]</legend>
                    <?php $attribute->edit() ?>
            </fieldset>
        <?php endforeach; ?>
    </div>
    
    <?php if( $this->witch("target") ): ?>
        <button class="trigger-action" 
                data-action="publish"
                data-target="edit-action">
            <i class="fa fa-check"></i>
            Publish
        </button>
        <button class="trigger-action"
                data-action="save-and-return"
                data-target="edit-action">
            <i class="fa fa-share"></i>
            Save and Quit
        </button>
        <button class="trigger-action"
                data-action="save"
                data-target="edit-action">
            <i class="fa fa-save"></i>
            Save
        </button>
        <button class="trigger-action"
                data-action="delete"
                data-target="edit-action">
            <i class="fa fa-trash"></i>
            Delete draft
        </button>
    <?php endif; ?>
    
    <?php if( $cancelHref ): ?>
        <button class="trigger-href" 
                data-href="<?=$cancelHref ?>">
            <i class="fa fa-times"></i>
            Cancel
        </button>
    <?php endif; ?>
</form>

    
