<?php /** @var WW\Cauldron $this */ ?>

<ul>
    <?php foreach( $this->contents() as $ingredient ): ?>
        <li style="display: flex;justify-content: space-between;">
            <div>
                <?php if( $ingredient->name ): ?>
                    <?=$ingredient->name ?>
                <?php endif; ?>
                <?="[".$ingredient->type."] " ?>
            </div>
            <div style="padding-left: 10px;">
                <?php $ingredient->display( null, 40 ); ?>
            </div>
        </li>
    <?php endforeach; ?>
</ul>