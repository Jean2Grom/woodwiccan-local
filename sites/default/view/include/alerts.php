<?php 
/**
 * @var WW\Module $this
 * @var ?array $alerts
 */

if( !empty($alerts) ): 
    $this->addCssFile('alert-message.css');
    ?>

    <div class="alert-message-container">
        <?php foreach( $alerts as $alert ): ?>
            <p class="alert-message <?=$alert['level']?>">
                <?=$alert['message']?>
            </p>
        <?php endforeach; ?>
    </div>

    <div class="restore-alert-message">
        <i class="fa fa-bell"></i>
        <!--i class="fa fa-window-restore"></i-->
        
        <?php foreach( $alerts as $alert ): ?>
            <span class="<?=$alert['level']?>"><?=$alert['message']?></span>
        <?php endforeach; ?>
    </div>

<?php endif; 