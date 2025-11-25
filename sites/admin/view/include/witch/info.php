<?php 
/**
 * @var WW\Witch $witch
 */
?>

<div class="box view__witch-info">
    <h3>
        <i class="fa fa-hand-sparkles"></i>
        Access
    </h3>
    
    <table class="vertical">
        <tr>
            <td class="label">Site</td>
            <td class="value">
                <?php if( $witch->website() ): ?>
                    <a  target="_blank" 
                        href="<?=$witch->website()->getFullUrl() ?>">
                        <?=$witch->website() ?>
                    </a>
                <?php else: ?>
                    <em>no</em>
                <?php endif; ?>
            </td>
        </tr>
        <tr>
            <td class="label">Status</td>
            <td class="value"><?=$witch->status() ?? "" ?></td>
        </tr>
        <?php if( $witch->hasInvoke() ): ?>
            <tr>
                <td class="label">Invoke</td>
                <td class="value">
                    <?php if( $witch->hasInvoke() ): ?>
                        <?=$witch->invoke ?>
                    <?php else: ?>
                        <em>no</em>
                    <?php endif; ?>
                </td>
            </tr>
            <tr>
                <td class="label">URL</td>
                <td class="value">
                    <?php if( !is_null($witch->url) ): ?>
                        <a  target="_blank" 
                            href="<?=$witch->url() ?? "" ?>">
                            /<?=$witch->url?>
                        </a>
                    <?php else: ?>
                        no
                    <?php endif; ?>
                </td>
            </tr>
        <?php endif; ?>
        <tr>
            <td class="label">Witch ID</td>
            <td class="value"><?=$witch->id ?></td>
        </tr>
        <?php if( $witch->mother() ): ?>
            <tr>
                <td class="label"><em>Mother</em></td>
                <td class="value">
                    <a href="<?=$witch->ww->website->getUrl("view", [ 'id' => $witch->mother()->id ]) ?>">
                        <em><?=$witch->mother() ?></em>
                    </a>
                </td>
            </tr>
        <?php endif; ?>
    </table>
    
    <div class="box__actions">
        <?php if( $witch->mother() ): ?>
            <button class="trigger-action" 
                    data-confirm="Warning ! You are about to remove the witch whith all descendancy"
                    data-target="view-info-action"
                    data-action="delete-witch">
                <i class="fa fa-trash"></i>
                Delete
            </button>
        <?php endif; ?>
        <button class="view-edit-info-toggle">
            <i class="fa fa-pencil"></i>
            Edit
        </button>
    </div>
</div>

<form method="post" id="view-info-action"></form>
