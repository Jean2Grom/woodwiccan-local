<?php
namespace WW;

/**
 * Generic Class to handle craft Attributes, 
 * all attributes types must heritate this class
 * 
 * @author Jean2Grom
 */
abstract class Attribute 
{
    const ATTRIBUTE_TYPE        = null;
    const DIR                   = "attributes";
    const VIEW_DIR              = "view/attributes";

    public $parameters     = [];
    public $dbFields       = [];
    public $values         = [];
    public $tableColumns   = [];
    public $joinTables     = [];
    public $joinFields     = [];
    public $joinConditions = [];
    public $leftJoin       = [];
    public $groupBy        = [];
    
    public $name;
    public $type;
    
    /**
     * Generic Class to handle all crafts types
     * 
     * @var ?Craft
     */
    public ?Craft $craft;
    
    /** 
     * WoodWiccan container class to allow whole access to Kernel
     * @var WoodWiccan
     */
    public WoodWiccan $ww;
    
    function __construct( WoodWiccan $ww, string $name, array $parameters=[], ?Craft $craft=null )
    {
        $this->ww                   = $ww;
        $this->craft                = $craft;
        $this->name                 = $name;
        $this->parameters           = $parameters;
        
        $instanciedAttributeClass   = (new \ReflectionClass($this))->getName();
        $this->type                 = $instanciedAttributeClass::ATTRIBUTE_TYPE;
        
        foreach( $instanciedAttributeClass::ELEMENTS as $elementKey => $elementValue )
        {
            $this->tableColumns[ $elementKey ]  = self::getColumnName( $this->type, $this->name, $elementKey );
            $this->dbFields[ $elementKey ]      = "`".$this->tableColumns[ $elementKey ]."` ".$elementValue;
            $this->values[ $elementKey ]        = NULL;
        }
    }
    
    function set( $args )
    {
        foreach( $args as $key => $value ){
            $this->values[ $key ] = $value;
        }
        
        return $this;
    }
    
    function content( ?string $element=null ) 
    {
        if( is_null($element) )
        {
            if( is_array($this->values) && count($this->values) == 1 ){
                return array_values($this->values)[0];
            }

            return $this->values;
        }
        
        return $this->values[ $element ] ?? null;
        
    }
    
    function setValue( $key, $value )
    {
        if( in_array($key, array_keys($this->values) ) ){
            $this->values[ $key ] = $value;
        }
        
        return $this;
    }
    
    function display( $filename=false )
    {
        if( !$filename ){
            $filename = strtolower( $this->type );
        }
        
        $file = $this->ww->website->getFilePath( self::DIR."/view/".$filename.'.php');
        
        if( !$file ){
            $file = $this->ww->website->getFilePath( self::DIR."/view/default.php");
        }
        
        if( $file ){
            include $file;
        }
        
        return;
    }
    
    function edit( $filename=false )
    {
        if( !$filename ){
            $filename = strtolower( $this->type );
        }
        
        $file = $this->ww->website->getFilePath( self::DIR."/edit/".$filename.'.php');
        
        if( !$file ){
            $file = $this->ww->website->getFilePath( self::DIR."/edit/default.php");
        }
        
        if( $file ){
            include $file;
        }
        
        return true;
    }
    
    function save( ?Craft $craft=null ) 
    {
        if( $craft ){
            $this->craft = $craft;
        }
        
        return 0;
    }
    
    function clone( Craft $craft )
    {
        $clonedAttribute        = clone $this;        
        $clonedAttribute->craft = $craft;
        
        return $clonedAttribute;
    }
    
    function delete() {
        return true;
    }
    
    static function getColumnName( string $type, string $name, string $element ): string
    {
        return $name."@".$type."#".$element;
    }
    
    static function splitColumn( string $columnName )
    {
        $buffer         = explode( "@", trim($columnName) );
        $attributeName  = $buffer[0];
        
        if( isset($buffer[1]) )
        {
            $buffer             = explode( "#", $buffer[1] );
            $attributeType      = $buffer[0];
            $attributeElement   = $buffer[1];
        }
        
        return  [
            'name'      =>  $attributeName,
            'type'      =>  $attributeType ?? false,
            'element'   =>  $attributeElement ?? false,
        ];
    }
    
    function getJointure( string $fromTable ): array
    {
        $craftTable        = trim( $this->ww->db->escape_string($fromTable) );
        $jointuresParams    = [ 'craft_table' => $craftTable ];
        foreach( $this->joinTables as $joinTableData ){
            $jointuresParams[ $joinTableData['table'] ] = $joinTableData['table'].'|'.$craftTable.'@'.$this->name;
        }
        
        $jointures          = [];
        foreach( $this->joinTables as $joinTableData )
        {
            $jointureQueryPart  =   "LEFT JOIN ";
            $jointureQueryPart  .=  "`".$joinTableData['table']."` AS `".$jointuresParams[ $joinTableData['table'] ]."` ";
            $jointureQueryPart  .=  "ON ";
            $jointureQueryPart  .=  str_replace(
                                        array_map( fn($key): string => ':'.$key, array_keys($jointuresParams) ), 
                                        array_map( fn($value): string => "`".$value."`", array_values($jointuresParams) ), 
                                        $joinTableData['condition']
                                    );
            $jointureQueryPart  .=  " ";
            
            $jointures[] = $jointureQueryPart;
        }
        
        return $jointures;
    }
    
    function getJoinFields( string $fromTable ): array
    {
        $craftTable        = trim( $this->ww->db->escape_string($fromTable) );
        $jointuresParams    = [ 'craft_table' => $craftTable ];
        foreach( $this->joinTables as $joinTableData ){
            $jointuresParams[ $joinTableData['table'] ] = $joinTableData['table'].'|'.$craftTable.'@'.$this->name;
        }
        
        $joinFields = [];
        foreach( $this->joinFields as $joinField )
        {
            $field =    str_replace(
                            array_map( fn($key): string => ':'.$key, array_keys($jointuresParams) ), 
                            array_map( fn($value): string => "`".$value."`", array_values($jointuresParams) ), 
                            $joinField
                        );
            
            $joinFields[] = str_replace("`|", "|", $field)." ";
        }
        
        return $joinFields;
    }
    
    function getSelectFields( string $fromTable ): array
    {
        $querySelectFields = [];
        foreach( $this->tableColumns as $attributeElement => $attributeElementColumn )
        {
            $field  =   "`".$fromTable."`.`".$attributeElementColumn."` ";
            $field  .=  "AS `".$fromTable."|".$this->name;
            $field  .=  "#".$attributeElement."` ";

            $querySelectFields[] = $field;
        }
        
        array_push( $querySelectFields, ...$this->getJoinFields($fromTable) );
        
        return $querySelectFields;
    }
    
    static function splitSelectField( string $fieldName )
    {
        $buffer         = explode('|', $fieldName);
        $table          = $buffer[0];
        
        if( isset($buffer[ 1 ]) )
        {
            $subBuffer      = explode('#', $buffer[1]);
            $field          = $subBuffer[0];
            $fieldElement   = $subBuffer[1] ?? false;
        }
        
        return  [
            'table'     =>  $table,
            'field'     =>  $field,
            'element'   =>  $fieldElement,
        ];
    }
    
    static function list( array $extendDirs=[] )
    {
        $dirs = array_unique(array_merge($extendDirs, [__DIR__.'/Attribute']));
        
        $attributesList                 = [];
        $attributeNameSpaceClassPrefix  = __CLASS__."\\";
        $attributeNameSpaceClassSuffix  = "Attribute";
        
        foreach( $dirs as $dir ){
            foreach( scandir($dir) as $file ){
                if( substr($file, -(strlen($attributeNameSpaceClassSuffix)+4), -4 ) == $attributeNameSpaceClassSuffix )
                {
                    $className = substr($attributeNameSpaceClassPrefix.$file, 0, -4);
                    if( !empty($className::ATTRIBUTE_TYPE) ){
                        $attributesList[ $className::ATTRIBUTE_TYPE ] =  $className;            
                    }
                }
            }
        }
        
        return $attributesList;        
    }
    
    function searchCondition( string $craftTable, mixed $value )
    {
        if( $this->tableColumns['value'] )
        {
            $key = md5($craftTable.$this->tableColumns[ 'value' ].$value);
            return  [
                'query'     => "`".$this->ww->db->escape_string($craftTable)."`.`".$this->tableColumns[ 'value' ]."` = :".$key." ",
                'params'    => [ $key => $value ],
            ];
        }
        
        return false;
    }
    
    function getEditParams(): array {
        return array_values($this->tableColumns);
    }
    
    function update( array $params ) {
        foreach( $params as $key => $value )
        {
            $data = self::splitColumn($key);
            
            if( $data['type'] == $this->type 
                && $data['name'] == $this->name 
            ){
                $this->setValue( $data['element'], $value );
            }            
        }
    }
}
