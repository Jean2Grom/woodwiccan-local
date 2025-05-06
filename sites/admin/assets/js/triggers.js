document.addEventListener("DOMContentLoaded", () => {

    document.querySelectorAll(".trigger-href").forEach( 
        trigger => trigger.addEventListener('click', 
            e => {
                e.preventDefault();
                if( !trigger.classList.contains("disabled") ){
                    window.location.href = trigger.dataset.href;
                }
                return false;
            }
        )    
    );

    document.querySelectorAll(".trigger-action").forEach( 
        trigger => trigger.addEventListener('click', 
            e => {
                e.preventDefault();

                if( trigger.classList.contains("disabled")
                    || trigger.dataset.action === undefined 
                    ||  trigger.dataset.target === undefined 
                    || (trigger.dataset.confirm !== undefined && !confirm( trigger.dataset.confirm ))
                ){
                    return false;
                }

                let action = document.createElement('input');
                action.setAttribute('type', "hidden");
                action.setAttribute('name', "action");
                action.value = trigger.dataset.action;

                let form = document.querySelector('#' + trigger.dataset.target);
                form.append(action);
                form.submit();

                return false;
            }
        )
    );
});