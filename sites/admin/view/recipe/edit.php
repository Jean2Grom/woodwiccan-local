<?php 
/**
 * @var WW\Module $this
 * @var WW\Cauldron\Recipe $recipe
 * @var array $possibleTypes
 */

$this->addJsFile('triggers.js');
?>

<h1>
    <i class="fa fa-pencil"></i>
    <?=$this->witch->name ?>
</h1>
<p><em><?=$this->witch->data?></em></p>
    
<?php $this->include('alerts.php', ['alerts' => $this->ww->user->getAlerts()]); ?>

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

<style>
    .restriction-settings {
        width: 100%;
    }
    .fieldsets-container {
        max-width: 700px;
        margin-top: 10px;
    }
    .fieldsets-container fieldset li {
        display: flex;
        justify-content: space-between;
        margin-bottom: 4px;
    }

    fieldset {
        border: 1px solid #ccc;
        box-shadow: 5px 5px 5px #ccc;
    }
        fieldset > legend {
            font-weight: bold;
        }
        fieldset.right {
            display: flex;
            justify-content: end;
            align-items: center;
        }
            fieldset.right button {
                margin-left: 8px;
            }
    #name-display,
    #file-display {
        cursor: pointer;
    }

    #name-input,
    #file-input {
        display: none;
    }

    .fieldsets-container > fieldset:first-child a.up-fieldset {
        display: none;
    }
    .fieldsets-container > fieldset:last-child a.down-fieldset {
        display: none;
    }
    .new-content-actions {
        display: flex;
        justify-content: end;
    }
    .new-content-actions.hidden {
        display: none;
    }
    #new-content legend {
        display: none;
    }
    #new-content legend.new-content-form {
        display: block;
    }
</style>

<script>
    $(document).ready(function() {

        // RESTRICTIONS 
        document.querySelectorAll("select.check-restriction-toggle").forEach( 
            (select) => select.addEventListener( 'change', (e) => checkRestrictionsToggle(e) )
        );

        function checkRestrictionsToggle( event )
        {
            let enabled =  event.target.selectedOptions[0].attributes["data-restrictions"].value === "on";
            let display = enabled? 'block': 'none';
            event.target.parentNode.parentNode.querySelector(".recipe-type-toggle").style.display = display;

            return enabled;
        }

        document.querySelectorAll("select.content-add-trigger").forEach( 
            (select) => select.addEventListener( 'change', (e) => contentAddTrigger(e) )
        );

        function contentAddTrigger( event )
        {
            let candidateItem   = event.target.value;
            let candidateLabel  = event.target.selectedOptions[0].innerHTML.trim();
            let actionName      = event.target.attributes["data-target"].value;
            let items           = event.target.parentNode.parentNode.querySelectorAll('[name="'+actionName+'[]"]');

            event.target.value  =  0;

            if( Array.from(items)
                        .map( (input) => input.value )
                        .includes( candidateItem ) 
            ){  
                return false; 
            }

            let newEntry = document.createElement("a");
            newEntry.classList.add('remove-content');

            let newInput = document.createElement("input");
            newInput.setAttribute('type', 'hidden');
            newInput.setAttribute('name', actionName+'[]');
            newInput.setAttribute('value', candidateItem);

            newEntry.appendChild( newInput );

            newEntry.appendChild( document.createTextNode(' '+candidateLabel+' ') );

            let newIcon = document.createElement("i");
            newIcon.classList.add('fa');
            newIcon.classList.add('fa-times');

            newEntry.appendChild( newIcon );
            newEntry.addEventListener('click', () => newEntry.remove());
            
            let addType         = actionName.slice( actionName.lastIndexOf('-') + 1 );
            event.target.parentNode.parentNode.querySelector('.'+addType+"-contents-container").append( newEntry );

            return true;
        }

        document.querySelectorAll(".remove-content").forEach( 
            (elmt) => elmt.addEventListener('click', () => elmt.remove())
        );

        // NAME / FILE (hidden inputs)
        ['name', 'file'].forEach( (hiddenInput) => {

            document.querySelector('#'+hiddenInput+'-display').addEventListener('click', () => {
                document.querySelector('#'+hiddenInput+'-input').style.display = 'block';
                document.querySelector('#'+hiddenInput+'-input').focus();
                document.querySelector('#'+hiddenInput+'-display').style.display = 'none';
            });

            document.querySelector('#'+hiddenInput+'-input').addEventListener('focusout', () => {
                document.querySelector('#'+hiddenInput+'-input').style.display = 'none';
                document.querySelector('#'+hiddenInput+'-display').style.display = 'block';
            });

            document.querySelector('#'+hiddenInput+'-input').addEventListener('change', () => {
                let value = document.querySelector('#'+hiddenInput+'-input').value;

                if( value !== '' ){
                    document.querySelector('#'+hiddenInput+'-display').innerHTML = value;
                }
                else {
                    document.querySelector('#'+hiddenInput+'-input').value = document.querySelector('#'+hiddenInput+'-display').innerHTML;
                }

                document.querySelector('#'+hiddenInput+'-input').style.display = 'none';
                document.querySelector('#'+hiddenInput+'-display').style.display = 'block';
            });
        });

        // FIELDSETS
        document.querySelectorAll("fieldset a.remove-fieldset").forEach( 
            (anchor) => anchor.addEventListener( 'click', () => removeFieldSet(anchor) )
        );

        function removeFieldSet( anchor )
        {
            if( confirm('Confirm Remove') ){
                anchor.parentNode.parentNode.remove();
            }

            return;
        }
        
        document.querySelectorAll("fieldset a.up-fieldset").forEach( 
            (anchor) => anchor.addEventListener( 'click', () => upFieldSet(anchor) )
        );

        function upFieldSet( anchor )
        {
            let fieldset    = anchor.parentNode.parentNode;
            let index       = Array.prototype.slice.call( document.querySelectorAll('#contents fieldset') ).indexOf( fieldset );
            if( index === 0 ){
                return;
            }

            document.querySelector('#contents').insertBefore(fieldset, document.querySelectorAll('#contents fieldset')[ index-1 ] );
            return;
        }

        document.querySelectorAll("fieldset a.down-fieldset").forEach( 
            (anchor) => anchor.addEventListener( 'click', () => downFieldSet(anchor) )
        );

        function downFieldSet( anchor )
        {
            let fieldset    = anchor.parentNode.parentNode;
            let index       = Array.prototype.slice.call( document.querySelectorAll('#contents fieldset') ).indexOf( fieldset );
            document.querySelector('#contents').insertBefore( document.querySelectorAll('#contents fieldset')[ index+1 ], fieldset )
            return;
        }

        document.querySelector('[name="NEW_CONTENT_NAME-name"]').addEventListener( 'input', (e) => {
            let name = e.target.value;
            document.querySelector('.new-content-actions').classList.remove('hidden');
            let refNames = [];
            document.querySelectorAll('#contents input.ref-name').forEach(
                (input) =>  refNames.push( input.value )
            );
            
            if( name === '' || refNames.includes(name) ){
                document.querySelector('.new-content-actions').classList.add('hidden');
            }
        });


        document.getElementById('add-content').addEventListener('click', function(){

            let fieldset    = document.getElementById('new-content').getElementsByTagName('fieldset')[0]; 
            let name        = document.querySelector('[name="NEW_CONTENT_NAME-name"]').value;
            let type        = document.querySelector('[name="NEW_CONTENT_NAME-type"]').value;
            let mandatory   = document.querySelector('[name="NEW_CONTENT_NAME-mandatory"]').checked;
            let min         = document.querySelector('[name="NEW_CONTENT_NAME-min"]').value;
            let max         = document.querySelector('[name="NEW_CONTENT_NAME-max"]').value;

            let newElement  = fieldset.cloneNode(true);

            fieldset.querySelector('[name="NEW_CONTENT_NAME-name"]').value          = '';
            fieldset.querySelector('[name="NEW_CONTENT_NAME-type"]').value          = 0;
            fieldset.querySelector('[name="NEW_CONTENT_NAME-mandatory"]').checked   = false;
            fieldset.querySelector(".recipe-type-toggle").style.display          = 'none';
            fieldset.querySelector('[name="NEW_CONTENT_NAME-min"]').value           = 0;
            fieldset.querySelector('[name="NEW_CONTENT_NAME-max"]').value           = -1;
            fieldset.querySelector('.accepted-contents-container').innerHTML        = '';
            fieldset.querySelector('.refused-contents-container').innerHTML         = '';

            document.querySelector('.new-content-actions').classList.add('hidden');

            newElement.querySelector('legend.new-content-form').remove();
            newElement.querySelector('.new-content-actions').remove();

            newElement.querySelector('legend').innerHTML = newElement.querySelector('legend').innerHTML.replace('NEW_CONTENT_NAME',name)
            newElement.querySelector("a.down-fieldset").addEventListener( 'click', (e) => downFieldSet(e.target) );
            newElement.querySelector("a.up-fieldset").addEventListener( 'click', (e) => upFieldSet(e.target) );
            newElement.querySelector("a.remove-fieldset").addEventListener( 'click', (e) => removeFieldSet(e.target) )

            newElement.querySelector('[name="NEW_CONTENT_NAME-name"]').setAttribute('name', name+'-name');
            newElement.querySelector('[name="NEW_CONTENT_NAME-type"]').value = type;
            newElement.querySelector('[name="NEW_CONTENT_NAME-type"]').addEventListener( 'change', (e) => checkRestrictionsToggle(e) );
            newElement.querySelector('[name="NEW_CONTENT_NAME-type"]').setAttribute('name', name+'-type');
            newElement.querySelector('[name="NEW_CONTENT_NAME-mandatory"]').setAttribute('name', name+'-mandatory');
            newElement.querySelector('[name="NEW_CONTENT_NAME-min"]').value = min;
            newElement.querySelector('[name="NEW_CONTENT_NAME-min"]').setAttribute('name', name+'-min');
            newElement.querySelector('[name="NEW_CONTENT_NAME-max"]').value = max;
            newElement.querySelector('[name="NEW_CONTENT_NAME-max"]').setAttribute('name', name+'-max');

            newElement.querySelector('[data-target="NEW_CONTENT_NAME-accepted"]').setAttribute('data-target', name+'-accepted');
            newElement.querySelector('[data-target="'+name+'-accepted"]').addEventListener( 'change', (e) => contentAddTrigger(e) );
            newElement.querySelectorAll('[name="NEW_CONTENT_NAME-accepted\[\]"]').forEach( 
                (input) => input.setAttribute('name', name+'-accepted[]')
            );
            newElement.querySelector('[data-target="NEW_CONTENT_NAME-refused"]').setAttribute('data-target', name+'-refused');
            newElement.querySelector('[data-target="'+name+'-refused"]').addEventListener( 'change', (e) => contentAddTrigger(e) );
            newElement.querySelectorAll('[name="NEW_CONTENT_NAME-refused\[\]"]').forEach( 
                (input) => input.setAttribute('name', name+'-refused[]')
            );

            newElement.querySelectorAll(".remove-content").forEach( 
                (elmt) => elmt.addEventListener('click', () => elmt.remove())
            );

            document.querySelector('#contents').append( newElement );
        });
    });
</script>