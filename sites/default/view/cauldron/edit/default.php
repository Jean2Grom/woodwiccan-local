<?php /** @var WW\Cauldron $this */ ?>

<ul>
    <?php foreach( $this->contents() as $content ): ?>
        <li>
            <h4>
                <?php if( $content->name ): ?>
                    <?=$content->name ?>
                <?php endif; ?>
                <?="[".$content->type."] " ?>
            </h4>
            <?php $content->form(); ?>
        </li>
    <?php endforeach; ?>
</ul>