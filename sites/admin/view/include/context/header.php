<?php /** @var WW\Context $this */ ?>

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

<?php if( $this->ww->user->connexion ): ?>
    <div class="header-user">
        <a  href="<?=$this->website->getUrl( "view?id=".$this->witch("user")->id ) ?>">
            <i class="fa fa-user"></i>
            &nbsp;
            <?=$this->ww->user->name ?>
        </a>
        &nbsp;
        <a href="<?=$this->website->getUrl("login") ?>">
            <i class="fa fa-times"></i>
        </a>
    </div>
<?php endif; ?>    
