<?php 
/**
 * @var WW\Witch $witch
 * @var WW\Witch[] $craftWitches
 */

if( $witch->craft() ): ?>
    <div class="box view__craft-witches">
        <h3>
            <i class="fa fa-project-diagram"></i>
            Craft Witches
        </h3>

        <?php if( !$craftWitches ): ?>
            <p><em>No other positions for this craft</em></p>

        <?php else: ?>
            <p><em>Witches list associated with this craft</em></p>
            <table>
                <thead>
                    <tr>
                        <th>Main</th>
                        <th>ID</th>
                        <th>Path</th>
                        <th>Name</th>
                    </tr>
                </thead>
                <tbody>
                    <form method="post" 
                          action="<?=$witch->ww->website->getUrl('edit?id='.$witch->id) ?>"
                          id="view-craft-witches-action">
                        <?php foreach( $craftWitches as $craftPositionWitch ): ?>
                            <tr>
                                <td>
                                    <div class="text-center">
                                        <input type="radio" 
                                               name="main"
                                               value="<?=$craftPositionWitch->id ?>"
                                               <?php if($craftPositionWitch->is_main): ?>
                                                    checked
                                               <?php else: ?>
                                                    class="trigger-action" 
                                                    data-action="switch-craft-main-witch" 
                                                    data-target="view-craft-witches-action" 
                                               <?php endif; ?> />
                                    </div>
                                </td>
                                <td>
                                    <div class="text-center"><?=$craftPositionWitch->id ?></div>
                                </td>
                                <td>
                                    <?php foreach( $craftPositionWitch->breadcrumb as $i => $breadcrumbItem ): ?>
                                        <a href="<?=$breadcrumbItem['href'] ?>" 
                                           target="_blank" 
                                           title="<?=$breadcrumbItem['data'] ?>"><em><?=$breadcrumbItem['name'] ?></em></a>
                                        &nbsp;&gt;
                                    <?php endforeach; ?>
                                </td>
                                <td>
                                    <a href="<?=$witch->ww->website->getUrl("view?id=".$craftPositionWitch->id."#tab-craft-part") ?>">
                                        <?=$craftPositionWitch->name ?>
                                        <em><?=$craftPositionWitch->id == $witch->id? "(this witch)": '' ?></em>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <input type="hidden" id="new-mother-witch-id" name="new-mother-witch-id" value="" />
                            
                    </form>
                </tbody>
            </table>
        <?php endif; ?>
        
        <div class="box__actions">
            <button class="trigger-action"
                    id="add-craft-witch-action"
                    style="display: none;"
                    data-action="add-craft-witch"
                    data-target="view-craft-witches-action">Add craft witch action</button>
            <button id="add-craft-witch">
                <i class="fa fa-plus"></i>
                Add craft witch
            </button>
        </div>
    </div>
<?php endif; ?>