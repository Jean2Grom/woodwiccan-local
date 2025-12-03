<?php 
/** 
 * @var WW\Cauldron\Ingredient\DatetimeIngredient $this 
 * @var string $input 
 */
?>

<input  
    name="<?=$input?>[value]" 
    value="<?=$this->value?->format( 'Y-m-d\TH:i' ) ?>" 
    type="datetime-local" 
/>
