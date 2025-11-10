document.addEventListener("DOMContentLoaded", () => {

    document.querySelectorAll(".cauldron-add-actions").forEach( 
        container => {
            let showButton      = container.querySelector(".show-form");
            let form            = container.querySelector(".add-form");
            let saveButton      = form.querySelector("button");
            let typeSelector    = form.querySelector("select");
            let nameInput       = form.querySelector("input");
            let lastType        = typeSelector.value;

            showButton.addEventListener( 'click', e => {
                e.preventDefault();
                
                showButton.style.display    = 'none';
                form.style.display          = 'flex';
                form.focus();
                
                return false;
            });

            form.querySelector("a.hide-form").addEventListener('click', () => {
                cancelAddForm(form, showButton);
            });

            document.addEventListener('click', function(e){
                if( form.style.display !== 'none' 
                    && !form.contains(e.target) 
                    && !showButton.contains(e.target) 
                ){
                    cancelAddForm(form, showButton);
                }                    
            });

            saveButton.addEventListener('click', e => {
                e.preventDefault();
                
                if( !saveButton.classList.contains("disabled") )
                {
                    let action = document.createElement('input');
                    action.setAttribute('type', "hidden");
                    action.setAttribute('name', "action");
                    action.value = saveButton.dataset.action;

                    let inputName = document.createElement('input');
                    inputName.setAttribute('type', "hidden");
                    inputName.setAttribute('name', form.dataset.input + '[name]' );
                    inputName.value = nameInput.value;
                    
                    let inputType = document.createElement('input');
                    inputType.setAttribute('type', "hidden");
                    inputType.setAttribute('name', form.dataset.input + '[type]' );
                    inputType.value = typeSelector.value;
                    
                    let actionForm = document.querySelector('#' + saveButton.dataset.target);
                    actionForm.append(inputName);
                    actionForm.append(inputType);
                    actionForm.append(action);
                    actionForm.submit();
                }
                return false;
            });

            typeSelector.addEventListener('focus', () => {
                lastType = typeSelector.value;
            });

            typeSelector.addEventListener('change', () => {
                if( nameInput.value === "" 
                    || nameInput.value === lastType
                ){
                    nameInput.value = typeSelector.value;
                }

                nameInput.select();
                nameInput.focus();

                checkAddFormValidity( typeSelector, nameInput, saveButton );
            });
            nameInput.addEventListener('input', () => checkAddFormValidity( typeSelector, nameInput, saveButton ));
        }
    );

    function checkAddFormValidity( select, input, button )
    {
        let name = select.options[select.selectedIndex].dataset.name;            
        if( name !== undefined )
        {
            if( input.dataset.previous === undefined ){
                input.dataset['previous'] = input.value;  
            }

            input.value = name;
            input.setAttribute('type', 'hidden');
        }
        else 
        {
            input.setAttribute('type', 'text');                

            if( input.dataset.previous !== undefined )
            {
                input.value = input.dataset.previous;
                input.removeAttribute('data-previous');
            }
        }
        
        if( !button.classList.contains("disabled") ){
            button.classList.add("disabled");
        }
        if( select.value !== "" && input.value.trim() !== '' ){
            button.classList.remove("disabled");
        }
    }

    function cancelAddForm(form, showButton)
    {
        if( form.querySelector('select > option').length > 1 )
        {
            form.querySelector('select').value  = "";
            form.querySelector('input').value   = "";
        }
        showButton.style.display    = 'block';
        form.style.display          = 'none';
    }

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
        let fieldset        = anchor.parentNode.parentNode;
        let container       = fieldset.parentNode;
        let fieldsetArray   = Array.from(fieldset.parentNode.childNodes).filter( element => element.type === fieldset.type );
        let index           = fieldsetArray.indexOf(fieldset);

        if( index === 0 ){
            return;
        }

        let targetPosition  = Array.prototype.slice.call( container.childNodes ).indexOf( fieldsetArray[index - 1] );    

        container.insertBefore(fieldset, container.childNodes[ targetPosition ] );
        return;
    }

    document.querySelectorAll("fieldset a.down-fieldset").forEach( 
        (anchor) => anchor.addEventListener( 'click', () => downFieldSet(anchor) )
    );

    function downFieldSet( anchor )
    {
        let fieldset        = anchor.parentNode.parentNode;
        let container       = fieldset.parentNode;
        let fieldsetArray   = Array.from(fieldset.parentNode.childNodes).filter( element => element.type === fieldset.type );
        let index           = fieldsetArray.indexOf(fieldset);

        if( index+1 === fieldsetArray.length ){
            return;
        }

        let target  = fieldsetArray[index + 2];
        if( target === undefined )
        {
            container.appendChild( fieldset );
            return;
        }

        let targetPosition  = Array.prototype.slice.call( container.childNodes ).indexOf( target );    

        container.insertBefore(fieldset, container.childNodes[ targetPosition ] );
        return;
    }
    
    document.querySelectorAll('span.span-input-toggle').forEach(
        span => span.addEventListener('click', () => {
            
            let oldInput = document.querySelector(
                'input[name="' 
                + span.dataset.input
                + '"]'
            );

            if( oldInput ){
                oldInput.remove();
            }
            
            let input       = document.createElement('input');
            input.setAttribute('type', "text");
            input.setAttribute('name', span.dataset.input);
            input.value     = span.innerHTML.trim();

            span.append(input);
            span.parentNode.insertBefore(input, span);
            span.style.display  = 'none';
            input.style.display = 'inline-block';
            input.focus();


            input.addEventListener('focusout', () => checkSpanInputToggleValidity( input, span ));
            input.addEventListener('change', () => checkSpanInputToggleValidity( input, span ));

        })
    );

    function checkSpanInputToggleValidity(input, span)
    {
        if( input.value === '' || input.value === span.dataset.value )
        {
            span.innerHTML = span.dataset.value;
            input.remove();
        }
        else 
        {
            span.innerHTML      = input.value;
            input.style.display = 'none';
        }

        span.style.display  = 'inline-block';
    }

});
