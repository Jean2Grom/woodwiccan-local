<?php 
/** 
 * @var WW\Cauldron $this 
 * @var string $input 
 */
?>

<div class="fieldsets-container">
    <fieldset class="ingredient">
        <legend>email</legend>
        <?php if( $this->content('email')?->exist() ): ?>
            <input  
                name="<?=$input?>[email][ID]" 
                value="<?=$this->content('email')?->id ?>" 
                type="hidden" 
            />
        <?php endif; ?>
        <!--input  
            name="<?=$input?>[email][name]" 
            value="email" 
            type="hidden" 
        />
        <input  
            name="<?=$input?>[email][type]" 
            value="string" 
            type="hidden" 
        /-->
        <input  
            name="<?=$input?>[email][value]"
            value="<?=$this->content('email')?->value() ?? ""?>" 
            type="email" 
            placeholder="address@domain.ext"
        />
    </fieldset>

    <fieldset class="ingredient">
        <legend>login</legend>
        <?php if( $this->content('login')?->exist() ): ?>
            <input  
                name="<?=$input?>[login][ID]" 
                value="<?=$this->content('login')?->id ?>" 
                type="hidden" 
            />
        <?php endif; ?>
        <!--input 
            name="<?=$input?>[login][name]" 
            value="login" 
            type="hidden" 
        />
        <input 
            name="<?=$input?>[login][type]" 
            value="string" 
            type="hidden" 
        /-->
        <input   
            name="<?=$input?>[login][value]" 
            value="<?=$this->content('login')?->value() ?? ""?>" 
            type="text"
        />
    </fieldset>

    <fieldset class="ingredient">
        <legend>password</legend>
        <?php if( $this->content('pass_hash')?->exist() ): ?>
            <input  
                name="<?=$input?>[pass_hash][ID]" 
                value="<?=$this->content('pass_hash')?->id ?>" 
                type="hidden"
            />
        <?php endif; ?>
        <!--input  
            name="<?=$input?>[password][name]" 
            value="pass_hash" 
            type="hidden"
        />
        <input 
            name="<?=$input?>[password][type]" 
            value="string" 
            type="hidden" 
        /-->
        <input 
            name="<?=$input?>[password][value]"
            value="<?=$this->content('pass_hash')?->value()? "xxxxxxxxxxxxxxxxxx" : ""?>" 
            type="password" 
        />  

        <legend>confirm password</legend>
        <input 
            name="<?=$input?>[password][confirm_value]" 
            value="<?=$this->content('pass_hash')?->value()? "yyyyyyyyyyyyyyyyyy" : ""?>" 
            type="password" 
        />
    </fieldset>
</div>