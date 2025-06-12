<?php /** @var WW\Module $this */

$this->addCssFile('boxes.css');
$this->addJsFile('triggers.js');
$this->addJsFile('root.js');
?>
<div class="content">
    <h1>Tableau de bord</h1>
    
    <?php $this->include('alerts.php', ['alerts' => $alerts]); ?>
    
    <div class="content__data"><?=$this->witch->data?></div>
    
    <form method="post" id="data-edit">
        <textarea name="data" id="witch__data"></textarea>
        <button class="trigger-action"
                data-action="edit-data"
                data-target="data-edit">
            Publier
        </button>
    </form>
    
    <div class="box-container">
        <div class="box content__daughters">
            <h2>Liste des enfants</h2>
            
            <form method="post" id="daughters-action">
            <table>
                <thead>
                    <tr>
                        <?php foreach( $subTree['headers'] as $header ): ?>
                            <th>
                                <?=$header?>
                            </th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach( $subTree['data'] as $daughter ): ?>
                        <tr>
                            <td>
                                <a href="<?=$this->ww->website->baseUri."/view?id=".$daughter->id ?>">
                                    <?=$daughter->name ?>
                                </a>
                            </td>
                            <td>
                                <?=$daughter->site ?>
                            </td>
                            <td>
                                <?php if( !empty($daughter->invoke) && $daughter->hasCauldron() ): ?>
                                    Module & Contenu
                                <?php elseif( !empty($daughter->invoke) ): ?>
                                    Module
                                <?php elseif( $daughter->hasCauldron() ): ?>
                                    Contenu
                                <?php else: ?>
                                    Répertoire
                                <?php endif; ?>
                            </td>
                            <td class="text-right">
                                <input  class="priorities-input" 
                                        type="number"
                                        name="priorities[<?=$daughter->id ?>]" 
                                        value="<?=$daughter->priority ?>" />
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            </form>

            <div class="box__actions">
                <button class="trigger-href" 
                        data-href="<?=$createElementHref?>">
                    Ajouter un enfant
                </button>
                <button class="trigger-action"
                        data-action="edit-priorities"
                        data-target="daughters-action">
                    Changer les priorités
                </button>
            </div>
        </div>        
    </div>
</div>
