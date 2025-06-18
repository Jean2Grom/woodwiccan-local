<?php /** @var WW\Context $this */ ?>

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta   
    name="viewport" 
    content="width=device-width, initial-scale=1, minimum-scale=0.25, maximum-scale=5.0, target-densitydpi=device-dpi" />

<title>
    <?=$this->witch()->{'meta-title'} ?? $this->witch('home')->{'meta-title'} ?? "WoodWiccan" ?>
</title>

<?=$this->favicon() ?>

<meta name="description" content="<?=$contextData['meta-description'] ?? ""?>">
<meta name="keywords" content="<?=$contextData['meta-keywords'] ?? ""?>">

<?php foreach( $this->getCssFiles() as $cssFile ): ?>
    <link   rel="stylesheet" 
            type="text/css" 
            href="<?=$cssFile?>" />
<?php endforeach; ?>
