$(document).ready( function(){
    $('.change-image').change(function(){
        let filename = $(this).val().split(/(\\|\/)/g).pop();
        
        let [file] = this.files;
        
        $(this).parents('.change-file-container').next('input.file-input').val( filename );
        if( file ){
            $(this).parents('.change-file-container').prev('.current-file-display').find('.new-image-target').attr('src', URL.createObjectURL(file));
        }
        
        $(this).parents('.change-file-container').prev('.current-file-display').show();
        $(this).parents('.change-file-container').hide();
    });
    
    $('.delete-image').click(function(){
        if( confirm('Are you sure to remove image ?') )
        {
            $(this).parents('.current-file-display').hide();
            $(this).parents('fieldset').find('input[type="hidden"]').val( '' );
            
            $(this).parents('.current-file-display').find('.current-file-target').hide();
            $(this).parents('.current-file-display').hide();
            $(this).parents('.current-file-display').next('.change-file-container').show();
            $(this).parents('.current-file-display').next('.change-file-container').find('.change-image').val('');
            
            $(this).parents('.current-file-display').find('input.file-input').val( '' );
            
        }
        
        return false;
    });
    
});