<?php 
/**
 * @var WW\Module $this
 * @var array $tree  
 * @var ?array $breadcrumb
 * @var ?int $currentId 
 */

$this->addCssFile('choose-witch.css');
$this->addJsFile('choose-witch.js');
?>
<div id="choose-witch">
    <h3>
        <span>Choose Witch</span>
        <a class="close"><i class="fa fa-times"></i></a>
    </h3>
    
    <?php $this->include( '../arborescence.php', [
        'currentId'     => $currentId,
        'tree'          => $tree,
        'breadcrumb'    => $breadcrumb
    ] ); ?>
</div>

