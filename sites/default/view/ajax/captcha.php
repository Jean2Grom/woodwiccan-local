<?php /** @var WW\Module $this */ ?>

<?php if( $captchaError ): ?>
    <div class="ww-captcha-error"><?=$captchaError?></div>
<?php endif; ?>
<div class="ww-captcha">
    <div class="ww-captcha-col">
        <?php if( $hintImage ): ?>
            <img src="data:image/png;base64,<?=base64_encode( $hintImage )?>" /> 
        <?php endif; ?>
        <fieldset>
            <label for="input-<?=$id?>">write captcha code</label>
            <br/>
            <input type="text" 
                   id="input-<?=$id?>"
                   name="captcha" 
                   maxlength="6" 
                   size="6"/>
        </fieldset>
        <button class="ww-captcha-refresh">
            <i class="fa fa-refresh"></i>
            Refresh 
        </button>
    </div>
    <div class="ww-captcha-col">
        <?php foreach($captchaImages as $imageData): ?>
            <img class="ww-captcha-image" src="data:image/png;base64,<?=base64_encode( $imageData )?>" /> 
        <?php endforeach; ?>
    </div>
</div>
