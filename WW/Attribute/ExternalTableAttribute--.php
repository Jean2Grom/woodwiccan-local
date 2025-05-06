<?php
namespace WW\Attribute;

use WW\Attribute;
use WW\Module;

class ExternalTableAttribute extends Attribute {
    
    function __construct( Module $module )
    {
        parent::__construct( $module );
        
        $this->tableColumns =   [
            'content_key'   => "@_".$this->type."__".$this->name,
        ];
        
        $this->values       =   [
            'content_key'    => null,
        ];
        
        $this->dbFields     =   [
            "`@_".$this->type."__".$this->name."` int(11) DEFAULT NULL", 
        ];
        
        $this->joinFields   =   [
            'id' => [
                'field' =>  "`".$this->type."_".$this->name."`.id",
                'alias' =>  "@_".$this->type."#id__".$this->name
            ],
        ];
        
        $this->leftJoin     =   [
            [
                'table'     =>  "attribute_".$this->type,
                'alias'     =>  $this->type."_".$this->name, 
                'condition' =>  "`".$this->type."_".$this->name."`.content_key ".
                                    "= craft.`".$this->tableColumns['content_key']."`"
            ],
        ];
        
        $this->groupBy      =   [
            "`@_".$this->type."#id__".$this->name."`",
        ];
    }
    
    function create( $craft )
    {
        $content_key = $this->getNewContentKey();
        
        $insertArray = $this->insert($content_key);
        
        if( $insertArray === false ){
            return false;
        }
        
        if( count($insertArray) > 0
            && !$craft->edit( [$this->tableColumns['content_key'] => $content_key] ) 
        ){
            return false;
        }
        
        return true;
    }
    
    function save( $craft ) 
    {
        if( !$this->delete() ){
            return false;
        }
        
        if( !$this->values['content_key'] ){
            $content_key = $this->getNewContentKey();
        }
        else {
            $content_key = $this->values['content_key'];
        }
        
        if( $this->insert($content_key) === false ){
            return false;
        }
        
        $this->values['content_key'] = $content_key;
        
        return true;
    }
    
    function delete()
    {
        if( $this->values['content_key'] )
        {
            $query =    "DELETE FROM `attribute_".$this->type."` WHERE ";
            $query .=   "`content_key` = ".$this->module->ww->db->escape_string($this->values['content_key']);
            
            if( !$this->module->ww->db->deleteQuery($query) ){
                return false;
            }
        }
        
        return true;
    }
    
    protected function globalInsert( $content_key, $externalTableFields )
    {
        $returnArray = [];
        foreach( $externalTableFields as $valueArray )
        {
            $query  =   "INSERT INTO `attribute_".$this->type."` ";
            $query .=   "( `content_key`";
            
            foreach( $valueArray as $column => $value ){
                $query .=   ", `".$this->module->ww->db->escape_string($column)."` ";
            }
            
            $query .=   " ) VALUES ('".$this->module->ww->db->escape_string($content_key)."'";
            
            foreach( $valueArray as $column => $value ){
                $query .=   ", '".$this->module->ww->db->escape_string($value)."' ";
            }
            
            $query .=   " ) ";
            
            $id = $this->module->ww->db->insertQuery($query);
                
            if( !$id ){
                return false;
            }
            
            $returnArray[] = $id;
        }
        
        return $returnArray;
    }
    
    private function insert( $content_key )
    {
        $limitIndice = 0;
        foreach( $this->values as $label => $values ){
            if( strcmp($label, 'content_key') != 0 ){
                if( count($values) > $limitIndice ){
                    $limitIndice = count($values);
                }
            }
        }
        
        $externalTableFields = [];
        for( $i=0; $i < $limitIndice; $i++ )
        {
            $externalTableFields[$i] = [];
            
            foreach( $this->values as $label => $values ){
                if( strcmp($label, 'content_key') != 0 && is_array($values) )
                {
                    if( strcmp(substr($label, -1), 's') == 0 ){
                        $field = substr($label, 0, -1);
                    }
                    else {
                        $field = $label;
                    }
                    
                    if( isset($values[$i]) ){
                        $externalTableFields[$i][$field] = $values[$i];
                    }
                }
            }
        }
        
        return $this->globalInsert( $content_key, $externalTableFields );
    }

    
    protected function getNewContentKey()
    {
        $query  =   "SELECT content_key FROM attribute_".$this->type." ";
        $query  .=  "ORDER BY content_key DESC LIMIT 0,1 ";
        
        $result = $this->module->ww->db->fetchQuery($query);
        
        if( $result == 0 ){
            return 1;
        }
        elseif( is_array($result) ){
            return (int) $result['content_key'] + 1;
        }
        else {
            return false;
        }
    }
}