async function chooseWitch( conditions={}, label="Choose witch" )
{
    return new Promise( (resolve) => {
        let chooseWitchDom = document.getElementById('choose-witch');

        chooseWitchDom.querySelector('h3 span').innerHTML = label;
        chooseWitchDom.style.display = 'block';
        
        chooseWitchDom.querySelector('.close').addEventListener( 'click', () => resolve( false ) );
        
        document.querySelector('#choose-witch').addEventListener('click', 
            e => {
                if( e.target.classList.contains('arborescence-level__witch__name') ){

                    let witch = e.target.closest('.arborescence-level__witch');

                    console.log( witch );
                    console.log('youpi3');

                    let match = true;
                    for( var data in conditions ) {
                        if( witch.dataset[ data ] !== conditions[ data ] ){
                            match = false;
                        }
                    }

                    if( match ){
                        resolve( witch.dataset.id );                
                    }            
                }
            }
        );

    }).then(( witchId ) => {

        //document.querySelector('#choose-witch').removeEventListener('click')
        document.querySelector('#choose-witch').style.display = 'none';
        
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