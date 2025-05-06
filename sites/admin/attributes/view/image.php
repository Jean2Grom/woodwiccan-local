<?php /** @var WW\Attribute\ImageAttribute $this */ 

$this->ww->website->context->addCssFile('attribute/image-view.css');

$srcFile = $this->getFile(); 

include $this->ww->website->getFilePath( self::VIEW_DIR."/view/image.php" );
