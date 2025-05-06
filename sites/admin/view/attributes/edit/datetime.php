<?php /** @var WW\Attribute\DatetimeAttribute $this */ ?>

<input  type="datetime-local" 
        name="<?=$this->tableColumns['value']?>" 
        id="<?=$this->tableColumns['value']?>" 
        value="<?=$inputValue ?? ''?>" />
