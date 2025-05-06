<?php /** @var WW\Module $this */

$this->addCssFile('boxes.css');
$this->addJsFile('triggers.js');
$this->addCssFile('profiles.css');
$this->addJsFile('profiles.js');

$this->addContextArrayItems( 'tabs', [
    'tab-current'       => [
        'selected'  => true,
        'iconClass' => "fas fa-list",
        'text'      => "List",
    ],
]);

foreach( $profiles as $id => $profile ){
    $this->addContextArrayItems( 'tabs', [
        'tab-profile-'.$id       => [
            'text'      => $profile->name,
            'close'     => true,
            'hidden'    => true,
        ],
    ]);
}

$this->addContextArrayItems( 'tabs', [
    'tab-profile-add'       => [
        'iconClass' => "fas fa-plus",
        'text'      => "New",
    ],
]);    
?>

<h2>User Profiles</h2> 
<p><em>Here you can manage permissions by handeling user profiles</em></p>

<?php $this->include('alerts.php', ['alerts' => $alerts]); ?>

<div class="tabs-target__item selected"  id="tab-current">
    <div class="box-container">
        <div><div class="box ">
            <h3>
                <i class="fas fa-users"></i> Profiles List
            </h3>
            <p><em>Filter by site here
            <select id="profile-list-site-filter">
                <option value="">All sites</option>
                <option value="all">Global</option>
                <?php foreach( $websitesList as $website ): ?>
                    <option value="<?=$website->site ?>">
                        <?=$website->site ?>
                    </option>
                <?php endforeach; ?>
            </select>
            </em></p>
            
            <table>
                <thead>
                    <tr>
                        <th>Site</th>
                        <th>Name</th>
                        <th>Edit</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach( $profiles as $profile ): ?>
                        <tr class="profile-container profile-site-<?=$profile->site == '*'? 'all': $profile->site ?>" data-id="<?=$profile->id?>">
                            <td>
                                <span class="text-center"><?=$profile->site ?></span>
                            </td>
                            <td>
                                <a class="view-profile" data-id="<?=$profile->id?>">
                                    <?=$profile->name?>
                                </a>
                            </td>
                            <td>
                                <a class="edit-profile text-center">
                                    <i class="fa fa-pencil"></i>
                                    &nbsp;
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            
            <div class="box__actions">
                <button class="tabs__item__triggering" href="#tab-profile-add" >
                    <i class="fa fa-plus"></i>
                    Create new
                </button>
            </div>
        </div></div>
    </div>
</div>

<?php foreach( $profiles as $id => $profile ): ?>
    <div class="tabs-target__item"  id="tab-profile-<?=$id ?>">
        <div class="box-container">
            <div><?php include $this->getIncludeViewFile('profile/display.php'); ?></div>
            <div><?php include $this->getIncludeViewFile('profile/edit.php'); ?></div>
        </div>
    </div>
<?php endforeach; ?>

<div class="tabs-target__item"  id="tab-profile-add">
    <div class="box-container">
        <div><?php include $this->getIncludeViewFile('profile/create.php'); ?></div>
    </div>
</div>
