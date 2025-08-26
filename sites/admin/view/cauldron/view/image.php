<?php /** @var WW\Cauldron $this */ ?>

<div style="display: flex; flex-direction: column;">
    <?=$this->content('name') ?>
    <img    src="<?=$this->file->value() ?>" 
            loading="lazy"
            style="max-width: 100%;" /> 
    <?=$this->content('caption') ?>
</div>
