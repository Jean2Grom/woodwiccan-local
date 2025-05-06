<?php /** @var WW\Module $this */

use WW\Session;
use WW\Website;

const WW_CAPTCHA_ITERATIONS = 3;

$site           = $this->ww->request->param('site');
if( $site )
{
    $session    = new Session($this->ww, $site);
    $website    = new Website($this->ww, $site);
}
else 
{
    $session    = $this->ww->user->session;
    $website    = $this->ww->user->website;
}

$iterations = $website->modules['ajax/captcha']['iterations'] ?? WW_CAPTCHA_ITERATIONS;

$id             = "ww-captcha-".md5(rand());   
$match          = rand(0, $iterations - 1);
$captchaImages  = [];
$hintImage      = null;
for( $i=0; $i < $iterations; $i++ )
{
    $randomAlpha    = md5(rand());
    $captchaCode    = substr($randomAlpha, 0, 6);
    
    $red    = rand(0, 255);
    $green  = rand(0, 255);
    $blue   = rand(0, 255);
    
    $image      = imagecreatetruecolor(150, 35);
    $bg         = imagecolorallocate($image, (255 - $red), (255 - $green), (255 - $blue));
    
    imagefill($image, 0, 0, $bg);
    
    $color = imagecolorallocate($image, $red, $green, $blue);
    
    imagestring( $image, 5, 50, 10, $captchaCode, $color );
    imagegammacorrect($image, 1.0, 2.0);
    
    ob_start (); 
    imagepng( $image );
    $imageData = ob_get_contents (); 
    ob_end_clean (); 
    
    $captchaImages[ $i ] = $imageData;
    
    if( $i == $match )
    {
        $session->write('captcha', $captchaCode);
        
        if( $iterations > 1 )
        {
            $string = "Code is the ".($match + 1);

            $image = imagecreatefrompng( substr($this->getImageFile('captcha-hint-layout.png'), 1) );
            $color = imagecolorallocate($image, 0, 0, 0);

            $px     = (int) (imagesx($image) - 8 * strlen($string)) / 2;
            imagestring($image, 4, $px, 13, $string, $color);

            ob_start (); 
            imagepng($image);
            $hintImage = ob_get_contents (); 
            ob_end_clean (); 
        }
    }
}

$captchaError = $session->read('captcha-error');

if( $captchaError )
{
    $session->delete('captcha-error');
    
    if( !is_string($captchaError) || strlen($captchaError) == 0 ){
        $captchaError = "Wrong Catpcha";
    }
}

$this->setContext('empty');
$this->view();
