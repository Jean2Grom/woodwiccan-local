document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll('#btnMenu').forEach( 
        navTogglerDom => navTogglerDom.addEventListener("click", 
            () => document.querySelectorAll('nav').forEach( 
                navDom => {
                    if( navDom.style.display !== 'block' ){
                        navDom.style.display = 'block';
                    }
                    else {
                        navDom.style.display = 'none';
                    }
                }
            )
        )
    );
});
