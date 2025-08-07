<?php /** @var WW\Module $this */ ?>

<?php 
if( $this->isRedirection ): ?>
    <p><em>Login requested</em></p>
<?php endif; 

if( !empty($alerts) ): 
    $this->addCssFile('alert-message.css');

    foreach( $alerts as $alert ): ?>
        <p class="alert-message <?=$alert['level']?>">
            <?=$alert['message']?>
        </p>
    <?php endforeach;
endif; ?>

<form method="POST">
    <input type="hidden" name="action" value="login" />

    <label for="username">Username or email</label>
    <input type="text" name="username" id="username"/>

    <label for="password">Password</label>
    <input type="password" name="password" id="password" />

    <button class="">
        Sign in
    </button>
</form>
