<?php 
/** 
 * @var WW\Context $this 
 * @var ?array $breadcrumb
 */ 
?>
<header>
    <div class="banner">
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

        <?php if( count($this->witch('menu')?->daughters() ?? []) > 0 ): ?>
            <a class="side-nav-toggler">
                <i class="fa fa-bars"></i>
            </a>
        <?php endif; ?>
    </div>
    
    <?php if( $breadcrumb ?? [] ): ?>
        <div class="banner-nav">
            <?php if( count($breadcrumb ?? []) > 1 ): ?>
                <a href="<?=array_reverse($breadcrumb)[1]['href']?>">
                    <i class="fa fa-arrow-up"></i>
                </a>
            <?php endif; ?>

            <a href="javascript: location.reload()">
                <i class="fa fa-rotate-right"></i>
            </a>

            <div class="breadcrumb">
                <?php if( $this->witch()->id ): foreach( $breadcrumb as $i => $breadcrumbItem ): ?>
                    <?=( $i > 0 )? "&nbsp;>&nbsp": "" ?>
                    <span class="breadcrumb__item" title="<?=$breadcrumbItem['data'] ?>">
                        <a href="<?=$breadcrumbItem['href'] ?>">
                            <?=$breadcrumbItem['name'] ?>
                        </a>
                    </span>
                <?php endforeach; endif; ?>
            </div>
        </div>
    <?php endif; ?>
</header>
