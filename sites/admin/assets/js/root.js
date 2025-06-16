document.addEventListener("DOMContentLoaded", () => {

    document.querySelector('body').addEventListener('click', 
        (e) => {
            if( e.target.nodeName === 'BUTTON' ){
                return;
            }

            if( e.target.classList.contains('content__data') )
            {
                let data = document.querySelector('.content__data').innerHTML;

                document.querySelector('#witch__data').value = data;

                document.querySelector('.content__data').style.display = 'none';
                document.querySelector('#data-edit').style.display = 'block';
            }
            else if( e.target.closest('#data-edit') === null )
            {
                document.querySelector('#data-edit').style.display = 'none';
                document.querySelector('.content__data').style.display = 'block';
            }
        }
    );
});