<?php 
/**
 * @var WW\Witch $witch
 * @var ?string $imgSrc
 */
?>

<div class="box view__witch-info">
    <h3>
        <i class="fa fa-hand-sparkles"></i>
        Access
    </h3>
    <p><em>Wich inner information</em></p>
    
    <table class="vertical">
        <tr>
            <td class="label">Site</td>
            <td class="value">
                <?php if( $witch->site ):  $this->ww->debug( $websitesList[ $witch->site ] ); ?>
                    <strong><?=$witch->site ?></strong>
                <?php else: ?>
                    <em>no</em>
                <?php endif; ?>
            </td>
        </tr>
        <tr>
            <td class="label">Witch ID</td>
            <td class="value"><?=$witch->id ?></td>
        </tr>
        <tr>
            <td class="label">Status</td>
            <td class="value"><?=$witch->status() ?? "" ?></td>
        </tr>
        <?php if( $witch->hasInvoke() ): ?>
            <tr>
                <td class="label">URL</td>
                <td class="value"><?=!is_null($witch->url)? '/'.$witch->url: "No"?></td>
            </tr>
            <tr>
                <td class="label">Invoke</td>
                <td class="value">
                    <?php if( $witch->hasInvoke() ): ?>
                        <strong><?=$witch->invoke ?></strong>
                    <?php else: ?>
                        <em>no</em>
                    <?php endif; ?>
                </td>
            </tr>
            <tr>
                <td class="label">Direct Access</td>
                <td class="value">
                    <a  target="_blank" 
                        href="<?=$witch->url(null, (new WW\Website($witch->ww, $witch->site) )) ?? "" ?>">
                        <i class="fas fa-hand-sparkles" aria-hidden="true"></i>
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

<form method="post" 
      action="<?=$witch->ww->website->getUrl('edit?id='.$witch->id) ?>"
      id="view-info-action"></form>

