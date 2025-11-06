<?php 
/** 
 * @var WW\Cauldron $this 
 * @var string $input 
 */

$this->ww->website->context->addJsFile('cauldron/file-edit.js');
?>
<div>
    <legend>Filename</legend>
    <?php if ($this->content('filename')?->exist()): ?>
        <input  
            name="<?=$input ?>[filename][ID]"
            value="<?=$this->content('filename')->id ?>"
            type="hidden"
        />
    <?php endif; ?>    
    <input  type="hidden" 
            name="<?=$input.'[filename][name]'?>" 
            value="filename" />
    <input  type="hidden" 
            name="<?=$input.'[filename][type]'?>" 
            value="string" />
    <input  class="file-input filename"
            type="text" 
            name="<?=$input.'[filename][value]'?>"
            value="<?=$title?>" 
            placeholder="enter filename"/>
    
    <?php if ($this->content('file')?->exist()): ?>
        <input  type="hidden"
                name="<?=$input ?>[file][ID]"
                value="<?=$this->content('file')->id ?>" />
    <?php endif; ?>
    <input  type="hidden" 
            name="<?=$input.'[file][name]'?>" 
            value="file" />
    <input  type="hidden" 
            name="<?=$input.'[file][type]'?>" 
            value="ww-file" />
    <?php $this->content('file')->form( 
            null, 
            [ 'input' => $input.'[file]' ]
        ); ?>
</div>
