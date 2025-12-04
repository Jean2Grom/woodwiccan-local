<?php 
/**
 * @var WW\Module $this
 * @var WW\Witch $witch
 */

$this->addCssFile('boxes.css');
$this->addJsFile('triggers.js');
$this->addJsFile('root.js');
?>

<div class="content">
    <div class="box-container">
        <div class="box content__daughters">
            <h2>Matriarches</h2>
            
            <form method="post" id="daughters-action">
                <table>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Site</th>
                            <th>Priority</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach( $witch->daughters() ?? [] as $daughter ): ?>
                            <tr>
                                <td>
                                    <a href="<?=$this->ww->website->getUrl("view", [ 'id' => $daughter->id ]) ?>">
                                        <?=$daughter->name ?>
                                    </a>
                                </td>
                                <td>
                                    <?=$daughter->site ?>
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
                        data-href="<?=$this->ww->website->getUrl("create-witch")?>">
                    <i class="fa fa-plus"></i>
                    Add daugther
                </button>
                <button class="trigger-action"
                        data-action="edit-priorities"
                        data-target="daughters-action">
                    <i class="fa fa-sort-numeric-up-alt"></i>
                    Update priorities
                </button>
            </div>
        </div>        
    </div>
</div>
