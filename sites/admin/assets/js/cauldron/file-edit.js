document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll('.file-edit-container').forEach( container => {

        container.querySelectorAll( 
            '.file-input .upload-file-input, .file-input .move-file-input' 
        ).forEach(
            input =>  input.addEventListener( 'change', () => {
                let filename = input.value.split(/(\\|\/)/g).pop();
                
                container.parentElement.querySelectorAll('.file-input.filename').forEach( filenameInput => {
                    if( filenameInput.value === '' )
                    {
                        filenameInput.value = filename.substring(0, filename.lastIndexOf('.'));

                        if( filenameInput.value === '' ){
                            filenameInput.value = filename;
                        }
                    }
                    filenameInput.select();
                    filenameInput.focus();
                });
            })
        );

        container.addEventListener( 'fileRemovedByUser', fileRemovedByUserEvent => {            
            let filename    = fileRemovedByUserEvent.detail.filename;
            let matchArray  = [
                filename, 
                filename.substring(0, filename.lastIndexOf('.'))
            ];
            container.parentElement.querySelectorAll('.file-input.filename').forEach( filenameInput => {
                if( matchArray.includes(filenameInput.value) ){
                    filenameInput.value = '';
                }                
                filenameInput.select();
                filenameInput.focus();
            });            
        });
    });
});
