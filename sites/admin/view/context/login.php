<!DOCTYPE html>
<?php /** @var WW\Context $this */

$this->addCssFile('base.css');
$this->addCssFile('basic.css');
$this->addCssFile('header-footer.css');
?>
<html lang="fr">
    <head>
        <?php include $this->getIncludeViewFile('head.php'); ?>
    </head>
    
    <body>
        <div class="container">
            <header><?php include $this->getIncludeViewFile('header.php'); ?></header>
            <main><?=$this->witch()->result() ?></main>
            <footer><?php include $this->getIncludeViewFile('footer.php'); ?></footer>
        </div>
        
        <?php foreach( $this->getJsFiles() as $jsFile ): ?>
            <script src="<?=$jsFile?>"></script>
        <?php endforeach; ?>        
    </body>
</html>