<?php /** @var WW\Context $this */ 

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
                <?php if( $logo = $this->witch('home')?->cauldron()?->content('logo') ): ?>
                    <a href="<?=$this->website->getUrl()?>">
                        <img    src="<?=$logo->file->value() ?? ""?>" 
                                alt="<?=$logo->content('caption')->value() ?? ""?>"
                                title="<?=$logo->content('name')->value() ?? ""?>"/>
                    </a>
                <?php endif; ?>
                
                <?php if( $action = $this->witch('home')?->cauldron()?->content('call-to-action') ): ?>
                    <div id="download" >
                        <a  <?=$action->external? 'target="_blank"': ''?>
                            href="<?=$action->href?>">
                            <p><?=$action->text ?? ""?></p>
                        </a>
                    </div>
                <?php endif; ?>
                
                <div class="content_menu">  
                    <ul>
                        <?php foreach( $this->witch('home')->daughters() as $daughter ): ?>
                            <li>
                                <a  <?=($this->witch() === $daughter)?
                                        'id="en_cours"':
                                        'id="btn_menu"'?>
                                    href="<?=$daughter->url() ?>">
                                    <?=strtoupper($daughter->name)?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul> 
                </div>
            </div>
            
            <div id="conteneur">
                <?php if( $background = $this->witch('home')?->cauldron()?->content('background') ): ?>
                    <div    id="contenu_index" style="<?=$backgroundCss?>">
                        <h1><?=$headline?></h1>

                        <?php if( $body ): ?>
                            <div id="baseline"><?=$body?></div>
                        <?php endif; ?>

                        <div id="boite_bouton">
                            <?php if( $action ): ?>
                                <div id="bouton">
                                    <a  <?=$action->external? 'target="_blank"': ''?>
                                        href="<?=$action->href?>">
                                        <p><?=$action->text ?? ""?></p>
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>
                
                <?=$this->witch()->result() ?>
                
            </div>
            
            <div id="footer">
                <div id="footer_content">
                    <div id="signature">powered by WoodWiccan</div>
                    <div id="copyright">©2025</div>
                </div>
            </div>
        </div>
        
        <?php foreach( $this->getJsFiles() as $jsFile ): ?>
            <script src="<?=$jsFile?>"></script>
        <?php endforeach; ?>
    </body>
</html>
