<?php /** @var WW\Cauldron $this */ ?>

<div style="display: flex; flex-direction: column;">
    <?=$this->content('name') ?>
    <div>
        <?php if($this->file): ?>
            <img src="<?=$this->file?->value() ?>" style="max-width: 100%;" /> 
        <?php endif; ?>
    </div>
    <?=$this->content('caption') ?>
</div>
