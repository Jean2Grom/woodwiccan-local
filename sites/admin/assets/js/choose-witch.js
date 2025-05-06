async function chooseWitch( conditions={}, label="Choose witch" )
{
    return new Promise( (resolve) => {
        let chooseWitchDom = document.getElementById('choose-witch');

        chooseWitchDom.querySelector('h3 span').innerHTML = label;
        chooseWitchDom.style.display = 'block';
        
        chooseWitchDom.querySelector('.close').addEventListener( 'click', () => resolve( false ) );
        
        $('#choose-witch').on('click', '.arborescence-level__witch__name', function()
        {
            let witch = $(this).parents('.arborescence-level__witch');
            
            let match = true;
            for( var data in conditions ) {
                if( $(witch).data( data ) !== conditions[ data ] ){
                    match = false;
                }
            }
            
            if( match ){
                resolve( $(witch).data('id') );                
            }            
        });
        
    }).then(( witchId ) => {
        $('#choose-witch').off( "click", ".arborescence-level__witch__name" );
        $('#choose-witch').off( "click", ".close" );
        $('#choose-witch').hide();
        
        return witchId;
    });
}

function readWitchName( witchId )
{
    let witchDom = $('#choose-witch .arborescence-level__witch[data-id='+witchId+']');
    
    if( witchDom.length === 0 ){
        return false;
    }
    
    let label = $(witchDom).find( ".arborescence-level__witch__name").html().trim();
    
    if( label === "" ){
        return witchId;
    }
    
    return label;
}