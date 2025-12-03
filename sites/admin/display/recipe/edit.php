<?php 
/**
 * @var WW\Module $this
 * @var WW\Cauldron\Recipe $recipe
 * @var array $possibleTypes
 */

$this->addCssFile('recipe/edit.css');
$this->addJsFile('triggers.js');
$this->addJsFile('recipe-edit.js');
?>

<form id="edit-action" method="post">
    <?php if( !empty($recipeName) ): ?>
        <input type="hidden" name="recipe" value="<?=$recipeName?>" />
        <h3>
            <span  id="name-display"><?=$recipe->name?></span>
            <input id="name-input" type="text" name="name" value="<?=$recipe->name ?>" />
        </h3>

        <em id="file-display"><?=$recipe->file ?? ''?></em>
        <input id="file-input" type="text" name="file" value="<?=$recipe->file ?>" />

    <?php else: ?>
        <h3>
            <span  id="name-display">Click to enter new recipe name</span>
            <input id="name-input" type="text" name="name" value="" />
        </h3>

        <em id="file-display">Click to customize new recipe file</em>
        <input id="file-input" type="text" name="file" value="" />
    <?php endif; ?>

    <div class="fieldsets-container">
        <fieldset>
            <legend>global restrictions</legend>
            <?php $this->include('recipe/edit-require.php', [
                'name'          => $globalRequireInputPrefix,
                'require'       => $recipe->require,
                'possibleTypes' => $possibleTypes,
            ]); ?>
        </fieldset>
    </div>
    
    <div class="fieldsets-container" id="contents">
        <?php foreach( $recipe->composition ?? [] as $item ): ?>
            <fieldset>
                <legend>
                    <?=$item['name']?> 
                    <a class="up-fieldset">[&#8593;]</a>
                    <a class="down-fieldset">[&#8595;]</a>                
                    <a class="remove-fieldset">[x]</a>
                </legend>
                <ul>
                    <li>
                        <div>Name</div>
                        <input  type="text" 
                                class="ref-name" 
                                name="<?=$item['name']?>-name" 
                                value="<?=$item['name']?>" />
                    </li>
                    <li>
                        <div>Type</div>
                        <select class="check-restriction-toggle"
                                name="<?=$item['name']?>-type">
                            <?php foreach( $possibleTypes as $possibleType => $label ): ?>
                                <option <?=$possibleType === $item['type']? "selected": ""?>
                                        data-restrictions="<?=in_array($possibleType, $ingredients)? "off": "on"?>"
                                        value="<?=$possibleType?>">
                                    <?=$label?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </li>
                    <li>
                        <div>Mandatory</div>
                        <input  type="checkbox" value="1" 
                                name="<?=$item['name']?>-mandatory" 
                                <?=$item['mandatory'] ?? null? "checked": ""?> />
                    </li>
                    <li <?=in_array($item['type'], $ingredients)? 'style="display: none"': ''?>
                        class="recipe-type-toggle">
                        <?php $this->include('recipe/edit-require.php', [
                            'name'          => $item['name'],
                            'require'       => $item['require'] ?? [],
                            'possibleTypes' => $possibleTypes,
                        ]); ?>
                    </li>
                </ul>
            </fieldset>
        <?php endforeach; ?>
    </div>
</form>

<div class="fieldsets-container" id="new-content">
    <fieldset>
        <legend class="new-content-form">new content</legend>
        <legend>
            NEW_CONTENT_NAME
            <a class="up-fieldset">[&#8593;]</a>
            <a class="down-fieldset">[&#8595;]</a>                
            <a class="remove-fieldset">[x]</a>
        </legend>
        <ul>
            <li>
                <div>Name</div>
                <input type="text" class="ref-name" name="NEW_CONTENT_NAME-name" value="" />
            </li>
            <li>
                <div>Type</div>
                <select class="check-restriction-toggle"
                        name="NEW_CONTENT_NAME-type">
                    <option value="0" data-restrictions="off">Select new type</option>
                    <?php foreach( $possibleTypes as $possibleType => $label ): ?>
                        <option data-restrictions="<?=in_array($possibleType, $ingredients)? "off": "on"?>"
                                value="<?=$possibleType?>">
                            <?=$label?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </li>
            <li>
                <div>Mandatory</div>
                <input  type="checkbox" value="1" 
                        name="NEW_CONTENT_NAME-mandatory" />
            </li>
            <li style="display: none"
                class="recipe-type-toggle">
                <?php $this->include('recipe/edit-require.php', [
                            'name'          => "NEW_CONTENT_NAME",
                            'require'       => [],
                            'possibleTypes' => $possibleTypes,
                        ]); ?>
            </li>
        </ul>
        <div class="new-content-actions hidden">
            <button id="add-content">
                <i class="fa fa-plus" aria-hidden="true"></i>
                Add
            </button>
        </div>
    </fieldset>
</div>
<div class="box__actions">
    <?php if( !empty($recipeName) ): ?>
        <button class="trigger-action" 
                data-action="save"
                data-target="edit-action">
            <i class="fa fa-save" aria-hidden="true"></i>
            Save
        </button>
        <button class="trigger-href" 
                data-href="<?=$this->ww->website->getUrl('recipe/view', ['recipe' => $recipe->name])?>">
            <i class="fa fa-times" aria-hidden="true"></i>
            Cancel
        </button>

    <?php else: ?>
        <button class="trigger-action" 
                data-action="publish"
                data-target="edit-action">
            <i class="fa fa-check" aria-hidden="true"></i>
            Publish
        </button>
        <button class="trigger-href" 
                data-href="<?=$this->ww->website->getUrl('recipe')?>">
            <i class="fa fa-times" aria-hidden="true"></i>
            Cancel
        </button>
    <?php endif; ?>
</div>