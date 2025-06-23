<?php /** @var WW\Module $this */ ?>

<?php foreach( array_values($this->witch()->daughters() ?? []) as $i => $daughter ): ?>
    <div id="bloc<?=$i%2+1?>">
        <h2><?=$daughter->name?></h2>
        
        <div id="intro_bloc<?=$i%2+1?>">
            <?=$daughter->name?>
        </div>
        
        <div id="content_bloc<?=$i%2+1?>">
            
            <?php if( $daughter->image?->file ): ?>
                <div class="schema">
                    <img src="<?=$daughter->image->file->value()?>" 
                         alt="<?=$daughter->image->caption?>" 
                         title="<?=$daughter->image->name?>" />
                </div>
            <?php endif; ?>
            
            <?php if( $daughter->body ): ?>
                <div class="schema">
                    <p><?=$daughter->body?></p>
                </div>
            <?php endif; ?>
            
            <?php if( $daughter->{'body-left'} ): ?>
                <div class="colone1">
                    <h4><?=$daughter->{'headline-left'}?></h4>
                    <p><?=$daughter->{'body-left'}?></p>
                </div>
            <?php endif; ?>
            
            <?php if( $daughter->{'body-center'} ): ?>
                <div class="colone2">
                    <h4><?=$daughter->{'headline-center'}?></h4>
                    <p><?=$daughter->{'body-center'}?></p>
                </div>
            <?php endif; ?>
            
            <?php if( $daughter->{'body-right'} ): ?>
                <div class="colone3">
                    <h4><?=$daughter->{'headline-right'}?></h4>
                    <p><?=$daughter->{'body-right'}?></p>
                </div>
            <?php endif; ?>
        </div>
        
        <div class="bouton_doc">
            <?php if( $daughter->link?->href ): ?>
                <a  <?=$daughter->link->external? 'target="_blank"': ''?> 
                    href="<?=$daughter->link->href?>">
                    <p><?=$daughter->link->text?></p>
                </a>
            <?php endif; ?>
        </div>
    </div>
<?php endforeach; ?>