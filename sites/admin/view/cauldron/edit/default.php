<?php 
/**
 * @var WW\Cauldron $this 
 * @var string $input
 */ 
?>
<div class="fieldsets-container">
    <?php if( empty($this->contents()) ): ?>
        <input  
            name="<?=$input?>" 
            value="" 
            type="hidden" 
        />
    
    <?php else: foreach( $this->contents() as $contentIndex => $content ): 
        $integrationCountClass  = substr_count($input, '[content]') % 2;
        $contentInput           = $input."[".$contentIndex."]";
        ?>
        <fieldset class="<?=$content->isIngredient()? 'ingredient': 'cauldron integration-'.$integrationCountClass?>">
            <legend>
                <span   class="span-input-toggle" 
                        data-input="<?=$contentInput?>[name]" 
                        data-value="<?=$content->name ?>"><?=$content->name ?></span>
                [<?=$content->type?>]
                <a class="up-fieldset">[&#8593;]</a>
                <a class="down-fieldset">[&#8595;]</a>
                <a class="remove-fieldset">[x]</a>
            </legend>
            <?php if( $content->exist() ): ?>
                <input  
                    name="<?=$contentInput?>[ID]" 
                    value="<?=$content->id ?>" 
                    type="hidden" 
                />
            <?php endif; ?>
            <input  
                name="<?=$contentInput ?>[type]" 
                value="<?=$content->type ?>" 
                type="hidden" 
            />
            <?php $content->edit( 
                null, 
                [ 'input' => $contentInput ]
            ); ?>
        </fieldset>
    <?php endforeach; endif; ?>
</div>

<?php $this->ww->website->include(
    'cauldron/add.php', 
    [
        'input'     => $input."[new]",
        'cauldron'  => $this
    ]
);?>
