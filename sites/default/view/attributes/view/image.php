<?php /** @var WW\Attribute $this */ 

if( $this->content() ): ?>
    <img title="<?=$this->content('title')?>" src="<?=$this->content('file')?>" />
<?php endif; 