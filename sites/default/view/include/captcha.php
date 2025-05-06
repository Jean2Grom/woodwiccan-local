<?php /** @var WW\Module $this */ 

/**
 * - To add a captcha check into your form, you have to add this code :
 * 
 * include $this->getIncludeViewFile('captcha.php');
 * 
 * - To check captcha validation in PHP file, use this test : 
 * 
 * $this->ww->user->session->read('captcha') === $this->ww->request->param('captcha')
 * 
 * - To display "Wrong Catpcha" or custom label :
 * 
 * $this->ww->user->session->write('captcha-error', true|"CUSTOM_LABEL");
 */

use WW\Website;

$this->addCssFile('captcha.css');
$this->addJsFile('captcha.js');

$id = "ww-captcha-container-".md5(rand());   
if( $this->ww->website->site === "admin" ){
    $wwCaptchaUrl = $this->ww->website->getUrl('captcha');
}
else {
    $wwCaptchaUrl   =   (new Website( $this->ww, "admin" ))->getUrl('captcha');
    $wwCaptchaUrl   .=  "?site=".$this->ww->website->name;
}
?>
<div class="ww-captcha-container" id="<?=$id?>">
    <div class="ww-captcha">
        <i class="fa fa-circle-notch fa-spin"></i>
    </div>
</div>
<script>
    var wwCaptchaUrl        = '<?=$wwCaptchaUrl ?>';
    var wwCaptchaId         = '<?=$id ?>';
    var wwCaptchaSiteTarget = '<?=$this->ww->website->name ?>';
</script>
