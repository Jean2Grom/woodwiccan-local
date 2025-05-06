<?php

?>

<h2>
    Contenus de type
    "<?=$this->parameters["structure"]["value"]?>"
</h2>

<p>
    <input  type="checkbox" 
            <? if($this->values['actif']) { ?>
                checked
            <? } ?>
            name="<?=$this->tableColumns['actif']?>" 
            value="1" />
    Actif
</p>

<h2>Localisation [site] (url)</h2>
<p>
    <select name="<?=$this->tableColumns['fk_localisation']?>">
        <? foreach( $allLocalisations as  $allLocalisations_item ) { ?>
            <option <? if( strcmp($allLocalisations_item['id'], $this->values["fk_localisation"]) == 0 ) { ?>
                        selected="selected"
                    <? } ?>
                    value="<?=$allLocalisations_item['id']?>">
                <?=" [".$allLocalisations_item['site']."]"?>
                <?=$allLocalisations_item['spacing'].$allLocalisations_item['name']?>
                <?=" (".$allLocalisations_item['url'].")"?>
            </option>
        <? } ?>
    </select>
</p>

<h2>Profondeur maximum de recherche de contenus</h2>
<p>
    Si vous ne souhaitez pas mettre de limite à la recherche, laissez la valeur à 0
</p>
<p>
    <input  type="number"
            name="<?=$this->tableColumns['depth']?>" 
            value="<?=$this->values['depth']?>" />

</p>

<h2>Nombre maximum de contenus remontés (limite)</h2>
<p>
    <input  type="number"
            name="<?=$this->tableColumns['limit']?>" 
            value="<?=$this->values['limit']?>" />

</p>

<h2>Ordre des remontées</h2>
<p>
    <select name="<?=$this->tableColumns['order']?>">
        <? foreach( $this->ordersList() as $order ) { ?>
            <option <? if( strcmp($order, $this->values["order"]) == 0 ) { ?>
                        selected="selected"
                    <? } ?>
                    value="<?=$order?>">
                <?=$order?>
            </option>
        <? } ?>
    </select>

</p>