<?php /** @var WW\Attribute\ConnexionAttribute $this */ ?>

<p>
    <strong>name&nbsp;:</strong>
    <?=$this->values['name']?>
</p>
<p>
    <strong>email&nbsp;:</strong>
    <?=$this->values['email']?>
</p>
<p>
    <strong>login&nbsp;:</strong>
    <?=$this->values['login']?>
</p>

<strong>profiles&nbsp;:</strong>
<ul>
    <?php foreach($profiles as $profile): ?>
        <li>
            [<?=$profile->site == '*'? 'all sites': $profile->site ?>]
            <?=$profile->name ?>
        </li>
    <?php endforeach; ?>
</ul>
<p>
    <strong>ID&nbsp;:</strong>
    <?=$this->values['id']?>
</p>
