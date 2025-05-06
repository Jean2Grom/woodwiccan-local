$(document).ready( function(){
    $('.change-file').change(function(){
        let filename = $(this).val().split(/(\\|\/)/g).pop();
        $(this).parents('.change-file-container').next('input.file-input').val( filename );
        
        $(this).parents('.change-file-container').prev('.current-file-display').find('.new-file-target').html( filename );
        $(this).parents('.change-file-container').prev('.current-file-display').show();
        $(this).parents('.change-file-container').hide();
    });
    
    $('.delete-file').click(function(){
        if( confirm('Are you sure to remove file ?') )
        {
            $(this).parents('.current-file-display').find('.current-file-target').hide();
            $(this).parents('.current-file-display').hide();
            $(this).parents('.current-file-display').next('.change-file-container').show();
            $(this).parents('.current-file-display').next('.change-file-container').find('.change-file').val('');
            
            $(this).parents('.current-file-display').next('input.file-input').val( '' );
        }
        
        return false;
    });    
});