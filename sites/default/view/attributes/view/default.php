<?php /** @var WW\Attribute $this */ 

if( count($this->values) > 1 ){
    $this->ww->dump($this->values);
}
else {
    echo $this->content();
}
