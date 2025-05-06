document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll('.image-edit-container').forEach( container => {
        
        container.querySelectorAll('.upload-image-input').forEach(
            input =>  input.addEventListener( 'change', () => { 
                let filename    = input.value.split(/(\\|\/)/g).pop();
                let [file]      = input.files;

                console.log( filename, file );
                console.log( input.files );

                if( !file ){
                    return false;
                }
    
                container.querySelectorAll('.image-display').forEach( 
                    elmnt => elmnt.style.display = 'block'
                );
                
                container.querySelectorAll('.file-input').forEach( 
                    elmnt => elmnt.style.display = 'none'
                );
                
                container.querySelectorAll('.new-image-focus').forEach(
                    img => img.src = URL.createObjectURL(file)
                );

                container.querySelectorAll('.filename-image-input').forEach( 
                    input => input.value = filename
                );

                container.parentElement.querySelectorAll('.image-input.name').forEach( imageNameInput => {
                    if( imageNameInput.value === '' )
                    {
                        imageNameInput.value = filename.substring(0, filename.lastIndexOf('.'));

                        if( imageNameInput.value === '' ){
                            imageNameInput.value = filename;
                        }
                    }
                    imageNameInput.select();
                    imageNameInput.focus();
                });

            })
        );

        container.querySelectorAll( '.remove-image' ).forEach(
            a =>  a.addEventListener( 'click', () => {
                if( !confirm('Remove image ?') ){
                    return false;
                }

                container.querySelectorAll( '.image-display .current-image-focus' ).forEach(
                    elmnt => elmnt.remove()
                );

                container.querySelectorAll( '.image-display' ).forEach(
                    elmnt => elmnt.style.display = 'none'
                );
                
                container.querySelectorAll( '.file-input' ).forEach(
                    elmnt => elmnt.style.display = 'block'
                );

                let filename = '';
                container.querySelectorAll( 
                    '.file-input .upload-image-input, .file-input .filename-image-input' 
                ).forEach( input => {
                    
                    if( input.classList.contains('filename-image-input') ){
                        filename = input.value;
                    }

                    if( input.value === '' ){
                        return;
                    }

                    input.value     = '';
                });

                let matchArray  = [
                    filename, 
                    filename.substring(0, filename.lastIndexOf('.'))
                ];

                container.querySelectorAll('.image-input.name').forEach( filenameInput => {
                    if( matchArray.includes(filenameInput.value) ){
                        filenameInput.value = '';
                    }
                    filenameInput.select();
                    filenameInput.focus();
                });            
    
            })
        );
    });
});
