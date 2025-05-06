<?php /** @var WW\Attribute\TextAttribute $this */ 

$this->ww->website->context->addCssFile('../trumbowyg/dist/ui/trumbowyg.min.css');
$this->ww->website->context->addJsLibFile('jquery-3.6.0.min.js');
$this->ww->website->context->addJsLibFile('../trumbowyg/dist/trumbowyg.min.js');

$id = $this->type.'__'.$this->name;
?>

<textarea  id="<?=$id ?>" 
           name="<?=$this->tableColumns['value']?>"><?=$this->values['value']?></textarea>

<script>
    $('#<?=$id ?>').trumbowyg();
</script>