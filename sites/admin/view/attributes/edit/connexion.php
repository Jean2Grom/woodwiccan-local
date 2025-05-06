<?php /** @var WW\Attribute\ConnexionAttribute $this */ ?>

<input  type="hidden"
        name="<?=$this->tableColumns['id']?>"
        value="<?=$this->values['id'] ?>" />

<h2>Name</h2>
<p>
    <input  type="text" 
            name="<?=$this->name.'@'.$this->type.'#name'?>"
            value="<?=$this->values['name']?>" />
</p>

<h2>Email</h2>
<p>
    <input  type="email" 
            name="<?=$this->name.'@'.$this->type.'#email'?>"
            value="<?=$this->values['email']?>" />
</p>

<h2>Login</h2>
<p>
    <input  type="text" 
            name="<?=$this->name.'@'.$this->type.'#login'?>"
            value="<?=$this->values['login']?>" />
</p>

<h2>Password</h2>
<p>
    <input  type="password" 
            name="<?=$this->name.'@'.$this->type.'#password'?>"
            value="" />
</p>

<h2>Confirm password</h2>
<p>
    <input  type="password" 
            name="<?=$this->name.'@'.$this->type.'#password_confirm'?>"
            value="" />
</p>

<h2>Profiles</h2>
<p>
    <select multiple
            name="<?=$this->name.'@'.$this->type.'#profiles[]'?>">
        <?php foreach( $sitesProfiles as $site => $siteProfileList ): ?>
            <optgroup label="<?=$site?>">
                <?php foreach( $siteProfileList as $profile ): ?>
                    <option <?php if( $this->values['profiles'] && in_array($profile->id, $this->values['profiles'] ) ): ?>
                                selected
                            <?php endif; ?>
                            value="<?=$profile->id?>">
                        <?=$profile->name?>
                    </option>
                <?php endforeach; ?>
            </optgroup>
        <?php endforeach; ?>
    </select>
</p>