<?php /** @var WW\Module $this */ ?>

<h1>Voir Structure : <?=$structure->name?></h1>

<p class="left modified">
    Dernière modification le <?=$creationDateTime->frenchFormat(true)?>
</p>

<h2>Attributs</h2>

<?php foreach( $attributes as $attributeName => $attributeData ): ?>
    <fieldset class="attributeField">
        <legend><?=$attributeName ?> [<?=($attributeData['class'])::ATTRIBUTE_TYPE ?>]</legend>
        <p>
            <h3>Nom</h3>
            <?=$attributeName ?>
        </p>
        <p>
            <h3>Type</h3>
            <?=($attributeData['class'])::ATTRIBUTE_TYPE ?>
        </p>
        
        <?php if( !empty(($attributeData['class'])::PARAMETERS) ): foreach( ($attributeData['class'])::PARAMETERS as $name => $parameterData ): ?>
            <p>
                <h3><?=$name?></h3>
                <?=$parameterData['value']?>
                <?php $this->ww->debug->dump($parameterData) ?>
            </p>
        <?php endforeach; endif; ?>
    </fieldset>
<?php endforeach; ?>

<br/>
<div id="action-controls">
    <form   method="post"
            style="width: auto; float: left; margin-right: 5px;"
            action="<?=$this->witch->url() ?>">
        <input  type="hidden"
                name="structure"
                value="<?=$structure->name?>" />
        <input  type="submit"
                name="deleteStructures"
                value="Supprimer Structure" />
    </form>
    
    <a href="<?=$this->witch->url() ?>">
        <input  type="button" 
                title="Revenir à la liste des structures" 
                value="Liste des structures" 
                name="listButton" 
                class="button" />
    </a>
    
    <a href="<?=$modificationHref?>">
        <input  type="button" 
                title="Modifier cette structure" 
                value="Modifier" 
                name="modifyButton" 
                class="button" />
    </a>
    
</div>
