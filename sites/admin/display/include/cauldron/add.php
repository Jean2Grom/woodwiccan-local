<?php
/**
 * @var string $input
 * @var WW\Cauldron $cauldron
 */

$allowedAll = array_merge(
    $cauldron->allowedNewElements(),
    $cauldron->allowedNewIngredients(),
    $cauldron->allowedNewRecipes(),
);

if( count($allowedAll) > 0 ): ?>
    <div class="cauldron-add-actions">
        <div class="add-form" data-input="<?=$input ?>">
            <h4>
                Add 
                <a class="hide-form">[x]</a>
            </h4>

            <?php if( count($allowedAll) === 1 ): if( is_array($allowedAll[0]) ): ?>
                <select>
                    <option selected value="<?=$allowedAll[0]['type'] ?>">
                        <?=$allowedAll[0]['name'] ?>
                    </option>
                </select>
                <input type="hidden" value="<?=$allowedAll[0]['name'] ?>" />

                <button data-action="save" 
                        data-target="edit-action">
                    <i class="fa fa-save"></i>
                    Save
                </button>

            <?php elseif( is_string($allowedAll[0]) ): ?>
                <select>
                    <option selected value="<?=$allowedAll[0] ?>">
                        <?=$allowedAll[0] ?>
                    </option>
                </select>
                <input type="text" value="" />

                <button class="disabled" 
                        data-action="save" 
                        data-target="edit-action">
                    <i class="fa fa-save"></i>
                    Save
                </button>

            <?php else: ?>
                <select>
                    <option selected value="<?=$allowedAll[0]->name ?>">
                        <?=$allowedAll[0]->name ?>
                    </option>
                </select>
                <input type="text" value="" />

                <button class="disabled" 
                        data-action="save" 
                        data-target="edit-action">
                    <i class="fa fa-save"></i>
                    Save
                </button>
                
            <?php endif; else: ?>
                <select>
                    <option value="">Select type</option>

                    <?php if( $cauldron->allowedNewElements() ): ?>
                        <optgroup label="elements">
                            <?php foreach( $cauldron->allowedNewElements() as $element ): ?>
                                <option value="<?=$element['type']?>"
                                        data-name="<?=$element['name']?>">
                                    <?=$element['name'] ?>
                                    <?=($element['mandatory'] ?? false)? " [mandatory]": "" ?>
                                </option>
                            <?php endforeach; ?>
                        </optgroup>
                    <?php endif; 

                    if( $cauldron->allowedNewIngredients() ): ?>
                        <optgroup label="ingredients">
                            <?php foreach( $cauldron->allowedNewIngredients() as $ingredient ): ?>
                                <option value="<?=$ingredient?>">
                                    <?=$ingredient?>
                                </option>
                            <?php endforeach; ?>
                        </optgroup>
                    <?php endif; 
                    
                    if( $cauldron->allowedNewRecipes() ): ?>
                        <optgroup label="recipes">
                            <?php foreach( $cauldron->allowedNewRecipes() as $recipe ): ?>
                                <option value="<?=$recipe->name?>">
                                    <?=$recipe->name?>
                                </option>
                            <?php endforeach; ?>
                        </optgroup>
                    <?php endif; ?>
                </select>
                
                <input type="text" value="" />

                <button class="disabled" 
                        data-action="save" 
                        data-target="edit-action">
                    <i class="fa fa-save"></i>
                    Save
                </button>
            <?php endif; ?>
        </div>
        
        <button class="show-form">
            <i class="fa fa-plus" aria-hidden="true"></i>
        </button>
    </div>
<?php endif; ?>