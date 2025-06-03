<?php 
/**
 * @var WW\Cauldron $this 
 * @var string $input
 */ 
?>
<div class="fieldsets-container">
    <?php foreach( $this->profiles as $profileItem ): ?> 
        <fieldset class="ingredient">
            <input  
                name="<?=$input?>[user__profile][]" 
                value="<?=$profileItem['id']?>" 
                <?=$this->value? 'checked': ''?> 
                type="checkbox" 
            />
            [<?=$profileItem['site']?>]
            <?=$profileItem['name']?>            
        </fieldset>
    <?php endforeach; ?> 
</div>

