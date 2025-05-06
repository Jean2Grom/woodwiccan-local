$(document).ready(function()
{
    // List
    $('.view-profile').click(function()
    {
        let profileDom  = $(this).parents('.profile-container');
        let hash        = '#tab-profile-'+ $(profileDom).data('id');
        
        $('a[href="'+hash+'"]').parent().show();
        triggerTabItem( hash );
        
        $( hash ).find('.box.view__profile').show();
        $( hash ).find('.box.edit__profile').hide();
    });
    
    $('.edit-profile').click(function()
    {
        let profileDom  = $(this).parents('.profile-container');
        let hash        = '#tab-profile-'+ $(profileDom).data('id');
        
        $('a[href="'+hash+'"]').parent().show();
        triggerTabItem( hash );
        
        $( hash ).find('.box.view__profile').hide();
        $( hash ).find('.box.edit__profile').show();
    });
    
    $('#profile-list-site-filter').change(function()
    {
        let siteFilter = $(this).val();
        
        if( siteFilter === '' ){
            $('.profile-container').show();
        }
        else 
        {
            $('.profile-container').hide();
            $('.profile-container.profile-site-all').show();
            $('.profile-container.profile-site-'+siteFilter).show();
            
        }
        return false;
    });
    
    // Toggles
    $('.box.edit__profile').hide();
    $('button.view-edit-profile-toggle').click(function()
    {
        let profileId  = $(this).parents('.box').data('profile');
        
        $('.view__profile[data-profile="'+profileId+'"]').toggle();
        $('.edit__profile[data-profile="'+profileId+'"]').toggle();
        
        return false;
    });
    
    
    // Site 
    $('.profile-site').change(function()
    {
        let formDom = $(this).parents('form');
        let siteVal = $(this).val();
        
        if( siteVal === '*' ){
            siteVal = 'all';
        }
        
        $(formDom).find('.profile-site-displayed').hide();
        $(formDom).find('.profile-site-displayed.profile-site-'+siteVal).show();
    });
    
    // Policy
    $('.edit-profile-form').on('click', 'button.policy-witch',  function()
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
    
    $('.edit-profile-form').on('click', '.unset-policy-witch',  function()
    {
        let policyDom       = $(this).parents('.policy-container');
        
        $(policyDom).find('button.policy-witch').show();
        $(policyDom).find('.policy-witch-display').hide();
        $(policyDom).find('.unset-policy-witch').hide();
        $(policyDom).find('.policy-witch-set').hide();        
        $(policyDom).find('.policy-witch-id').val('');
        
        return false;
    });
    
    // Remove / Add on Edit profile
    $('.edit-profile-form').on('click', '.policy-remove',  function()
    {
        let policyDom       = $(this).parents('.policy-container');
        let policyId        = $(policyDom).find('.policy-id').val();
        
        $(policyDom).find('.policy-deleted').val( policyId );
        $(policyDom).hide();
        
        return false;
    });
    
    $('.edit-profile-form').on('click', '.add-policy-action',  function()
    {
        let formDom         = $(this).parents('form.edit-profile-form');
        let newPolicy       = $(formDom).find('.policy-container').first().clone();
        let newPolicyIndex  = $(formDom).find('.policy-container.new-policy').length;
        
        $(newPolicy).find('.policy-id').val('new-' + newPolicyIndex);
        $(newPolicy).find('.policy-witch-set input[type="checkbox"]').val('new-' + newPolicyIndex);
        
        $(formDom).find('tbody').append( newPolicy );
        $(formDom).find('.policy-container').last().addClass('new-policy').show();
        
        return false;
    });
    
    
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