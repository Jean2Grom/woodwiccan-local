<?php
namespace WW\Attribute;

use WW\Attribute\ExternalTableAttribute;
use WW\Module;

class Files extends ExternalTableAttribute {
    
    function __construct( Module $module, $attributeName, $params=[] )
    {
        $this->type         =   "files";
        $this->name         =   $attributeName;
        $this->directory    =   "files/".$this->type."/".$this->name;
        
        parent::__construct( $module );
        
        $this->values['files']      =   [];
        $this->values['titles']     =   [];
        
        //$this->joinFields['files']  =   '`'.$this->type.'_'.$this->name.'`.file '.
        //                                    'AS `@_'.$this->type.'#files__'.$this->name.'`';
        
        $this->joinFields['files']  =   [
            'field' =>  "`".$this->type.'_'.$this->name.'`.file',
            'alias' =>  "@_".$this->type."#files__".$this->name
        ];
        
        //$this->joinFields['titles'] =   '`'.$this->type.'_'.$this->name.'`.title '.
        //                                    'AS `@_'.$this->type.'#titles__'.$this->name.'`';
        
        $this->joinFields['titles'] =   [
            'field' =>  "`".$this->type.'_'.$this->name.'`.title',
            'alias' =>  "@_".$this->type."#titles__".$this->name
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
                                //$this->values['files'][$i] = $valueItem;
                            }
                        }
                    }
                break;
                
                case '@_'.$this->type.'#titles__'.$this->name:
                    if( is_array($value) )
                    {
                        $this->values['titles'] = [];
                        foreach( $value as $i => $valueItem ){
                            if( !empty($valueItem) ){   
                                $this->values['titles'][$i] = $valueItem;   
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
                    }
                break;
            }
        }
        
        return true;
    }
    
    function getFile( $filename )
    {
        $filepath = $this->directory.'/'.$filename;
        
        if( !is_file($filepath) ){
            return false;
        }
        
        return $this->module->getHost()."/".$filepath;
    }

}