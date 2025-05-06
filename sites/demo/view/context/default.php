<?php

$module->addCssFile('styles.css');
$module->addJsFile('witch.js');
$module->addJsFile('jquery.min.js');
$module->addJsFile('case.jq.js');
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <? include $module->getViewFile('contexts/includes/head.php'); ?>
    </head>
    
    <body>
        <div id="page">
            <div class="socialbar">
                <div class="content_socialbar">
                    <img src="<?=$module->getImageFile('lettre_mail.jpg')?>" />
                    <a  <? if($contextData['contact-email']['external']) { ?>target="_blank"<? } ?>
                        href="<?=$contextData['contact-email']['href']?>">
                        <?=$contextData['contact-email']['text']?>
                    </a>
                </div>        
            </div>
            
            <div class="menu">
                <a href="<?=$baseUri?>">
                    <img    src="<?=$contextData['logo']['file']?>" 
                            alt="<?=$contextData['logo']['title']?>"
                            title="<?=$contextData['logo']['title']?>"/>
                </a>
                
                <div id="download" >
                    <a href="<?=$contextData['download-highlight']['file']?>">
                        <p><?=$contextData['download-highlight']['text']?></p>
                    </a>
                </div>
                
                <div class="content_menu">  
                    <ul>
                        <? foreach( $menu as $menuItem ) { ?>
                            <li>
                                <a  <? if( strcmp($menuItem['url'], $baseUri.$localisation->url) == 0 ){ ?>
                                        id="en_cours"
                                    <? } else { ?>
                                        id="btn_menu"
                                    <? } ?>
                                    href="<?=$menuItem['url']?>">
                                    <?=  strtoupper($menuItem['name'])?>
                                </a>
                            </li>
                        <? } ?>
                    </ul> 
                </div>
            </div>
            
            <div id="conteneur">
                <? if( isset($backgroundImage) ) { ?>
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
                                <a href="<?=$contextData['download-highlight']['file']?>">
                                    <p><?=$contextData['download-highlight']['text']?></p>
                                </a>
                            </div>
                        </div>
                    </div>
                <? } ?>
                
                <?=$module_result?>
                
            </div>
            
            <div id="footer">
                <div id="footer_content">
                    <div id="signature">
                        <?=$contextData['footer-left']?>
                    </div>
                    <div id="copyright">
                        <?=$contextData['footer-right']?>
                    </div>
                </div>
            </div>
        </div>
        
        <? foreach( $module->getJsFiles() as $jsFile ) { ?>
            <script src="<?=$jsFile?>"></script>
        <? } ?>
    </body>
</html>
