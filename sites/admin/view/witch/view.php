<?php /** @var WW\Module $this */

$this->addCssFile('view.css');
$this->addCssFile('boxes.css');
$this->addJsFile('triggers.js');
$this->addJsFile('view.js');

$this->addContextArrayItems( 'tabs', [
    'tab-current'       => [
        'selected'  => true,
        'iconClass' => ($this->witch("target")->hasCraft() && $this->witch("target")->hasInvoke())? "fas fa-hat-wizard"
                            : ($this->witch("target")->hasCraft()? "fas fa-mortar-pestle"
                            : ($this->witch("target")->hasInvoke()? "fas fa-hand-sparkles"
                            : "fas fa-folder")),
        'text'      => "Witch",
    ],
    'tab-craft-part'    => [
        'iconClass' => $this->witch("target")->hasCraft()? "fas fa-mortar-pestle" :"far fa-plus-square",
        'text'      => "Craft",
    ],
    'tab-cauldron-part'    => [
        'iconClass' => $this->witch("target")->hasCauldron()? "fas fa-mortar-pestle": "far fa-plus-square",
        'text'      => "Cauldron",
    ],
]);
?>

<?php $this->include('alerts.php', ['alerts' => $this->ww->user->getAlerts()]); ?>

<div><?php $this->include('witch/menu-info.php', [ 'witch' => $this->witch("target") ]); ?></div>
<div><?php $this->include('witch/edit-menu-info.php', [ 'witch' => $this->witch("target") ]); ?></div>

<div class="tabs-target__item selected"  id="tab-current">
    <div class="box-container">
        <div><?php $this->include('witch/info.php', [ 
            'witch'     => $this->witch("target"), 
            'imgSrc'    => $this->image('favicon.png'),
        ]); ?></div>
        <div><?php $this->include('witch/edit-info.php', [ 
            'witch'         => $this->witch("target"), 
            'imgSrc'        => $this->image('favicon.png'),
            'websitesList'  => $websitesList, 
        ]); ?></div>
        <div><?php $this->include('witch/daughters.php', [ 
            'witch'         => $this->witch("target"), 
            'websitesList'  => $websitesList, 
        ]); ?></div>
        <div><?php $this->include('witch/create.php', [ 'witch' => $this->witch("target") ]); ?></div>
    </div>
</div>

<div class="tabs-target__item"  id="tab-craft-part">
    <div class="box-container">
        <div><?php $this->include('witch/craft.php', [ 
            'witch'             => $this->witch("target"), 
            'structuresList'    => $structuresList, 
            'deletion'          => count($craftWitches) == 1, 
        ]); ?></div>
        <div><?php $this->include('witch/craft-witches.php', [ 
            'witch'         => $this->witch("target"), 
            'craftWitches'  => $craftWitches, 
        ]); ?></div>
    </div>
</div>

<div class="tabs-target__item"  id="tab-cauldron-part">
    <div class="box-container">
        <div><?php $this->include('cauldron/display.php', [ 'witch'=> $this->witch("target") ]); ?></div>
        <div><?php $this->include('cauldron/witches.php', [ 'witch'=> $this->witch("target") ]); ?></div>
    </div>
</div>
