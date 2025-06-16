async function chooseWitch( conditions={}, label="Choose witch" )
{
    let chooseWitchDom  = document.getElementById('choose-witch');
    let uuid            = self.crypto.randomUUID();

    chooseWitchDom.setAttribute('uuid', uuid);

    return new Promise( (resolve) => {
        chooseWitchDom = document.querySelector('#choose-witch[uuid="'+uuid+'"]');

        chooseWitchDom.querySelector('h3 span').innerHTML   = label;
        chooseWitchDom.style.display                        = 'block';
        
        chooseWitchDom.querySelector('.close').addEventListener( 'click', 
            () => resolve( false ) 
        );

        chooseWitchDom.addEventListener('click', 
            ( e ) => {
                if( e.target.classList.contains('arborescence-level__witch__name') )
                {
                    let witch = e.target.closest('.arborescence-level__witch');

                    let match = true;
                    for( var data in conditions ){
                        if( witch.dataset[ data ].toString() !== conditions[ data ].toString() ){
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
        let chooseWitchDom = document.getElementById('choose-witch');
        chooseWitchDom.setAttribute('uuid', '');

        document.querySelector('#choose-witch').style.display = 'none';
        return witchId;
    });
}

function readWitchName( witchId )
{
    let witchDom = document.querySelector('#choose-witch .arborescence-level__witch[data-id='+witchId+']');
    
    if( witchDom.length === 0 ){
        return false;
    }
    
    let label = witchDom.querySelector('.arborescence-level__witch__name').html().trim();
    
    if( label === "" ){
        return witchId;
    }
    
    return label;
}