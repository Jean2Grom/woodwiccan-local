<?php /** @var WW\Cauldron $this */ ?>

<ul>
    <?php foreach( $this->contents() as $ingredient ): ?>
        <li style="display: flex;justify-content: space-between;">
            <div style="text-align: left;">
                <?php if( $ingredient->name ): ?>
                    <?=$ingredient->name ?>
                <?php endif; ?>
                <?="[".$ingredient->type."] " ?>
            </div>
            <div style="margin-left: 40px;text-align: right;">
                <?php $ingredient->display( null, 40 ); ?>
            </div>
        </li>
    <?php endforeach; ?>
</ul>