document.addEventListener("DOMContentLoaded", () => 
{
    document.querySelectorAll('.view-profile').forEach(
        clickDom => clickDom.addEventListener( "click", 
        () => {
            let profileDom  = clickDom.closest('.profile-container');
            let hash        = '#tab-profile-'+ profileDom.dataset.id;
            let tab         = document.querySelector('a[href="'+hash+'"]').parentElement;
            let tabDom      = document.querySelector( hash );

            tab.style.display = 'block';
            triggerTabItem( hash );

            tabDom.querySelectorAll('.box.view__profile').forEach(
                viewDom => viewDom.style.display = 'block'
            );

            tabDom.querySelectorAll('.box.edit__profile').forEach(
                editDom => editDom.style.display = 'none'
            );
        })
    );

    document.querySelectorAll('.edit-profile').forEach(
        clickDom => clickDom.addEventListener( "click", 
        () => {
            let profileDom  = clickDom.closest('.profile-container');
            let hash        = '#tab-profile-'+ profileDom.dataset.id;
            let tab         = document.querySelector('a[href="'+hash+'"]').parentElement;
            let tabDom      = document.querySelector( hash );

            tab.style.display = 'block';
            triggerTabItem( hash );

            tabDom.querySelectorAll('.box.view__profile').forEach(
                viewDom => viewDom.style.display = 'none'
            );

            tabDom.querySelectorAll('.box.edit__profile').forEach(
                editDom => editDom.style.display = 'block'
            );
        })
    );

    let siteFilter = document.getElementById('profile-list-site-filter');
    siteFilter.addEventListener('change',
        () => {
            let site = siteFilter.value;
            document.querySelectorAll('.profile-container').forEach(
                profileDom => {
                    if( site === '' 
                        || profileDom.classList.contains('profile-site-all')
                        || profileDom.classList.contains('profile-site-'+site)
                    ){
                        profileDom.style.display = 'table-row';
                    }
                    else {
                        profileDom.style.display = 'none';
                    }
                }
            );
        }
    );

    // Toggles
    document.querySelectorAll('.box.edit-profile').forEach( boxDom => boxDom.style.display = 'none' );
    document.querySelectorAll('button.view-edit-profile-toggle').forEach( 
        buttonDom => buttonDom.addEventListener('click', e => {
            e.preventDefault();

            let profileId   =   buttonDom.closest('.box').dataset.profile;
            let query       =   '.view__profile[data-profile="'+profileId+'"] ';
            query           +=  ', .edit__profile[data-profile="'+profileId+'"] ';

            document.querySelectorAll( query ).forEach( 
                toggleDom => {
                    if( toggleDom.style.display === 'none' ){
                        toggleDom.style.display = 'block';
                    }
                    else {
                        toggleDom.style.display = 'none';
                    }
                }
            );
            
            return false;
        })
    );

    // Site 
    document.querySelectorAll('.profile-site').forEach(
        siteInput => siteInput.addEventListener('change', () => {
            let formDom = siteInput.closest('form');
            let site    = siteInput.value;

            if( site === '*' ){
                site = 'all';
            }

            formDom.querySelectorAll('.profile-site-displayed').forEach(
                profileDom => {
                    if( profileDom.classList.contains('profile-site-'+site) ){
                        profileDom.style.display = 'block';
                    }
                    else {
                        profileDom.style.display = 'none';
                    }
                }
            );
        })
    );
    
    // Policy
    document.querySelectorAll('.edit-profile-form').forEach(
        formDom => formDom.addEventListener('click', e => {
            if( e.target.nodeName === 'BUTTON' && e.target.classList.contains('policy-witch') )
            {
                e.preventDefault();
                let policyDom = e.target.closest('.policy-container');

                chooseWitch().then( witchId => {

                    if( witchId === false ){
                        return;
                    }
                    
                    let witchName           = readWitchName( witchId );
                    let witchBaseHref       = policyDom.querySelector('.policy-witch-display').getAttribute('href').split('?')[0];
                    let witchNameDisplayDom = policyDom.querySelector('.policy-witch-display'); 

                    policyDom.querySelector('button.policy-witch').style.display = 'none';

                    witchNameDisplayDom.innerHTML = witchName;
                    witchNameDisplayDom.setAttribute('href', witchBaseHref + '?id=' + witchId);
                    witchNameDisplayDom.style.display = 'block';

                    policyDom.querySelector('.unset-policy-witch').style.display    = 'block';
                    policyDom.querySelector('.policy-witch-set').style.display      = 'block';
                    policyDom.querySelector('.policy-witch-id').value               =  witchId;
                });
            }
            else if( e.target.classList.contains('unset-policy-witch') )
            {
                e.preventDefault();
                let policyDom = e.target.closest('.policy-container');
                
                policyDom.querySelector('button.policy-witch').style.display    = 'block';
                policyDom.querySelector('.policy-witch-display').style.display  = 'none';
                policyDom.querySelector('.unset-policy-witch').style.display    = 'none';
                policyDom.querySelector('.policy-witch-set').style.display      = 'none';
                policyDom.querySelector('.policy-witch-id').value               = '';
            }
            // Remove / Add on Edit profile
            else if( e.target.classList.contains('add-policy-action') )
            {
                e.preventDefault();

                let formDom         = e.target.closest('form.edit-profile-form');
                let newPolicy       = formDom.querySelector('.policy-container').cloneNode(true);
                newPolicy.classList.remove('policy-pattern');
                newPolicy.classList.add('new-policy');

                let newPolicyIndex  = formDom.querySelectorAll('.policy-container.new-policy').length;
                
                newPolicy.querySelector('.policy-id').value = 'new-' + newPolicyIndex;
                newPolicy.querySelector('.policy-witch-set input[type="checkbox"]').value = 'new-' + newPolicyIndex;
                
                formDom.querySelector('tbody').append( newPolicy );
                formDom.querySelector('.policy-container.new-policy').style.dusplay = 'block';
            }
            else if( e.target.closest('a') && e.target.closest('a').classList.contains('policy-remove') )
            {
                e.preventDefault();
                let policyDom = e.target.closest('.policy-container');

                let policyId  = policyDom.querySelector('.policy-id').value;
        
                policyDom.querySelector('.policy-deleted').value = policyId;
                policyDom.style.display = 'none';
            }
            
            return false;
        })
    );

    // TODO
    $('.undo-profile-action').click(function()
    {
        let formDom         = $(this).parents('form.edit-profile-form');
        
        $(formDom).find('.unset-policy-witch').trigger('click');
        
        $(formDom).find('input, select, textarea').each(function( i, input )
        {
            if( $(input).data('init') !== undefined ){
                $(input).val( $(input).data('init') );
            }
        });
        
        $(formDom).find('.profile-site').trigger('change');        
        $(formDom).find('.new-policy').remove();
        
        $(formDom).find('.policy-deleted').each(function( i, input ){
            if( $(input).val() !== "" && $(input).val() > 0 )
            {
                $(input).val('');
                $(input).parents('.policy-container').show();
            }
            
        });
        
        $(formDom).find('.policy-witch-id').each(function( i, input )
        {
            let witchId = $(this).val();
            
            if( witchId !== "" )
            {
                let witchName       = readWitchName(witchId);
                let policyDom       = $(this).parents('.policy-container');
                let witchBaseHref   = $(policyDom).find('.policy-witch-display').attr('href').split('?')[0];
                
                $(policyDom).find('button.policy-witch').hide();
                $(policyDom).find('.policy-witch-display').html( witchName ).attr('href', witchBaseHref + '?id=' + witchId).show();
                $(policyDom).find('.unset-policy-witch').show();
                $(policyDom).find('.policy-witch-set').show();                
            }
        });
        
        $(formDom).find('.policy-witch-set input[type="checkbox"]').each(function( i, input ){
            if( $(this).parents('.policy-pattern').length === 0 ){
                $(this).prop('checked', ( $(this).data('init') === 1 ) );
            }
        });
        
        return false;
    });
    
    // Add/ Remove on Create profile
    $('#create-profile-form').on('click', '.add-policy-action',  function()
    {
        let formDom         = $(this).parents('form');
        let newPolicy       = $(formDom).find('.policy-container').first().clone();
        let newPolicyIndex  = $(formDom).find('.policy-container.new-policy').length;
        
        $(newPolicy).find('.policy-id').val('new-' + newPolicyIndex);
        $(newPolicy).find('.policy-witch-set input[type="checkbox"]').val('new-' + newPolicyIndex);
        
        $(formDom).find('tbody').append( newPolicy );
        $(formDom).find('.policy-container').last().addClass('new-policy').show();
        
        return false;
    });
    
    $('#create-profile-form').on('click', '.policy-remove',  function()
    {
        let policyDom       = $(this).parents('.policy-container');
        $(policyDom).remove();
        
        return false;
    });

    
    $('#create-profile-form').on('click', 'button.policy-witch',  function()
    {
        chooseWitch().then( (witchId) => {
            if( witchId === false ){
                return;
            }
            
            let witchName       = readWitchName(witchId);
            let policyDom       = $(this).parents('.policy-container');
            let witchBaseHref   = $(policyDom).find('.policy-witch-display').attr('href').split('?')[0];
            
            $(policyDom).find('button.policy-witch').hide();
            $(policyDom).find('.policy-witch-display').html( witchName ).attr('href', witchBaseHref + '?id=' + witchId).show();
            $(policyDom).find('.unset-policy-witch').show();
            $(policyDom).find('.policy-witch-set').show();
            
            $(policyDom).find('.policy-witch-id').val( witchId );
        });
        
        return false;
    });
    
    $('#create-profile-form').on('click', '.unset-policy-witch',  function()
    {
        let policyDom       = $(this).parents('.policy-container');
        
        $(policyDom).find('button.policy-witch').show();
        $(policyDom).find('.policy-witch-display').hide();
        $(policyDom).find('.unset-policy-witch').hide();
        $(policyDom).find('.policy-witch-set').hide();        
        $(policyDom).find('.policy-witch-id').val('');
        
        return false;
    });

    
    $('.reset-profile-action').click(function()
    {
        let formDom         = $(this).parents('form#create-profile-form');
        
        $(formDom).find('.profile-name').val('');
        $(formDom).find('.profile-site').val('*');
        $(formDom).find('.profile-site').trigger('change');
        $(formDom).find('.new-policy').remove();
        
        return false;
    });
});