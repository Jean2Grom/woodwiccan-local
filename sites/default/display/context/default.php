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
        <?=$this->jsLibs() ?>
        <?=$this->css() ?>
    </head>
    
    <body>
        <div class="container">
            <main><?=$this->witch()->result() ?></main>
        </div>
        
        <?=$this->js() ?>
    </body>
</html>