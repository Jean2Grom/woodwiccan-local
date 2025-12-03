<?php 
/** 
 * @var WW\Cauldron\Ingredient\BooleanIngredient $this 
 * @var string $input 
 */
?>

<input  
    name="<?=$input?>[value]" 
    value='0' 
    type='hidden' 
/>
<input  
    name="<?=$input?>[value]" 
    value="1" 
    <?=$this->value? 'checked': ''?> 
    type="checkbox" 
/>

