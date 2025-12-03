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
            <?php $this->include('context/header.php'); ?>
            <main><?=$this->witch()->result() ?></main>
            <?php $this->include('context/footer.php'); ?>
        </div>
        
        <?php foreach( $this->getJsFiles() as $jsFile ): ?>
            <script src="<?=$jsFile?>"></script>
        <?php endforeach; ?>        
    </body>
</html>