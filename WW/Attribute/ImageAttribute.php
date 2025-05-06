<?php
namespace WW\Attribute;

/**
 * Class to handle Image Attributes
 * 
 * @author Jean2Grom
 */
class ImageAttribute extends FileAttribute
{
    const ATTRIBUTE_TYPE    = "image";
    
    function setValue($key, $value)
    {
        if( $key == "file" && !empty($_FILES[ $this->name.'@'.$this->type.'#fileupload' ]["tmp_name"]) )
        {
            $tmpFileInfos = $_FILES[ $this->name.'@'.$this->type.'#fileupload' ];

            $check = getimagesize($tmpFileInfos["tmp_name"]);

            if( $check !== false )
            {
                $directoryPath = "";
                foreach( explode('/', $this->directory) as $folder ) 
                {
                    $directoryPath .= $folder;
                    if( !is_dir($directoryPath) ) 
                    {   mkdir( $directoryPath, 0705 );  }

                    $directoryPath .= "/";
                }

                if( copy($tmpFileInfos["tmp_name"], $directoryPath.$tmpFileInfos["name"]) ){
                    $this->values['file'] = $tmpFileInfos["name"];
                }
            }
            
            return $this;
        }
        
        return parent::setValue($key, $value);
    }
}
