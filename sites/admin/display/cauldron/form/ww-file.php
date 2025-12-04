<?php 
/** 
 * @var WW\Cauldron $this 
 * @var string $input 
 */

$this->addJsFile('cauldron/ww-file-edit.js');

$key = "_FILES__".md5(microtime().rand());
?>
<div class="file-edit-container">
    <div class="file-display" <?=$storagePath? '': 'style="display: none;"' ?>>
        <?php if( $storagePath ): ?>
            <a  class="current-file-focus" 
                href="/<?=$storagePath?>" 
                target="_blank">
                <?=$filename? $filename: basename($storagePath)?>
            </a>
        <?php endif; ?>
        
        <span class="new-file-focus"></span>

        <a class="remove-file">
            <i class="fa fa-times"></i>
        </a>
    </div>

    <div class="file-input" <?=$storagePath? 'style="display: none;"': '' ?>>
        <?php if( $this->content('storage-path')?->exist() ): ?>
            <input  
                name="<?=$input.'[path][ID]'?>" 
                value="<?=$this->content('storage-path')?->id ?>" 
                type="hidden" 
            />
        <?php endif; ?>
        <input  
            name="<?=$input.'[path][name]'?>" 
            value="storage-path" 
            type="hidden" 
        />
        <input  
            name="<?=$input.'[path][type]'?>" 
            value="string" 
            type="hidden" 
        />
        <input  
            name="<?=$input.'[path][$_FILES]'?>" 
            value="<?=$key?>" 
            type="hidden" 
        /> 

        <div class="switch-file-input-type">
            <a  class="selected" 
                data-target="upload-file-input">
                Upload file
            </a>
            /
            <a  class="" 
                data-target="move-file-input">
                Move server file
            </a>
        </div>
        <input  
            name="<?=$key?>" 
            type="file" 
            class="upload-file-input"
        />
        <input  
            name="<?=$input.'[path][value]'?>" 
            value="<?=$storagePath ?>" 
            type="text" 
            class="move-file-input"
            style="display: none;"
            placeholder="enter here full path filename" 
        />
        
        <?php if( $this->content('filename')?->exist() ): ?>
            <input  
                name="<?=$input.'[filename][ID]'?>" 
                value="<?=$this->content('filename')?->id ?>" 
                type="hidden"
            />
        <?php endif; ?>
        <input  
            name="<?=$input.'[filename][name]'?>" 
            value="filename" 
            type="hidden" 
        />
        <input  
            name="<?=$input.'[filename][type]'?>" 
            value="string" 
            type="hidden" 
        />
        <input  
            name="<?=$input.'[filename][value]'?>"
            value="<?=$filename?>" 
            type="hidden" 
            class="filename-file-input"
        />
    </div>
</div>
