<?php /** @var WW\Context $this */ 

// $this->ww->debug( $this );
// $this->ww->debug( $this->witch() );
$this->ww->debug( $this->witch('home')->cauldron() );
// $this->ww->debug->die("jean");

$this->addCssFile('styles.css');
$this->addCssFile('responsive.css');
$this->addJsFile('witch.js');
$this->addJsFile('case.jq.js');
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <?php include $this->getIncludeViewFile('head.php'); ?>
    </head>
    
    <body>
        <div id="page">
            <div class="socialbar">
                <?php if( $contactEmail = $this->witch('home')?->cauldron()?->content('contact-email') ): ?>
                    <div class="content_socialbar">
                        <img src="<?=$this->getImageFile('lettre_mail.jpg')?>" />
                        <a  <?=$contactEmail->content('external')->value()? 'target="_blank"': ''?>
                            href="<?=$contactEmail->content('href')->value() ?? ""?>">
                            <?=$contactEmail->content('text')->value()?>
                        </a>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="menu">
                <a href="<?=$this->website->getUrl()?>">
                    <img    src="<?=$contextData['logo']['file'] ?? ""?>" 
                            alt="<?=$contextData['logo']['title']?? ""?>"
                            title="<?=$contextData['logo']['title']?? ""?>"/>
                </a>
                
                <div id="download" >
                    <a href="<?=$contextData['download-highlight']['file'] ?? ""?>">
                        <p><?=$contextData['download-highlight']['text'] ?? ""?></p>
                    </a>
                </div>
                
                <div class="content_menu">  
                    <ul>
                        <?php foreach( $menu ?? [] as $menuItem ): ?>
                            <li>
                                <a  <?=( strcmp($menuItem['url'], $baseUri.$localisation->url) == 0 )?
                                        'id="en_cours"':
                                        'id="btn_menu"'?>
                                    href="<?=$menuItem['url'] ?? ""?>">
                                    <?=strtoupper($menuItem['name'])?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul> 
                </div>
            </div>
            
            <div id="conteneur">
                <?php if( isset($backgroundImage) ): ?>
                    <div    id="contenu_index" 
                            style="background:url(<?=$backgroundImage?>) no-repeat center center;">
                        <h1>
                            <?=$headline?>
                        </h1>
                        <div id="baseline">
                            <?=$headlineBody?>
                        </div>

                        <div id="boite_bouton">
                            <div id="bouton">
                                <a href="<?=$contextData['download-highlight']['file'] ?? ""?>">
                                    <p><?=$contextData['download-highlight']['text'] ?? ""?></p>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                
                <?=$this->witch()->result() ?>
                
            </div>
            
            <div id="footer">
                <div id="footer_content">
                    <div id="signature">
                        <?=$contextData['footer-left'] ?? ""?>
                    </div>
                    <div id="copyright">
                        <?=$contextData['footer-right'] ?? ""?>
                    </div>
                </div>
            </div>
        </div>
        
        <?php foreach( $this->getJsFiles() as $jsFile ): ?>
            <script src="<?=$jsFile?>"></script>
        <?php endforeach; ?>
    </body>
</html>
