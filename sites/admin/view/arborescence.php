<?php /** @var WW\Module $this */

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
        "currentId": <?=$currentId ?? 0?>,
        "currentSite": "<?=$this->ww->website->name?>",
        "breadcrumb": <?=json_encode($breadcrumb)?>,
        "draggable": <?=$draggble ?? null ? "true": "false"?>,
        "clipboardUrl": <?=($clipboardUrl ?? null)? '"'.$clipboardUrl.'"': "null" ?>,
        "createUrl": <?=($createUrl ?? null)? '"'.$createUrl.'"': "null" ?>,
        "urlHash": <?=($urlHash ?? null)? '"'.$urlHash.'"': "null" ?>,
    };
</script>


