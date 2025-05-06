<?php 
/**
 * @var WW\Witch $witch
 */

if( $witch->cauldron() ): ?>
    <div class="box">
        <h3>
            <i class="fa fa-project-diagram"></i>
            Cauldron Witches
        </h3>

        <form   method="post" 
                action="<?=$witch->ww->website->getUrl('view', [ 'id' => $witch->id ]) ?>#tab-cauldron-part"
                id="view-cauldron-witches-action">
            <input type="hidden" id="cauldron-new-witch-id" name="cauldron-new-witch-id" value="" />
            <input type="hidden" id="cauldron-witch-id" name="cauldron-witch-id" value="" />

            <button class="trigger-action"
                    style="display: none;"
                    id="remove-cauldron-witch-action"
                    data-action="remove-cauldron-witch" 
                    data-confirm="Remove cauldron from witch ?" 
                    data-target="view-cauldron-witches-action" >remove-cauldron-witch</button>

            <button class="trigger-action"
                    style="display: none;"
                    id="delete-cauldron-witch-action"
                    data-action="delete-cauldron-witch" 
                    data-confirm="Delete cauldron's witch ?" 
                    data-target="view-cauldron-witches-action" >delete-cauldron-witch</button>

            <?php if( count($witch->cauldron()->witches()) === 1 ): ?>
                <p><em>No other witch</em></p>

            <?php else: ?>
                <p><em>Cauldron's associated witches list</em></p>
                <table>
                    <thead>
                        <tr>
                            <th>Main</th>
                            <th>ID</th>
                            <th>Detach</th>
                            <th>Delete</th>
                            <th>Name</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach( $witch->cauldron()->witches() as $key => $witchItem ): ?>
                            <tr>
                                <td>
                                    <div class="text-center">
                                        <input type="radio" 
                                               name="main"
                                               value="<?=$witchItem->id ?>"
                                               <?php if( array_key_first($witch->cauldron()->witches()) === $key ): ?>
                                                    checked
                                               <?php else: ?>
                                                    class="trigger-action" 
                                                    data-action="switch-cauldron-main-witch" 
                                                    data-target="view-cauldron-witches-action" 
                                               <?php endif; ?> />
                                    </div>
                                </td>
                                <td>
                                    <div class="text-center"><?=$witchItem->id ?></div>
                                </td>
                                <td>
                                    <?php if( $witchItem->id !== $witch->id ): ?>
                                        <a  class="remove-cauldron-witch text-center"
                                            data-witch="<?=$witchItem->id?>">
                                            <i class="fa fa-times"></i>
                                        </a>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if( $witchItem->id !== $witch->id ): ?>
                                        <a  class="delete-cauldron-witch text-center"
                                            data-witch="<?=$witchItem->id?>">
                                            <i class="fa fa-trash"></i>
                                        </a>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if( $witchItem->id === $witch->id ): ?>
                                        <span class="highlighted">
                                            <?=$witchItem->name ?>
                                            <em>(this witch)</em>
                                        </span>
                                    <?php else: ?>
                                        <a href="<?=$witch->ww->website->getUrl( "view", ['id'=> $witchItem->id] ) ?>#tab-cauldron-part">
                                            <?=$witchItem->name ?>
                                        </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </form>
        
        <div class="box__actions">
            <button class="trigger-action"
                    id="cauldron-add-new-witch-action"
                    style="display: none;"
                    data-action="cauldron-add-new-witch"
                    data-target="view-cauldron-witches-action">Add cauldron new witch action</button>
            <button class="trigger-action"
                    id="cauldron-add-witch-action"
                    style="display: none;"
                    data-action="cauldron-add-witch"
                    data-target="view-cauldron-witches-action">Add cauldron witch action</button>

            <button id="add-cauldron-new-witch">
                <i class="fa fa-plus"></i>
                Create new cauldron witch
            </button>
            <button id="add-cauldron-witch">
                <i class="fa fa-mortar-pestle"></i>
                Add to witch
            </button>
        </div>
    </div>
<?php endif; ?>