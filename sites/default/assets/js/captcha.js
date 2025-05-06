function  generateWcCaptcha()
{
    fetch( wwCaptchaUrl )
    .then( (response) => {
        if( !response.ok ){
            throw new Error(`HTTP error, status = ${response.status}`);
        }

        return response.text();
    })
    .then( (WcCaptchaHTML) => {
        document.querySelector("#"+wwCaptchaId).innerHTML = WcCaptchaHTML;

        document.querySelector("#"+wwCaptchaId+' .ww-captcha-refresh')
            .addEventListener("click", (e) => {
                e.preventDefault();
                generateWcCaptcha();
                return false;
            });        
    })
    .catch((error) => {
        document.querySelector("#"+wwCaptchaId).innerHTML = "<p>Captcha loading failure...</p>";
    });
}

generateWcCaptcha();
