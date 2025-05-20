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