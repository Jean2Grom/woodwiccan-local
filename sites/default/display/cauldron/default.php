<?php /** @var WW\Cauldron $this */ ?>

<ul>
    <?php foreach( $this->contents() as $ingredient ): ?>
        <li>
            <fieldset>
                <legend>
                    <?php if( $ingredient->name ): ?>
                        <?=$ingredient->name ?>
                    <?php endif; ?>
                    <?="[".$ingredient->type."] " ?>
                </legend>

                <?php $ingredient->display( null, 40 ); ?>
            </fieldset>
        </li>
    <?php endforeach; ?>
</ul>