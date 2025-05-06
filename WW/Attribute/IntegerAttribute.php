<?php
namespace WW\Attribute;

/**
 * Class to handle Integer Attributes
 * 
 * @author Jean2Grom
 */
class IntegerAttribute extends \WW\Attribute 
{
    const ATTRIBUTE_TYPE    = "integer";
    const ELEMENTS          = [
        "value" => "INT(11) DEFAULT NULL",
    ];
    const PARAMETERS        = [];    
}
