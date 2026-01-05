<?php 
/**
 * @var WW\Module $this
 * @var array $tree  
 * @var ?array $breadcrumb
 */

$this->addCssFile('choose-witch.css');
?>
<div id="cauldrons">
    <h3><span>Cauldrons Navigation</span></h3>
    <?php $this->include( '../arborescence.php', [
        'tree'          => $tree,
        'breadcrumb'    => $breadcrumb
    ] ); ?>
</div>

