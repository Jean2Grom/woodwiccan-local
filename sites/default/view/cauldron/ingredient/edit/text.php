<?php 
/** 
 * @var WW\Cauldron\Ingredient\TextIngredient $this 
 * @var string $input 
 */
?>
<fieldset>
        <?php if( $this->exist() ): ?>
                <input  type="hidden" 
                        name="<?=$input?>[ID]" value="<?=$this->id ?>" />
        <?php endif; ?>

        <label for="<?=$input?>#name">Name</label>
        <input  type="text" 
                id="<?=$input?>#name" 
                name="<?=$input?>[name]" 
                value="<?=$this->name ?>" />

        <label for="<?=$input?>#value">Value</label>
        <textarea       id="<?=$input?>#value" 
                        name="<?=$input?>[value]"><?=$this->value ?? ''?></textarea>
        
        <label for="<?=$input?>#priority">Priority</label>
        <input  type="number" 
                id="<?=$input?>#priority" 
                name="<?=$input?>[priority]" 
                value="<?=$this?>" />
</fieldset>