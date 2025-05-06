<?php /** @var WW\Attribute\DecimalAttribute $this */ ?>

<input  type="number" 
        name="<?=$this->tableColumns['value']?>" 
        id="<?=$this->tableColumns['value']?>" 
        value="<?=htmlentities($this->values['value']) ?? ''?>" 
        min="0" step=".01" />
