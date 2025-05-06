<?php
/** 
 * @var WW\Cauldron $this 
 * @var string $input 
 */

$this->ww->website->context->addJsFile('cauldron/image-edit.js');

$key = "_FILES__".md5( microtime().rand() );
?>
<div class="image-edit-container">
    <legend>Image name</legend>
    <?php if ($this->content('name')->exist()): ?>
        <input  
            name="<?=$input ?>[name][ID]"
            value="<?=$this->content('name')->id ?>" 
            type="hidden"
        />
    <?php endif; ?>
    <input  
        name="<?=$input.'[name][name]' ?>"
        value="name" 
        type="hidden"
    />
    <input  
        name="<?=$input.'[name][type]' ?>"
        value="string" 
        type="hidden"
    />
    <input  
        name="<?=$input.'[name][value]' ?>"
        value="<?=$name ?>"
        class="image-input name"
        type="text"
        placeholder="enter image name" 
    />
    
    <?php if( $this->content('file')->exist() ): ?>
        <input  
            name="<?=$input ?>[file][ID]"
            value="<?= $this->content('file')->id ?>" 
            type="hidden"
        />
    <?php endif; ?>
    <input  
        name="<?=$input.'[file][name]' ?>"
        value="file" 
        type="hidden"
    />
    <input  
        name="<?=$input.'[file][type]' ?>"
        value="ww-file" 
        type="hidden"
    />
    <div class="image-display" <?=$storagePath? '': 'style="display: none;"' ?>>
        <?php if( $storagePath ): ?>
            <legend class="current-image-focus">Current image</legend>
            <img class="current-image-focus" src="<?='/'.$storagePath ?>" /> 

            <input  type="hidden" 
                    class="current-image-focus"
                    name="<?=$input.'[file][content][path][value]'?>" 
                    value="<?=$storagePath ?>"  />            
        <?php endif; ?>

        <img class="new-image-focus" src="" />
        <br/>
        
        <a class="remove-image">
            <i class="fa fa-times"></i>
            <span>remove image</span>
        </a>
    </div>
    
    <div class="file-input" <?= $storagePath ? 'style="display: none;"' : '' ?>>
        <?php if( $this->content('file')->content('storage-path')?->exist() ): ?>
            <input  
                name="<?=$input.'[file][content][path][ID]' ?>"
                value="<?=$this->content('file')->content('storage-path')?->id ?>" 
                type="hidden"
            />
        <?php endif; ?>
        <input  
            name="<?=$input.'[file][content][path][name]' ?>"
            value="storage-path" 
            type="hidden"
        />
        <input  
            name="<?=$input.'[file][content][path][type]' ?>"
            value="string" 
            type="hidden"
        />
        <input  
            name="<?=$input.'[file][content][path][$_FILES]' ?>"
            value="<?=$key ?>" 
            type="hidden"
        />

        <?php if( $this->content('file')->content('filename')?->exist() ): ?>
            <input  
                name="<?=$input.'[file][content][filename][ID]' ?>"
                value="<?=$this->content('file')->content('filename')?->id ?>" 
                type="hidden"
            />
        <?php endif; ?>
        <input  
            name="<?=$input.'[file][content][filename][name]' ?>"
            value="filename" 
            type="hidden"
        />
        <input  
            name="<?=$input.'[file][content][filename][type]' ?>"
            value="string" 
            type="hidden"
        />
                
        <legend>Upload file</legend>
        <input  
            name="<?= $key ?>" 
            type="file"
            accept="image/*"
            class="upload-image-input"
        />
        <input  
            name="<?=$input.'[file][content][filename][value]' ?>"
            value="<?=$filename ?>" 
            type="hidden"
            class="filename-image-input"
        />
    </div>
    
    <legend>Image caption</legend>
    <?php if ($this->content('caption')->exist()): ?>
        <input  
            name="<?=$input ?>[caption][ID]"
            value="<?=$this->content('caption')->id ?>" 
            type="hidden"
        />
    <?php endif; ?>
    <input  
        name="<?=$input.'[caption][name]' ?>"
        value="caption" 
        type="hidden"
    />
    <input  
        name="<?=$input.'[caption][type]' ?>"
        value="string" 
        type="hidden"
    />
    <input  
        name="<?=$input.'[caption][value]' ?>"
        value="<?=$caption ?>"
        type="text"
        class="image-input caption"
        placeholder="enter caption" 
    />
</div>