<?php /** @var WW\Attribute\FileAttribute $this */ 

$srcFile = $this->getFile(); 

include $this->ww->website->getFilePath( self::VIEW_DIR."/view/file.php" );
