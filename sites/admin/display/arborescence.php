<?php 
/**
 * @var WW\Module $this
 * @var array $tree  
 * @var ?array $breadcrumb
 * @var ?int $currentId 
 * @var ?string $currentSite
 * @var ?bool $draggble
 * @var ?string $clipboardUrl
 * @var ?string $createUrl
 * @var ?string $cauldronUrl
 * @var ?string $urlHash
 */

$breadcrumb     = $breadcrumb ?? [];
$currentId      = $currentId ?? 0;
$currentSite    = $currentSite ?? $this->ww->website->name;
$draggble       = $draggble ?? false;
$clipboardUrl   = $clipboardUrl ?? "null";
$createUrl      = $createUrl ?? "null";
$cauldronUrl    = $cauldronUrl ?? "null";
$urlHash        = $urlHash ?? "null";

$this->addJsFile('fontawesome.js');
$this->addCssFile('arborescence-menu.css');
$this->addJsFile('arborescence-menu.js');

$key = "arborescence_".md5(microtime().rand());
?>
<div id="<?=$key ?>" class="arborescence-menu-container module"></div>

<script type="text/javascript">
    if( arborescencesInputs === undefined ){
        var arborescencesInputs = {};
    }
    
    arborescencesInputs[ "<?=$key ?>" ] = {
        "treeData": <?=json_encode($tree)?>,
        "breadcrumb": <?=json_encode($breadcrumb)?>,
        "currentId": <?=$currentId?>,
        "currentSite": "<?=$currentSite ?>",
        "draggable": <?=$draggble? "true": "false"?>,
        "clipboardUrl": "<?=$clipboardUrl ?>",
        "createUrl": "<?=$createUrl ?>",
        "cauldronUrl": "<?=$cauldronUrl ?>",
        "urlHash": "<?=$urlHash ?>",
    };
</script>


