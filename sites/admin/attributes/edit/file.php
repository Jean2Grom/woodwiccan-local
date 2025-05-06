<?php /** @var WW\Attribute\FileAttribute $this */ 

$this->ww->website->context->addJsFile('attribute/file-edit.js');
$this->ww->website->context->addCssFile('attribute/file-edit.css');

$srcFile = $this->getFile(); 

include $this->ww->website->getFilePath( self::VIEW_DIR."/edit/file.php");
