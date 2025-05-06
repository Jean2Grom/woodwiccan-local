<?php /** @var WW\Attribute\FileAttribute $this */ ?>

<div class="current-file-display" <?=$srcFile? '': 'style="display: none;"' ?>>
    <?php if( $srcFile ): ?>
        <h2 class="current-file-target">Current file</h2>
        
        <a class="current-file-target" 
           href="<?=$srcFile?>" 
           target="_blank">
            <?=$this->values['file']?>
        </a>
    <?php endif; ?>
    
    <span class="new-file-target"></span>
    <a class="delete-file">
        <i class="fa fa-times"></i>
    </a>
</div>

<div class="change-file-container" <?=$srcFile? 'style="display: none;"': '' ?>>
    <div>
        <h2>Upload file</h2>

        <input  type="file" 
                class="change-file"
                name="<?=$this->name.'@'.$this->type.'#fileupload'?>" />
    </div>
    <div>    
        <h2>Move server file</h2>
        <input  type="text" 
                class="change-file"
                name="<?=$this->name.'@'.$this->type.'#filemove'?>" />
        <em>enter here full path filename</em>
    </div>
</div>

<input  type="hidden" 
        class="file-input"
        name="<?=$this->name.'@'.$this->type.'#file'?>" 
        id="<?=$this->name.'@'.$this->type.'#file'?>" 
        value="<?=$this->values['file']?>" />

<p>
    <h2>Filename</h2>
    <input  type="text" 
            name="<?=$this->name.'@'.$this->type.'#title'?>"
            value="<?=$this->values['title']?>" />
</p>
