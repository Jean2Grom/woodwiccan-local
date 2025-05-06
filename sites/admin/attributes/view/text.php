<?php /** @var WW\Attribute\TextAttribute $this */ 

$maxLenght  = 255;
$value      = "";

if( $this->content() )
{
    $indice     = strpos( substr($this->content(), $maxLenght), " " ) + $maxLenght;
    $value      = substr($this->content(), 0, $indice);
    
    if( strlen($this->content()) > strlen($value) ){
        $value .= " (...)";
    }
}

include $this->ww->website->getFilePath( self::VIEW_DIR."/view/text.php");
