<?php /** @var WW\Module $this */

$this->addCssFile('structures.css');
?>
<div class="view-content">
    <h1>Structures des données</h1>

    <div class="errorMessages">
        <?php foreach( $messages as $message ): ?>
            <p><?=$message?></p>
        <?php endforeach; ?>
    </div>

    <p>
        Les données sont stockées sous la forme de structures qui sont éditables ici.
    </p>

    <div class="structures-content__list">
        <form method="POST" name="structures">
            <div id="navHeader">
                <h2><?=$count?> Structures</h2>
            </div>

            <table id="structures-navHeader-table">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Contents</th>
                        <th>Drafts</th>
                        <th>Archives</th>
                        <th>Création</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach( $structures as $structure ): ?>
                        <tr>
                            <td>
                                <a href="<?=$structure['viewHref']?>">
                                    <?=$structure['name']?>
                                </a>
                            </td>
                            <td>
                                <?=$structure['count']['content']?>
                            </td>
                            <td>
                                <?=$structure['count']['draft']?>
                            </td>
                            <td>
                                <?=$structure['count']['archive']?>
                            </td>
                            <td>
                                <?=$structure['creation']->frenchFormat(true)?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <input  type="submit"
                    name="createStructure"
                    value="Créer Structure" />
        </form>
    </div>
    <div class="clear"></div>
</div>