<?php /** @var WW\Module $this */ ?>

<h1>
    Modifier la Structure&nbsp;: <?=$structureName?>
</h1>

<div class="errorMessages" >
    <?php foreach( $messages as $message ): ?>
        <p><?=$message?></p>
    <?php endforeach; ?>
</div>

<form   method="post"
        name="editStructureForm">
    <input  type="hidden"
            name="currentAction"
            value="editingStructure" />
    
    <?php 
    $indice = 0;
    foreach( $attributes as $attributeName => $attributeData ): ?>
        <fieldset class="attributeField" >
            <legend><?=$attributeName ?> [<?=($attributeData['class'])::ATTRIBUTE_TYPE ?>]</legend>
            <p>
                <h3>Nom</h3>
                <input  type="text" 
                        value="<?=$attributeName ?>" 
                        name="attributes[<?=$indice?>][name]" />
            </p>
            
            <p>
                <h3>Type</h3>
                <?=($attributeData['class'])::ATTRIBUTE_TYPE ?>
                <input  type="hidden"
                        name="attributes[<?=$indice?>][type]"
                        value='<?=($attributeData['class'])::ATTRIBUTE_TYPE ?>' />
            </p>
            
            <?php if( !empty(($attributeData['class'])::PARAMETERS) ): foreach( ($attributeData['class'])::PARAMETERS as $name => $parameterData ): ?>
                <p>
                    <?=call_user_func_array([$attribute, $attribute->parameters[$name]['inputForm']], [$indice])?>
                </p>
            <?php endforeach; endif; ?>
            
            <input  type="submit"
                    name="deleteAttribute[<?=$indice?>]"
                    value="Supprimer attribut" />
        </fieldset>
    <?php 
    $indice++;
    endforeach; ?>
    
    <fieldset class="attributeField">
        <legend>Nouvel Attribut</legend>
        <h3>Type</h3>
        <select name="addAttributType">
            <?php foreach( array_keys($attributesList) as $attributeName ): ?>
                <option value="<?=$attributeName?>">
                    <?=$attributeName?>
                </option>
            <?php endforeach; ?>
        </select>
        
        <input  type="submit"
                name="addAttribute"
                value="Ajouter Attribut" />
    </fieldset>
    
    <br/>
    <input  type="submit"
            name="publishStructure"
            value="Publier Structure" />
    <a href="<?=$viewHref ?>">
        <input  type="button"
                name="cancel"
                value="Annuler" />
    </a>
</form>
