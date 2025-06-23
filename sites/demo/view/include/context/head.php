<?php /** @var WW\Context $this */ ?>

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta   
    name="viewport" 
    content="width=device-width, initial-scale=1, minimum-scale=0.25, maximum-scale=5.0, target-densitydpi=device-dpi" />

<title>
    <?=$this->title ?? $this->witch()->{'meta-title'} ?? $this->witch('home')->{'meta-title'} ?? "WoodWiccan" ?>
</title>

<?=$this->favicon() ?>

<?php if( $description = $this->witch('home')?->cauldron()?->content('meta-description') ): ?>
    <meta name="description" content="<?=$description?>" />
<?php endif; ?>

<?php if( $keywords = $this->witch('home')?->cauldron()?->content('meta-keywords') ): ?>
    <meta name="description" content="<?=$keywords?>">
<?php endif; ?>

<?php foreach( $this->getCssFiles() as $cssFile ): ?>
    <link   rel="stylesheet" 
            type="text/css" 
            href="<?=$cssFile?>" />
<?php endforeach; ?>
