<?php /** @var WW\Module $this */ ?>

<h1>
    Création de votre nouvelle structure
</h1>

<div class="errorMessages">
    <?php foreach( $messages as $message ): ?>
        <p><?=$message?></p>
    <?php endforeach; ?>
</div>

<p>
    Les structures sont la forme que doivent prendre les différents éléments de contenu dans Woody CMS. 
    Par exemple article, rubrique ou page d'accueil. 
</p>

<form name="createStructureForm" method="post" >
    <input  type="hidden"
            name="currentAction"
            value="creatingStructure" />
    
    <fieldset>
        <h2>Nom</h2>
        <p>
            Vous devez impérativement donner un nom à votre structure. 
        </p>
        <input  type="text"
                name="name"
                id="name"
                value="" />
        <br/>
        <br/>
        
        <h2>Copie depuis</h2>
        <p>
            Si vous souhaitez vous baser sur une structure existante, 
            séléctionnez son nom ici. 
            Si vous ne séléctionnez pas de structure, vous éditerez une structure 
            vierge. 
        </p>
        <p>
            <select name="structureCopy">
                <option value="">structure vierge</option>
                <?php foreach( $structuresData as $structure ): ?>
                    <option value="<?=$structure['name']?>">
                        <?=$structure['name']?>
                    </option>
                <?php endforeach; ?>
            </select>
        </p>
    </fieldset>
    <br />
    
    <input  type="submit" 
            title="Créer la structure pour pouvoir l'éditer" 
            value="Créer" 
            name="createButton" 
            class="button" />
    <a href="<?=$this->witch->url()?>">
        <input  type="button" 
                title="Retourner à la liste des structures" 
                value="Annuler" 
                name="cancelButton" 
                class="button" />
    </a>
</form>
