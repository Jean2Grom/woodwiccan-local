<?php /** @var WW\Attribute\LinkAttribute $this */ ?>

<h2>URL</h2>
<p>
    <input  type="text" 
            name="<?=$this->name.'@'.$this->type.'#href'?>"
            value="<?=$this->values['href']?>" />
</p>

<h2>Text</h2>
<p>
    <input  type="text" 
            name="<?=$this->name.'@'.$this->type.'#text'?>"
            value="<?=$this->values['text']?>" />
</p>

<p>
    <input  type="checkbox" 
            <?php if($this->values['external']): ?>
                checked
            <?php endif; ?>
            name="<?=$this->name.'@'.$this->type.'#external'?>" 
            value="1" />
    Open in new window
</p>
