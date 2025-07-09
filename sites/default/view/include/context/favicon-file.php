<?php /** @var WW\Context $this */ ?>

<link   rel="icon" 
        type="<?=mime_content_type( $this->ww->website->getFilePath(self::IMAGES_SUBFOLDER."/".$iconFile) )?>" 
        href="<?=$iconSrc?>" />
