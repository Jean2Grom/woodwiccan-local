<?php /** @var WW\Module $this */ ?>

<div class="box view__profile" data-profile="<?=$profile->id?>">
    <h3>
        <i class="fas fa-user"></i> <?=$profile->name?>
    </h3>
    <p><em><?=$profile->site != '*'? $profile->site: "All sites" ?></em></p>

    <table>
        <thead>
            <tr>
                <th>Module</th>
                <th>Status</th>
                <th>Position Witch</th>
                <th>Position Rules</th>
                <th>Custom</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach( $profile->policies as $policy ): ?>
                <tr>
                    <td>
                        <span class="text-center"><?=$policy->module ?></span>
                    </td>
                    <td>
                        <span class="text-center"><?=$policy->statusLabel ?></span>
                    </td>
                    <td>
                        <?php if( !empty($policy->positionId) ): ?>
                            <a href="<?=$this->ww->website->getUrl("view", [ 'id' => $policy->positionId ]) ?>"
                               target="_blank">
                                <?=$policy->positionName ?? $policy->positionId ?>
                            </a>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?=$policy->position_rules['ancestors']? "Parents": "" ?>
                        <?=$policy->position_rules['self']? "Self": "" ?>
                        <?=$policy->position_rules['descendants']? "Descendants": "" ?>
                    </td>
                    <td>
                        <?=$policy->custom_limitation ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="box__actions">
       <button  class="trigger-action" 
                data-confirm="Warning ! You are about to delete this profile"
                data-target="view-profile-form-<?=$profile->id?>"
                data-action="delete-profile">
            <i class="fa fa-trash"></i>
            Delete
       </button>
       <button class="view-edit-profile-toggle">
           <i class="fa fa-pencil"></i>
           Edit
       </button>
   </div>
</div>

<form method="post" id="view-profile-form-<?=$profile->id?>">
    <input type="hidden" name="profile-id" value="<?=$profile->id?>" />
</form>
