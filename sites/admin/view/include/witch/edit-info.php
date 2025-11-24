<?php 
/**
 * @var WW\Website[] $websitesList
 * @var WW\Witch $witch
 */
?>
<div class="box edit__witch-info">
    <form   method="post"
            id="edit-witch-info">
        <h3>
            <i class="fa fa-hand-sparkles"></i>
            Access Edit Form
        </h3>
        
        <label for="witch-site">
            Site
        </label>
        <select name="witch-site" 
                id="witch-site"
                data-init="<?=$witch->site ?>">
            <option value="">
                no site selected
            </option>
            <?php foreach( $websitesList as $website ): ?>
                <option <?=($witch->website()?->site === $website->site)? 'selected' :'' ?>
                        value="<?=$website->site ?>">
                    <?=$website->name ?> 
                </option>
            <?php endforeach; ?>
        </select>

        <div <?=!is_null($witch->site)? 'style="display: none;"' :'' ?>
            class="witch-info__part witch-info__part-">

            <label for="witch-status-">
                Status
            </label>
            <select name="witch-status[no-site-selected]" 
                    id="witch-status-"  
                    data-init="<?=$witch->statusLevel ?>">
                <?php foreach(  $witch->ww->configuration->read( "global", "status" ) as $statusKey => $statusLabel ): ?>
                    <option <?=($witch->statusLevel === $statusKey)? 'selected': '' ?>
                            value="<?=$statusKey ?>"><?=$statusLabel ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <?php foreach( $websitesList as $site => $website ): ?>
            <div <?=($witch->site !== $website->site)? 'style="display: none;"' :'' ?>
                class="witch-info__part witch-info__part-<?=$site ?>">
                
                <label for="witch-status-<?=$site ?>">
                    Status
                </label>
                <select name="witch-status[<?=$site ?>]" 
                        id="witch-status-<?=$site ?>"  
                        data-init="<?=$witch->statusLevel ?>">
                    <?php foreach( $website->status() as $statusKey => $statusLabel ): ?>
                        <option <?=($witch->statusLevel === $statusKey)? 'selected': '' ?>
                                value="<?=$statusKey ?>"><?=$statusLabel ?></option>
                    <?php endforeach; ?>
                </select>                
                
                <label for="witch-invoke-<?=$site ?>">
                    Module to invoke
                </label>
                <select name="witch-invoke[<?=$site ?>]" 
                        id="witch-invoke-<?=$site ?>"                                  
                        class="witch-invoke"
                        data-init="<?=$witch->invoke ?>">
                    <option value="">
                        no module to invoke
                    </option>
                    <?php foreach( $website->listModules() as $moduleItem ): ?>
                        <option <?=($witch->invoke === $moduleItem)? 'selected': '' ?>
                                value="<?=$moduleItem ?>"><?=$moduleItem ?></option>
                    <?php endforeach; ?>
                </select>
                
                <!--label for="witch-context-<?=$site ?>">
                    Forced Context
                    <em>You can force default context here</em>
                </label>
                <select name="witch-context[<?=$site ?>]" 
                        id="witch-context-<?=$site ?>" 
                        data-init="<?=$witch->context?>">
                    <option value="">Empty</option>
                    
                    <?php /*foreach( $website->listContexts() as $contextItem ): ?>
                        <option value="<?=$contextItem ?>"><?=$contextItem ?></option>
                    <?php endforeach; */?>
                </select-->                
            </div>
        <?php endforeach; ?>
        
        <div id="site-selected"
             <?=!$witch->site? 'style="display: none;"' :'' ?>>
            <div class="auto-url-disabled"
                 <?=!$witch->url? 'style="display: none;"' :'' ?>>
                <label for="witch-url">
                    URL
                </label>
                <div class="url-input">
                    <span class="url-input-prefix">/</span>
                    <input  type="text"
                            name="witch-url"
                            id="witch-url"
                            data-init="<?=$witch->url?>"
                            value="<?=$witch->url ?>" />
                </div>
                <label  title="uncheck if you want to input a closest URL parent relative URL"
                        for="witch-full-url">Full URL</label>
                <input  title="uncheck if you want to input a closest URL parent relative URL"
                        type="checkbox" 
                        id="witch-full-url" 
                        <?=$witch->url ? 'checked': '' ?>
                        name="witch-full-url" />
            </div>
            
            <label for="witch-auto-url">
                Automatic URL generation
            </label>
            <input type="checkbox" 
                   id="witch-auto-url" 
                   <?=$witch->url ? '': 'checked' ?>
                   name="witch-automatic-url" />
        </div>
    </form>
    
    <div class="box__actions">
        <button class="trigger-action" 
                data-target="edit-witch-info"
                data-action="edit-witch-info">
            <i class="fas fa-save"></i>
            Save
        </button>        
        <button class="edit-info-reinit">
            <i class="fa fa-undo"></i>
            Reinit Form
        </button>        
        <button class="view-edit-info-toggle">
            <i class="fa fa-times"></i>
            Cancel
        </button>
    </div>
</div>
