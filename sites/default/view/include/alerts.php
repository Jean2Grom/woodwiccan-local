<?php /** @var WW\Module $this */ 

if( !empty($alerts) ): 
    $this->addCssFile('alert-message.css');
    
    foreach( $alerts as $alert ): ?>
        <p class="alert-message <?=$alert['level']?>">
            <?=$alert['message']?>
        </p>
    <?php endforeach; 
endif; 