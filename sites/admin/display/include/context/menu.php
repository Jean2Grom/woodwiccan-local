<?php 
/** 
 * @var WW\Context $this 
 * @var ?WW\Witch[] $menu
 */ 
?>
<nav>
    <div class="nav-container">
        <div class="nav-menu">
            <?php foreach( $menu ?? [] as $menuItemWitch ): ?>
                <a href="<?=$menuItemWitch->url() ?>">
                    <?=$menuItemWitch->name?>
                </a>
            <?php endforeach; ?>
        </div>

        <?php if( $this->ww->user->connexion ): ?>
            <div class="nav-user">
                <a  href="<?=$this->website->getUrl( "view", ['id' => $this->witch("user")->id] ) ?>">
                    <span><?=$this->ww->user->name ?></span>
                </a>
                <a href="<?=$this->website->getUrl("login") ?>">
                    <i class="fa fa-times"></i>
                </a>
            </div>
        <?php endif; ?>    
    </div>
</nav>
