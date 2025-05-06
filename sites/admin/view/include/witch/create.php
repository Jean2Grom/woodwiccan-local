<?php 
/**
 * @var WW\Witch $witch
 */
?>
<div class="box create__witch">
    <form   method="post"
            action="<?=$witch->ww->website->getUrl('edit?id='.$witch->id) ?>"
            id="create-witch-info">
        <h3>
            <i class="fa fa-folder-open"></i>
            Add Witch Daughter Form
        </h3>
        
        <p class="alert-message error" style="display: none;">Mandatory field</p>
        <label for="new-witch-name">Name*</label>
        <input  type="text" 
                value="" 
                placeholder="new witch name"
                name="new-witch-name" 
                id="new-witch-name" />
        
        <label for="new-witch-data">Description</label>
        <textarea   name="new-witch-data" 
                    id="new-witch-data" 
                    placeholder="new witch short description"></textarea>
        
        <label for="new-witch-priority">Priority</label>
        <input  type="number" 
                value="" 
                name="new-witch-priority" 
                id="new-witch-priority" />
    </form>
    
    <div class="box__actions">
        <button class="trigger-action" 
                data-target="create-witch-info"
                data-action="create-new-witch">
            <i class="fa fa-plus"></i>
            Create
        </button>
        
        <button class="view-daughters__create-witch__toggle">
            <i class="fa fa-times"></i>
            Cancel
        </button>
    </div>
</div>