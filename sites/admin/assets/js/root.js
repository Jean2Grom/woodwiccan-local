$(document).ready(function()
{
    $('body').click(function(e)
    {
        if( $(e.target).is('button') ){
            return;
        }
        
        if( $(e.target).is('.content__data') )
        {
            $('#witch__data').val( $('.content__data').html() );
            $('.content__data').hide();
            $('#data-edit').show();
        }
        else if( $(e.target).parents('#data-edit').length == 0 )
        {
            $('#data-edit').hide();
            $('.content__data').show();
        }
    });

});