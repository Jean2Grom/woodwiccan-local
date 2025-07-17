<!DOCTYPE html>
<?php /** @var WW\Context $this */

use WW\Website;

$this->addCssFile('base.css');
$this->addCssFile('header-footer.css');
$this->addCssFile('context-standard.css');
$this->addJsFile('fontawesome.js');
$this->addJsFile('context-standard.js');
?>

<html lang="fr-FR" dir="ltr">
    <head><?php $this->include('context/head.php'); ?></head>
    <body>
        <div class="container">
            <?php $this->include('context/menu.php', [ 'menu' => $this->witch('menu')?->daughters() ]); ?>
            <?php $this->include('context/header.php', [ 'breadcrumb' => $breadcrumb ?? [] ]); ?>

            <main>
                <?php if( $this->witch("target") ): ?>
                    <div><?php $this->include( 'witch/menu-info.php', [ 'witch' => $this->witch("target") ]); ?></div>
                    <div><?php $this->include( 'witch/edit-menu-info.php', [ 'witch' => $this->witch("target") ]); ?></div>
                <?php endif; ?>

                <div class="tabs">
                    <?php if( $this->ww->cairn->invokation("arborescence") ): ?>
                        <div class="tabs__item">
                           <a href="#tab-navigation">
                               <i class="fas fa-sitemap"></i> 
                               Navigation
                           </a>
                         </div>
                    <?php endif; ?>

                    <?php if( !$this->witch()->id ): ?>
                        <div class="tabs__item selected">
                            <a href="#tab-current">
                                <i class="fas fa-bomb"></i> 404
                            </a>
                        </div>

                    <?php elseif( $this->tabs ): foreach( $this->tabs as $id => $tab ): ?>
                        <div    <?=($tab['hidden'] ?? null)? 'style="display: none;"': '' ?>
                                class="tabs__item <?=($tab['selected'] ?? null)? 'selected': '' ?>">
                            <a href="#<?=$id ?>">
                                <?=($tab['iconClass'] ?? null)? '<i  class="'.$tab['iconClass'].'"></i>': '' ?>
                                <?=$tab['text'] ?? '' ?>
                            </a>
                            <?php if( !empty($tab['close']) ): ?>
                                <a class="tabs__item__close" data-target="<?=$id ?>">
                                    <i class="fas fa-times"></i>
                                </a>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; else: ?>
                        <div class="tabs__item selected">
                            <a href="#tab-current">
                                <?php if( $this->witch()->hasCauldron() && $this->witch()->invoke ): ?>
                                    <i  class="fas fa-hat-wizard"></i>
                                <?php elseif( $this->witch()->hasCauldron() ): ?>
                                    <i  class="fas fa-mortar-pestle"></i>
                                <?php elseif( $this->witch()->invoke ): ?>
                                    <i  class="fas fa-hand-sparkles"></i>
                                <?php else: ?>
                                    <i class="fas fa-folder"></i>
                                <?php endif; ?>

                                <?=$this->witch()->name ?>

                                <?php if( $this->witch("target") ): ?>
                                    &nbsp;:
                                <?php elseif( $this->witch("mother") ): ?>
                                    from&nbsp;:
                                <?php endif; ?>
                                <?=$this->witch("target").$this->witch("mother")?>
                            </a>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="tabs-target">
                    <?php if( !$this->witch() ): ?>
                        <div class="tabs-target__item selected" id="tab-current">404</div>
                    <?php elseif( !$this->tabs ): ?>
                        <div class="tabs-target__item selected" id="tab-current"><?=$this->ww->cairn->invokation() ?></div>
                    <?php else: ?>
                        <?=$this->ww->cairn->invokation() ?>
                    <?php endif; ?>

                    <?php if( $this->witch("arborescence") ): ?>
                        <div class="tabs-target__item" id="tab-navigation">
                            <?=$this->ww->cairn->invokation("arborescence") ?>
                        </div>
                    <?php endif; ?>
                </div>
            </main>
            
            <?php if( $this->witch("chooseWitch") ): ?>
                <?=$this->ww->cairn->invokation("chooseWitch") ?>
            <?php endif; ?>
            
            <?php $this->include('context/footer.php'); ?>
        </div>
        
        <?php foreach( $this->getJsFiles() as $jsFile ): ?>
            <script src="<?=$jsFile?>"></script>
        <?php endforeach; ?>        
    </body>
</html>