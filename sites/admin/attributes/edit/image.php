<?php /** @var WW\Attribute\ImageAttribute $this */ 

$this->ww->website->context->addJsFile('attribute/image-edit.js');
$this->ww->website->context->addCssFile('attribute/image-edit.css');

$srcFile = $this->getFile(); 

include $this->ww->website->getFilePath( self::VIEW_DIR."/edit/image.php");
