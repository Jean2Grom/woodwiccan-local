<?php /** @var WW\Attribute\DatetimeAttribute $this */ 

$value      =  $this->content();
$inputValue =  "UNDEFINED";

if( !empty($value) ){
    $inputValue = $value->format( 'd/m/Y H:i:s' );
}

include $this->ww->website->getFilePath( self::VIEW_DIR."/view/datetime.php");