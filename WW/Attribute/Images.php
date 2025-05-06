<?php
namespace WW\Attribute;

use WW\Attribute\ExternalTableAttribute;

class Images extends ExternalTableAttribute {
    
    function __construct( $module, $attributeName, $params=[] )
    {
        $this->type         =   "images";
        $this->name         =   $attributeName;
        $this->directory    =   "files/".$this->type."/".$attributeName;
        
        parent::__construct( $module );
        
        $this->values['files']  = [];
        $this->values['titles'] = [];
        $this->values['links']  = [];
        
        $this->joinFields['files']  =   [
            'field' =>  "`".$this->type.'_'.$this->name.'`.file',
            'alias' =>  "@_".$this->type."#files__".$this->name
        ];
        
        $this->joinFields['titles']  =   [
            'field' =>  "`".$this->type.'_'.$this->name.'`.title',
            'alias' =>  "@_".$this->type."#titles__".$this->name
        ];
        
        $this->joinFields['links']  =   [
            'field' =>  "`".$this->type.'_'.$this->name.'`.link',
            'alias' =>  "@_".$this->type."#links__".$this->name
        ];
    }
    
    function set( $args )
    {
        if( !empty($_FILES['@_'.$this->type.'#fileuploads__'.$this->name]["tmp_name"]) 
            && is_array($_FILES['@_'.$this->type.'#fileuploads__'.$this->name]["tmp_name"])
        ){
            $tmpFilesInfos = $_FILES['@_'.$this->type.'#fileuploads__'.$this->name];
            
            foreach( $tmpFilesInfos["tmp_name"] as $i => $tmpFile ){
                if( !empty($tmpFile) )
                {
                    $check = getimagesize($tmpFile);

                    if( $check !== false )
                    {
                        $directoryPath = "";
                        foreach( explode('/', $this->directory) as $folder ) 
                        {
                            $directoryPath .= $folder;
                            if( !is_dir($directoryPath) ){
                                mkdir( $directoryPath, 0705 );
                            }
                            
                            $directoryPath .= "/";
                        }
                        
                        if( copy($tmpFile, $directoryPath.$tmpFilesInfos["name"][$i]) )
                        {
                            $filesArray     = $this->values['files'];
                            $filesArray[$i] = $tmpFilesInfos["name"][$i];
                            ksort($filesArray);
                            
                            $this->values['files'] = $filesArray;
                            //$this->values['files'][$i] = $tmpFilesInfos["name"][$i];
                        }
                    }
                }
            }
        }
        
        foreach( $args as $argColumn => $value ){
            switch( $argColumn )
            {
                case $this->tableColumns['content_key']:
                    $this->values['content_key'] = $value;
                break;
                
                case '@_'.$this->type.'#files__'.$this->name:
                    if(is_array($value) ){
                        foreach( $value as $i => $valueItem ){
                            if( !empty($valueItem) )
                            {
                                $filesArray     = $this->values['files'];
                                $filesArray[$i] = $valueItem;
                                ksort($filesArray);
                                
                                $this->values['files'] = $filesArray;
                            }
                        }
                    }
                break;
                
                case '@_'.$this->type.'#titles__'.$this->name:
                    if(is_array($value) )
                    {
                        $this->values['titles'] = [];
                        foreach( $value as $i => $valueItem ){
                            if( !empty($valueItem) ){   
                                $this->values['titles'][$i] = $valueItem;   
                            }
                        }
                    }
                break;
                
                case '@_'.$this->type.'#links__'.$this->name:
                    if(is_array($value) )
                    {
                        $this->values['links'] = [];
                        foreach( $value as $i => $valueItem ){
                            if( !empty($valueItem) ){   
                                $this->values['links'][$i] = $valueItem;
                            }
                        }
                    }
                break;
                
                case 'storeButton':
                    $matchLenght = strlen('@_'.$this->type.'#filedelete__'.$this->name);
                    
                    if( strcmp(substr($value, 0, $matchLenght), '@_'.$this->type.'#filedelete__'.$this->name) == 0 )
                    {
                        $deleteIndice = substr($value, $matchLenght + 1, -1);
                        unset($this->values['files'][$deleteIndice]); 
                        unset($this->values['titles'][$deleteIndice]); 
                        unset($this->values['links'][$deleteIndice]); 
                    }
                break;
            }
        }
        
        return true;
    }
    
    function content()
    {
        $content = [];
        foreach( $this->values['files'] as $i => $filename )
        {
            $filepath   = $this->getImageFile($filename);
            
            if( $filepath )
            {
                $buffer         = [];
                $buffer['file'] = $filepath;
                
                if( isset($this->values['titles'][$i]) ){
                    $buffer['title'] = $this->values['titles'][$i];
                }
                else {
                    $buffer['title'] = false;
                }
                
                if( isset($this->values['links'][$i]) ){
                    $buffer['link'] = $this->values['links'][$i];
                }
                else {
                    $buffer['link'] = false;
                }
                
                $content[] = $buffer;
            }
        }
        
        return $content;
    }
    
    function getImageFile( $filename )
    {
        $filepath = $this->directory.'/'.$filename;
        
        if( !is_file($filepath) ){
            return false;
        }
        
        return $this->module->getHost()."/".$filepath;
    }
}