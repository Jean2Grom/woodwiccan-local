<?php /** @var WW\Attribute $this */ 

if( $this->content() ): ?>
    <a  <?php if( $this->content('external') ): ?>target="_blank"<?php endif; ?>
        href="<?=$this->content('href')?>">
        <?=$this->content('text')?>
    </a>
<?php endif; 