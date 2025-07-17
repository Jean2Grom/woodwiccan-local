<?php /** @var WW\Module $this */

$this->addCssFile('boxes.css');
$this->addJsFile('triggers.js');
$this->addJsFile('root.js');
?>

<div class="content">
    <!--div class="content__data"><?=$this->witch->data?></div>
    
    <form method="post" id="data-edit">
        <textarea name="data" id="witch__data"></textarea>
        <button class="trigger-action"
                data-action="edit-data"
                data-target="data-edit">
            Publier
        </button>
    </form-->
    
    <div class="box-container">
        <div class="box content__daughters">
            <h2>Daugthers</h2>
            
            <form method="post" id="daughters-action">
                <table>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Site</th>
                            <th>Type</th>
                            <th>Priority</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($this->getDaughters() as $daughter): ?>
                            <tr>
                                <td>
                                    <a href="<?=$this->ww->website->getUrl("/view?id=".$daughter->id) ?>">
                                        <?=$daughter->name ?>
                                    </a>
                                </td>
                                <td>
                                    <?=$daughter->site ?>
                                </td>
                                <td>
                                    <?php if( $daughter->hasInvoke() && $daughter->hasCauldron() ): ?>
                                        Craft & Invoke 
                                    <?php elseif( $daughter->hasInvoke() ): ?>
                                        Invoke
                                    <?php elseif( $daughter->hasCauldron() ): ?>
                                        Craft
                                    <?php else: ?>
                                        Folder
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
                        data-href="<?=$this->ww->website->getUrl("create?mother=".$this->witch->id)?>">
                    Add daugther
                </button>
                <button class="trigger-action"
                        data-action="edit-priorities"
                        data-target="daughters-action">
                    Update priorities
                </button>
            </div>
        </div>        
    </div>
</div>
