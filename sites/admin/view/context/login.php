<?php /** @var WW\Context $this */

$this->addCssFile('base.css');
$this->addCssFile('basic.css');
$this->addCssFile('header-footer.css');
?>
<!DOCTYPE html>
<html lang="fr">
    <head><?php $this->include('context/head.php'); ?></head>
    
    <body>
        <div class="container">
            <header><?php $this->include('context/header.php'); ?></header>
            <main><?=$this->witch()->result() ?></main>
            <footer><?php $this->include('context/footer.php'); ?></footer>
        </div>
        
        <?php foreach( $this->getJsFiles() as $jsFile ): ?>
            <script src="<?=$jsFile?>"></script>
        <?php endforeach; ?>        
    </body>
</html>