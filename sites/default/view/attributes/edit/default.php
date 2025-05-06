<?php /** @var WW\Attribute $this */ 

if( count($this->tableColumns) == 1 && count($this->values) == 1 ): ?>
    <input  type="text" 
            name="<?=array_values($this->tableColumns)[0]?>" 
            id="<?=array_values($this->tableColumns)[0]?>" 
            value="<?=htmlentities(array_values($this->values)[0] ?? '')?>" />
<?php else: 
    $this->ww->dump($this->tableColumns);
    $this->ww->dump($this->values);    
endif; 