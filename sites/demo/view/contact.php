<?php

?>

<div id="bloc1">
    <h2>CONTACTEZ WOOD WICCAN</h2>
    
    <? if( !$result ) { ?>
        <div id="text-contact">
            Merci de remplir ce formulaire et nous vous contacterons rapidemment afin de répondre à toutes vos questions.
        </div>
        
        <div id="content_bloc1">
            <form id="contact" method="post">
                <ul>
                    <li>
                        <label for="prenom">Prénom*</label>
                        <input  type="text" 
                                name="prenom" 
                                id="prenom" 
                                required placeholder="Ex:Jean" 
                                size="40" 
                                maxlength="40" />
                    </li>
                    <li>
                        <label for="nom">Nom*</label>  
                        <input  type="text" 
                                name="nom" 
                                id="nom" 
                                required placeholder="Ex:Dupont" 
                                size="40" 
                                maxlength="40" />
                    </li>
                    <li>
                        <label for="email">Email*</label>  
                        <input  type="email" 
                                name="email" 
                                id="email" 
                                required placeholder="Ex:info@witch-case.com" 
                                size="40" maxlength="40" />
                    </li>
                    <li>
                        <label for="societe">Société</label>  
                        <input  type="text" 
                                name="societe" 
                                id="societe" 
                                placeholder="Ex:Witch case" 
                                size="40" maxlength="40" />
                    </li>
                    <li>
                        <label for="question">Demande</label>  
                        <textarea name="question" rows="4" cols="31"></textarea>
                    </li>
                    <li>
                        <input id="envoyer" type="submit" name="bouton_formulaire" value="Envoyer" />
                    </li>
                </ul>
            </form>
        </div>
    <? } else { ?>
        <div id="text-contact">
            Nous avons bien pris en compte votre message, nous vous réponderons au plus vite.<br/>
            Merci de l'intérès que vous nous portez.
        </div>
        <div id="content_bloc1">
            &nbsp;
        </div>

    <? } ?>
</div>
