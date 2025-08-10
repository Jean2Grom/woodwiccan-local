<?php /** @var WW\Context $this */ ?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title><?=$this->title ?? "Wood Wiccan Example" ?></title>

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

    <body class="<?=$this->bodyClass ?? "" ?>">
        <?=$this->witch()->result() ?>

        <?php foreach( $this->getJsFiles() as $jsFile ): ?>
            <script src="<?=$jsFile?>"></script>
        <?php endforeach; ?>
    </body>
</html>