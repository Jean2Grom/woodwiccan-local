<?php /** @var WW\Module $this */

$this->addCssFile('view.css');
$this->addCssFile('boxes.css');
$this->addJsFile('triggers.js');
$this->addJsFile('view.js');

$this->addContextVar('editMenuData', true);
$this->addContextArrayItems( 'tabs', [
    'tab-current'       => [
        'selected'  => true,
        'iconClass' => ($this->witch("target")->hasCauldron() && $this->witch("target")->hasInvoke())? "fas fa-hat-wizard"
                            : ($this->witch("target")->hasCauldron()? "fas fa-mortar-pestle"
                            : ($this->witch("target")->hasInvoke()? "fas fa-hand-sparkles"
                            : "fas fa-folder")),
        'text'      => "Witch : ".$this->witch("target")->name,
    ],
]);
?>

<div class="tabs-target__item selected"  id="tab-current">
    <div class="box-container">
        <div><?php $this->include('witch/info.php', [ 
            'witch'     => $this->witch("target"), 
        ]); ?></div>
        <div><?php $this->include('witch/edit-info.php', [ 
            'witch'         => $this->witch("target"), 
            'websitesList'  => $websitesList, 
        ]); ?></div>
        <div><?php $this->include('witch/daughters.php', [ 
            'witch'         => $this->witch("target"), 
            'websitesList'  => $websitesList, 
        ]); ?></div>
        <div><?php $this->include('cauldron/display.php', [ 'witch'=> $this->witch("target") ]); ?></div>
        <div><?php $this->include('cauldron/witches.php', [ 'witch'=> $this->witch("target") ]); ?></div>
    </div>
</div>

