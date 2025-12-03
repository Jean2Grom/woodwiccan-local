<?php 
/**
 * @var WW\Cauldron $this 
 * @var string $input
 */ 
$key = "WW_PROFILES__".md5( microtime().rand() );
?>
<div class="fieldsets-container">
    <?php foreach( $this->profiles as $profileID => $profileItem ): ?> 
        <fieldset class="ingredient">
            <input  
                id="<?=$key.$profileID?>"
                name="<?=$input?>[user__profile][]" 
                value="<?=$profileItem['id']?>" 
                <?=in_array($profileItem['id'], $this->selected)? 'checked': ''?> 
                type="checkbox" 
            />
            <label for="<?=$key.$profileID?>">
                [<?=$profileItem['site']?>]
                <?=$profileItem['name']?>
            </label>
        </fieldset>
    <?php endforeach; ?> 
</div>

