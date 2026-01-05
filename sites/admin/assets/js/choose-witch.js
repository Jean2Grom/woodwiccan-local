async function chooseWitch( conditions={}, label="Choose witch" )
{
    let chooseWitchDom  = document.getElementById('choose-witch');
    let uuid            = self.crypto.randomUUID();

    // Draggable div js part
    let pos1 = 0, pos2 = 0, pos3 = 0, pos4 = 0;
    chooseWitchDom.querySelector('h3').onmousedown = dragMouseDown;

    function dragMouseDown(e) 
    {
        e = e || window.event;
        e.preventDefault();
        // get the mouse cursor position at startup:
        pos3 = e.clientX;
        pos4 = e.clientY;
        document.onmouseup = closeDragElement;
        // call a function whenever the cursor moves:
        document.onmousemove = elementDrag;
    }

    function elementDrag(e) 
    {
        e = e || window.event;
        e.preventDefault();
        // calculate the new cursor position:
        pos1 = pos3 - e.clientX;
        pos2 = pos4 - e.clientY;
        pos3 = e.clientX;
        pos4 = e.clientY;
        // set the element's new position:
        chooseWitchDom.style.top = (chooseWitchDom.offsetTop - pos2) + "px";
        chooseWitchDom.style.left = (chooseWitchDom.offsetLeft - pos1) + "px";
    }

    function closeDragElement() 
    {
        // stop moving when mouse button is released:
        document.onmouseup = null;
        document.onmousemove = null;
    }    
    // End of Draggable div js part
    
    chooseWitchDom.setAttribute('uuid', uuid);

    return new Promise( (resolve) => {
        chooseWitchDom = document.querySelector('#choose-witch[uuid="'+uuid+'"]');

        chooseWitchDom.querySelector('h3 span').innerHTML   = label;
        chooseWitchDom.style.display                        = 'block';
        chooseWitchDom.style.top = document.querySelector('html').scrollTop + "px";
        
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
    let witchDom = document.querySelector('#choose-witch .arborescence-level__witch[data-id="'+witchId+'"]');
    
    if( witchDom.length === 0 ){
        return false;
    }
    
    let label = witchDom.querySelector('.arborescence-level__witch__name').innerHTML.trim();
    
    if( label === "" ){
        return witchId;
    }
    
    return label;
}