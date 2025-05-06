<?php /** @var WW\Attribute\IntegerAttribute $this */ ?>

<input  type="number" 
        name="<?=$this->tableColumns['value']?>" 
        id="<?=$this->tableColumns['value']?>" 
        value="<?=htmlentities($this->values['value']) ?? ''?>" 
        min="0" step="1" />
