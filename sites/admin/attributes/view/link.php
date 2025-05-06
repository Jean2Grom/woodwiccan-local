<?php /** @var WW\Attribute\LinkAttribute $this */ 

$maxLenght  = 255;

$indice = strpos( substr($this->values['text'] ?? '', $maxLenght), " " ) + $maxLenght;
$text   = substr($this->values['text'] ?? '', 0, $indice);
if( strlen($this->values['text'] ?? '') > strlen($text) ){
    $text .= " (...)";
}

$indice = strpos( substr($this->values['href'] ?? '', $maxLenght), " " ) + $maxLenght;
$href   = substr($this->values['href'] ?? '', 0, $indice);
if( strlen($this->values['href'] ?? '') > strlen($href) ){
    $href .= " (...)";
}

if( $this->values['external'] ){
    $externalDisplay = "Open in new window";
}
else {
    $externalDisplay = "Self window target";
}

include $this->ww->website->getFilePath( self::VIEW_DIR."/view/link.php" );
