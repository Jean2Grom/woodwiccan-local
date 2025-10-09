<?php /** @var WW\Cauldron $this */ ?>

<div style="display: flex; flex-direction: column;">
    <?=$this->content('name') ?>
    <div>
        <img src="<?=$this->file->value() ?>" style="max-width: 100%;" /> 
    </div>
    <?=$this->content('caption') ?>
</div>
