<div class="current-file-display" <?=$srcFile? '': 'style="display: none;"' ?>>
    <?php if( $srcFile ): ?>
        <h2 class="current-file-target">Current image</h2>
        
        <img class="current-file-target" src="<?=$srcFile?>" />
    <?php endif; ?>
    
    <img class="new-image-target" src="" />
    
    <a class="delete-image" >
        <i class="fa fa-times"></i>
    </a>
</div>

<div class="change-file-container" <?=$srcFile? 'style="display: none;"': '' ?>>
    <h2>Browse image file</h2>
    
    <input  type="file" 
            accept="image/*"
            class="change-image"
            name="<?=$this->name.'@'.$this->type.'#fileupload'?>" />
    
    <input  type="hidden" 
            class="file-input"
            name="<?=$this->name.'@'.$this->type.'#file'?>" 
            id="<?=$this->name.'@'.$this->type.'#file'?>" 
            value="<?=$this->values['file']?>" />
</div>

<p>
    <h2>Caption</h2>
    <input  type="text" 
            name="<?=$this->name.'@'.$this->type.'#title'?>"
            value="<?=$this->values['title']?>" />
</p>

