<?php
$this->module->addJsFile('externalTableAttributeEdit.js');
?>

<div id="<?=$this->type.'__'.$this->name?>_container">
    <?php foreach( $values as $i => $value ): ?>
        <h2>cible du lien (url)</h2>
        <p>
            <input  type="text" 
                    name="<?=$this->name.'@'.$this->type.'#hrefs'?>[<?=$i?>]"
                    value="<?=$value['href']?>" />
        </p>
        <h2>texte du lien</h2>
        <p>
            <input  type="text" 
                    name="<?=$this->name.'@'.$this->type.'#texts'?>[<?=$i?>]"
                    value="<?=$value['text']?>" />
        </p>
        <p>
            <input  type="checkbox" 
                    <?php if($value['external']): ?>
                        checked
                    <?php endif; ?>
                    name="<?=$this->name.'@'.$this->type.'#externals'?>[<?=$i?>][]" 
                    value="1" />
            Ouvrir dans une nouvelle fenêtre (ou onglet)
        </p>
    <?php endforeach; ?>
</div>

<input  type="button"
        id="addElement_<?=$this->type.'__'.$this->name?>"
        onclick="addElementForm('<?=$this->type.'__'.$this->name?>')"
        value="Ajouter un lien" />
<br/>

<div id="<?=$this->type.'__'.$this->name?>_model" style="display: none;">
    <h2>cible du lien (url)</h2>
    <p>
        <input  type="text" 
                name="<?=$this->name.'@'.$this->type.'#hrefs'?>[<?=count($values)?>]"
                value="" />
    </p>
    <h2>texte du lien</h2>
    <p>
        <input  type="text" 
                name="<?=$this->name.'@'.$this->type.'#texts'?>[<?=count($values)?>]"
                value="" />
    </p>
    <p>
        <input  type="checkbox" 
                checked
                name="<?=$this->name.'@'.$this->type.'#externals'?>[<?=count($values)?>][]" 
                value="1" />
        Ouvrir dans une nouvelle fenêtre (ou onglet)
    </p>
    
    <input  type="submit"
            name="storeButton"
            value="Valider le nouveau lien" />
</div>