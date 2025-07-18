<?php /** @var WW\Module $this */

$this->addCssFile('recipe/view.css');
$this->addJsFile('triggers.js');
?>

<h3>
    <i class="fa fa-eye"></i>
    <?=$recipe->name?>
</h3>
<em><?=$recipe->file ?? ''?></em>

<?php  if( $recipe->require ): 
    $require = $recipe->require;
    ?>
    <ul class="global-data"> 
        <?php $this->include('recipe/require-li.php', [ 
            'module'    => $this,
            'require'   => $require, 
        ]); ?>
    </ul>
<?php endif; ?>

<div class="fieldsets-container">
    <?php foreach( $recipe->composition ?? [] as $item ): ?>
        <fieldset>
            <legend><?=$item['name']?></legend>
            <ul>
                <li>
                    <div>Type</div>
                    <?php if( $item['recipe'] ?? false ): ?>
                        <a href="<?=$this->witch->url([ 'recipe' => $item['recipe']->name ])?>">
                            <?=$item['type']?>
                        </a>
                    <?php else: ?>
                        <div><?=$item['type']?></div>
                    <?php endif; ?>
                </li>
                <li>
                    <div>Mandatory</div>
                    <div><?=$item['mandatory'] ?? null? "true": "false"?></div>
                </li>
                <?php if( $item['require'] ?? false ): ?>
                    <?php $this->include('recipe/require-li.php', [ 
                        'module'    => $this,
                        'require'   => $item['require'],
                    ]); ?>
                <?php endif; ?>
            </ul>
        </fieldset>
    <?php endforeach; ?>
</div>
    
<div class="box__actions">
    <button class="trigger-href" 
            data-href="<?=$this->ww->website->getUrl( 'recipe/edit', ['recipe' => $recipe->name] )?>" 
            id="cauldron__edit">
        <i class="fa fa-pencil" aria-hidden="true"></i>
        Edit
    </button>
    <button class="trigger-href" 
            data-href="<?=$this->ww->website->getUrl('recipe')?>">
        <i class="fa fa-list" aria-hidden="true"></i>
        Back
    </button>
</div>
