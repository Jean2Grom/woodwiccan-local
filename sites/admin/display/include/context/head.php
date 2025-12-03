<?php /** @var WW\Context $this */ ?>

<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

<title><?=$title ?? "WoodWiccan Admin" ?></title>

<?=$this->favicon() ?>

<?php foreach( $this->getJsLibFiles() as $jsLibFile ): ?>
    <script src="<?=$jsLibFile?>"></script>
<?php endforeach; ?>

<?php foreach( $this->getCssFiles() as $cssFile ): ?>
    <link   rel="stylesheet" 
            type="text/css" 
            href="<?=$cssFile?>" />
<?php endforeach; ?>

