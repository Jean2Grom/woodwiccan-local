<!DOCTYPE html>
<?php /** @var WW\Context $this */ 

$this->addCssFile('base.css');
$this->addCssFile('basic.css');
?>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>WoodWiccan</title>
        
        <?=$this->favicon() ?>
        
        <?php foreach( $this->getJsLibFiles() as $jsLibFile ): ?>
            <script src="<?=$jsLibFile?>"></script>
        <?php endforeach; ?>
        
        <?php foreach( $this->getCssFiles() as $cssFile ): ?>
            <link   rel="stylesheet" 
                    type="text/css" 
                    href="<?=$cssFile?>" />
        <?php endforeach; ?>
    </head>
    
    <body>
        <div class="container">
            <main><?=$this->witch()->result() ?></main>
        </div>
        
        <?php foreach( $this->getJsFiles() as $jsFile ): ?>
            <script src="<?=$jsFile?>"></script>
        <?php endforeach; ?>
    </body>
</html>