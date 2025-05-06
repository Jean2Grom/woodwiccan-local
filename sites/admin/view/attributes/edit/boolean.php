<?php /** @var WW\Attribute\BooleanAttribute $this */ ?>

<div>
  <input type="radio" 
         id="<?=$this->tableColumns['value'].'__1'?>" 
         name="<?=$this->tableColumns['value']?>" 
         <?=($this->values['value'] == 1)? 'checked': ''?>
         value="1">
  <label for="<?=$this->tableColumns['value'].'__1'?>">OUI</label>
</div>
<div>
  <input type="radio" 
         id="<?=$this->tableColumns['value'].'__0'?>" 
         name="<?=$this->tableColumns['value']?>" 
         <?=($this->values['value'] == 0)? 'checked': ''?>
         value="0">
  <label for="<?=$this->tableColumns['value'].'__0'?>">NON</label>
</div>