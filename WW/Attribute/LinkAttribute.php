<?php
namespace WW\Attribute;

/**
 * Class to handle Link Attributes
 * 
 * @author Jean2Grom
 */
class LinkAttribute extends \WW\Attribute 
{
    const ATTRIBUTE_TYPE    = "link";
    const ELEMENTS          = [
        "href"          => "VARCHAR(511) DEFAULT NULL",
        "text"          => "VARCHAR(511) DEFAULT NULL",
        "external"      => "TINYINT(1) DEFAULT 1",
    ];
    const PARAMETERS        = [];
    
    function content( ?string $element=null  )
    {
        if( empty($this->values['href']) ){
            return null;
        }
        
        $content         = [];
        
        if( is_null($element) || $element == 'text' ){
            if( !empty($this->values['text']) ){
                $content['text'] = $this->values['text'];
            }
            else {
                $content['text'] = $content['href'];
            }
        }

        if( is_null($element) || $element == 'external' ){
            if( $this->values['external'] ){
                $content['external'] = true;
            }
            else {
                $content['external'] = false;
            }
        }
        
        $content['href'] = $this->values['href'];
        
        if(is_null($element) ){
            return $content;            
        }
        
        return $content[ $element ] ?? null;
    }
}
