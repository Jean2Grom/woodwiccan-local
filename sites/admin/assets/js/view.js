document.addEventListener("DOMContentLoaded", () => 
{
    // Menu info edition behaviour
    document.querySelectorAll('.edit__witch-menu-info').forEach(
        hideDom => hideDom.style.display = 'none'
    );

    document.querySelectorAll('button.view-edit-menu-info-toggle').forEach(
        toggler => toggler.addEventListener("click", 
        () => {
            document.querySelectorAll('.view__witch-menu-info, .edit__witch-menu-info').forEach(
                toggledDom => {
                    if( toggledDom.style.display !== 'block' ){
                        toggledDom.style.display = 'block';
                    }
                    else {
                        toggledDom.style.display = 'none';
                    }
                }
            );

            document.querySelectorAll('.edit__witch-info, .create__witch').forEach(
                hideDom => hideDom.style.display = 'none'
            );

            document.querySelectorAll('.view__witch-info, .view__daughters').forEach(
                showDom => showDom.style.display = 'block'
            );
        })
    );

    document.querySelectorAll('button.edit-menu-info-reinit').forEach(
        reinitDom => reinitDom.addEventListener("click", function () {
            document.querySelectorAll('.edit__witch-menu-info input, .edit__witch-menu-info textarea').forEach(
                input => {
                    if( input.dataset.init !== undefined ){
                        input.value = input.dataset.init;
                    }
                }
            )
        })
    );


    // Add new witch behaviour
    document.querySelectorAll('.create__witch').forEach(
        hideDom => hideDom.style.display = 'none'
    );

    document.querySelectorAll('button.view-daughters__create-witch__toggle').forEach(
        toggler => toggler.addEventListener("click", 
        () => {
            document.querySelectorAll('.create__witch, .view__daughters').forEach(
                toggledDom => {
                    if( toggledDom.style.display !== 'block' ){
                        toggledDom.style.display = 'block';
                    }
                    else {
                        toggledDom.style.display = 'none';
                    }
                }
            );

            document.querySelectorAll('.edit__witch-info, .edit__witch-menu-info').forEach(
                hideDom => hideDom.style.display = 'none'
            );

            document.querySelectorAll('.view__witch-info, .view__witch-menu-info').forEach(
                showDom => showDom.style.display = 'block'
            );

        })
    );


    // Witch info edition behaviour
    document.querySelectorAll('.edit__witch-info').forEach(
        hideDom => hideDom.style.display = 'none'
    );

    document.querySelectorAll('button.view-edit-info-toggle').forEach(
        toggler => toggler.addEventListener("click", 
        () => {
            document.querySelectorAll('.view__witch-info, .edit__witch-info').forEach(
                toggledDom => {
                    if( toggledDom.style.display !== 'block' ){
                        toggledDom.style.display = 'block';
                    }
                    else {
                        toggledDom.style.display = 'none';
                    }
                }
            );

            document.querySelectorAll('.edit__witch-menu-info, .create__witch').forEach(
                hideDom => hideDom.style.display = 'none'
            );

            document.querySelectorAll('.view__witch-menu-info, .view__daughters').forEach(
                showDom => showDom.style.display = 'block'
            );

        })
    );

    witchInfoChange();
    autoUrlChange();
    
    document.querySelectorAll('#witch-site, .witch-invoke').forEach(
        selectDom => selectDom.addEventListener('change', witchInfoChange )
    );

    document.querySelector('#witch-auto-url').addEventListener('change', autoUrlChange);

    function witchInfoChange()
    {
        document.querySelectorAll('.witch-info__part, #site-selected').forEach(
            hideDom => hideDom.style.display = 'none'
        );

        let site = document.querySelector('#witch-site').value;
        document.querySelectorAll('.witch-info__part-' + site).forEach(
            showDom => showDom.style.display = 'block'
        );

        if( site !== '' && document.querySelector('#witch-invoke-' + site).value !== '' ){
            document.querySelector('#site-selected').style.display = 'block';
        }
    }    
    
    function autoUrlChange()
    {
        if( document.querySelector('#witch-auto-url').checked ){
            document.querySelectorAll('.auto-url-disabled').forEach(
                hideDom => hideDom.style.display = 'none'
            );
        }
        else {
            document.querySelectorAll('.auto-url-disabled').forEach(
                showDom => showDom.style.display = 'block'
            );
        }
    }

    document.querySelectorAll('button.edit-info-reinit').forEach(
        button => button.addEventListener('click', 
            () => {
                document.querySelectorAll('.edit__witch-info input, .edit__witch-info select').forEach(
                    input => {
                        if( input.dataset.init !== undefined ){
                            input.value = input.dataset.init;
                        }
                    }
                );

                if( document.querySelector('#witch-url').value === '' )
                {
                    document.querySelector('#witch-full-url').checked = false;
                    document.querySelector('#witch-auto-url').checked = true;
                }
                else 
                {
                    document.querySelector('#witch-full-url').checked = true;
                    document.querySelector('#witch-auto-url').checked = false;
                }

                witchInfoChange();
                autoUrlChange();
            }
        )
    );
    

    // Cauldron part
    // enable/disable create new cauldron's button, based on recipe selection
    let cauldronRecipeSelector = document.getElementById('witch-cauldron-recipe');
    if( cauldronRecipeSelector ){
        cauldronRecipeSelector.addEventListener('change', e => {
            if( e.target.value === '' ){
                document.getElementById('witch-create-cauldron').classList.add("disabled");
            }
            else {
                document.getElementById('witch-create-cauldron').classList.remove('disabled')
            }
        });
    }

    let cauldronFetchButton = document.getElementById('witch-get-existing-cauldron');
    if( cauldronFetchButton ){
        cauldronFetchButton.addEventListener('click', () => {
            chooseWitch({ cauldron: true }, "Choose importing cauldron's witch").then( witchId => 
            { 
                if( witchId === false ){
                    return;
                }

                document.getElementById('imported-cauldron-witch').value = witchId;
                document.getElementById('import-cauldron-action').click();
            });
        });    
    }
    /*
    let cauldronPositionButton = document.getElementById('choose-cauldron-position');
    if( cauldronPositionButton ){
        cauldronPositionButton.addEventListener('click', () => {
            chooseWitch({ cauldron: true }, "Choose new cauldron position").then( witchId => 
            { 
                if( witchId === false ){
                    return;
                }

                document.getElementById('imported-cauldron-witch').value = witchId;
                document.getElementById('import-cauldron-action').click();
            });
        });    
    }
    */
    // add existing cauldron to existing witch
    let addCauldronWitchButton = document.getElementById('add-cauldron-witch');
    if( addCauldronWitchButton ){
        addCauldronWitchButton.addEventListener('click', () => {
            chooseWitch({ cauldron: false }).then( (witchId) => { 
                if( witchId === false ){
                    return;
                }
                
                document.getElementById('cauldron-new-witch-id').value = witchId;
                document.getElementById('cauldron-add-witch-action').click();
            });
        });
    }
    // create new witch to add existing cauldron
    let addCauldronNewWitchButton = document.getElementById('add-cauldron-new-witch');
    if( addCauldronNewWitchButton ){
        addCauldronNewWitchButton.addEventListener('click', () => {
            chooseWitch().then( (witchId) => { 
                if( witchId === false ){
                    return;
                }
                
                document.getElementById('cauldron-new-witch-id').value = witchId;
                document.getElementById('cauldron-add-new-witch-action').click();
            });
        });

    }

    document.querySelectorAll('.remove-cauldron-witch').forEach( 
        a => a.addEventListener( 'click', () => {
            document.getElementById('cauldron-witch-id').value = a.dataset.witch;
            document.getElementById('remove-cauldron-witch-action').click();
        } )
    );

    document.querySelectorAll('.delete-cauldron-witch').forEach( 
        a => a.addEventListener( 'click', () => {
            document.getElementById('cauldron-witch-id').value = a.dataset.witch;
            document.getElementById('delete-cauldron-witch-action').click();
        } )
    );

    
    // Daughters cut/copy
    document.querySelectorAll('.cut-descendants').forEach(
        cutDom => cutDom.addEventListener('click', 
            () => {
                document.querySelector('#origin-witch').value = cutDom.dataset.id;

                chooseWitch({}, "Choose moving destination witch").then( (witchId) => { 
                    if( witchId === false ){
                        return;
                    }
                    
                    document.querySelector('#destination-witch').value = witchId;
                    document.querySelector('#move-witch-action').click();
                });
            }
        )
    );
    
    document.querySelectorAll('.copy-descendants').forEach(
        copyDom => copyDom.addEventListener('click',
            () => {
                document.querySelector('#origin-witch').value = copyDom.dataset.id;
                
                chooseWitch({}, "Choose copy destination witch").then( (witchId) => { 
                    if( witchId === false ){
                        return;
                    }
                    
                    document.querySelector('#destination-witch').value = witchId;
                    document.querySelector('#copy-witch-action').click();
                });
            }
        )
    );    
});
