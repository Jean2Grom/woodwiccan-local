<?php /** @var WW\Context $this */ ?>

<div class="banner">
    <?php if( count($this->witch('menu')?->daughters() ?? []) > 0 ): ?>
        <a class="side-nav-toggler">
            <i class="fa fa-bars"></i>
        </a>
    <?php endif; ?>

    <div class="logo">
        <a href="<?=$this->website->getRootUrl()?>">
            <img    src="<?=$this->imageSrc("logo.png"); ?>" 
                    alt="WoodWiccan" 
                    title="WoodWiccan"/>
        </a>
    </div>

    <h1 title="<?=$this->witch()->data ?>">
        <?=$this->ww->witch()->name ?>
    </h1>

    <div class="header-user">
        <?php if( $this->ww->user->connexion ): ?>
            <a  href="<?=$this->website->getUrl( "view?id=".$this->witch("user")->id ) ?>">
                <i class="fa fa-user"></i>
                &nbsp;
                <span><?=$this->ww->user->name ?></span>
            </a>
            &nbsp;
            <a href="<?=$this->website->getUrl("login") ?>">
                <i class="fa fa-times"></i>
            </a>
        <?php endif; ?>    
    </div>
</div>