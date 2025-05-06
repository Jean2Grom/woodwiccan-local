<?php

?>

<? foreach( $contents as $i => $content ) { ?>
    <div id="bloc<?=$i%2+1?>">
        <h2><?=$content['name']?></h2>
        
        <div id="intro_bloc<?=$i%2+1?>">
            <?=$content['description']?>
        </div>
        
        <div id="content_bloc<?=$i%2+1?>">
            
            <? if( $images[$i] ) { ?>
                <div class="schema">
                    <img src="<?=$images[$i]["file"]?>" 
                         alt="<?=$images[$i]["title"]?>" 
                         title="<?=$images[$i]["title"]?>" />
                </div>
            <? } ?>
            
            <? if( $bodies[$i] ) { ?>
                <div class="schema">
                    <p>
                        <?=$bodies[$i]?>
                    </p>
                </div>
            <? } ?>
            
            <? if( $columns[$i]['left'] ) { ?>
                <div class="colone1">
                    <h4>
                        <?=$content["attributes"]["headline-left"]->content()?>
                    </h4>
                    <p>
                        <?=$columns[$i]['left']?>
                    </p>
                </div>
            <? } ?>
            
            <? if( $columns[$i]['center'] ) { ?>
                <div class="colone2">
                    <h4>
                        <?=$content["attributes"]["headline-center"]->content()?>
                    </h4>
                    <p>
                        <?=$columns[$i]['center']?>
                    </p>
                </div>
            <? } ?>
            
            <? if( $columns[$i]['right'] ) { ?>
                <div class="colone3">
                    <h4>
                        <?=$content["attributes"]["headline-right"]->content()?>
                    </h4>
                    <p>
                        <?=$columns[$i]['right']?>
                    </p>
                </div> 
            <? } ?>
        </div>
        
        <? if( $downloads[$i] ) { ?>
            <div class="bouton_doc">
                <a href="<?=$downloads[$i]['file']?>">
                    <p><?=$downloads[$i]['text']?></p>
                </a>
            </div>
        <? } elseif( $links[$i] ) { ?>
            <div class="bouton_doc">
                <a href="<?=$links[$i]['href']?>" target="_blank">
                    <p><?=$links[$i]['text']?></p>
                </a>
            </div>
        <? } else { ?>
            <div class="no_doc"></div>
        <? } ?>
    </div>
<? } ?>