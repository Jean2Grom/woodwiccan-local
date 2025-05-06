<?php 
/**
 * @var WW\Module $this
 * @var ?WW\Cauldron $cauldron
 */
$cauldron = $cauldron ?? $this->witch("target")->cauldron();

$this->addCssFile('cauldron/edit.css');
$this->addJsFile('cauldron/edit.js');
$this->addJsFile('triggers.js');
?>
<h1>
    <i class="fa fa-feather-alt"></i>
    <?=$this->witch("target")->name ?>
</h1>
<p><em><?=$this->witch("target")->data?></em></p>

<?php $this->include('alerts.php', ['alerts' => $this->ww->user->getAlerts()]); ?>

<form id="edit-action" method="post" enctype="multipart/form-data">
    <input type="hidden" name="MAX_FILE_SIZE" value="3000000" />
    
    <?php if( $cauldron->draft()->exist() ): ?>
        <input  
            name="ID" 
            value="<?=$cauldron->draft()->id ?>" 
            type="hidden" 
        />
    <?php endif; ?>

    <input  
        name="type" 
        value="<?=$cauldron->draft()->type ?>" 
        type="hidden" 
    />
    <h3>
        [<?=$cauldron->recipe ?>] 
        <span   class="span-input-toggle" 
                data-input="name" 
                data-value="<?=$cauldron->draft()->name ?>"><?=$cauldron->draft()->name ?></span>
    </h3>
    <p>
        <?php if( $cauldron->draft()->created ): ?>
            <em>Draft created by <?=$cauldron->draft()->created->actor?>: <?=$cauldron->draft()->created->format( \DateTimeInterface::RFC2822 )?></em>
        <?php endif; ?>
        <?php if( $cauldron->draft()->modified && $cauldron->draft()->created != $cauldron->draft()->modified ): ?>
            <br/> 
            <em>Draft modified by <?=$cauldron->draft()->modified->actor?>: <?=$cauldron->draft()->modified->format( \DateTimeInterface::RFC2822 )?></em>
        <?php endif; ?>
    </p>
    
    <?php $cauldron->draft()->edit(); ?>

</form>

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

<button class="trigger-href" 
        data-href="<?= $this->ww->website->getUrl('view', [ 'id' => $this->witch("target")->id ]) ?>">
    <i class="fa fa-times"></i>
    Cancel
</button>