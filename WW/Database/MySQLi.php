<?php
namespace WW\Database;

use WW\WoodWiccan;

/**
 * MySQLi database driver
 * 
 * @author Jean2Grom
 */
class MySQLi implements DatabaseInterface
{
    public $mysqli;
    
    /** 
     * WoodWiccan container class to allow whole access to Kernel
     * @var WoodWiccan
     */
    public WoodWiccan $ww;
    
    function __construct( WoodWiccan $ww, $parameters )
    {
        $this->ww   = $ww;
        
        $this->mysqli   =   new \mysqli(   
            $parameters['server'], 
            $parameters['user'], 
            $parameters['password'], 
            $parameters['database'], 
            $parameters['port']? $parameters['port']: null
        );

        if( !empty($parameters['charset']) ){
            $this->mysqli->set_charset( $parameters['charset'] );
        }
    }
    
    function __destruct() {
        $this->mysqli->close();
    }
    
    function fetchQuery( string $query, array $bindParams=[] )
    {
        if( empty($bindParams) ){
            $result = $this->mysqli->query( $query );
        }
        else 
        {
            $statementPreparation   = $this->statementPreparation( $query, $bindParams );
            $stmt                   = $this->mysqli->prepare( $statementPreparation['query'] );
            
            $stmt->bind_param( 
                self::getMysqliParamsType($statementPreparation['bindParams']), 
                ...$statementPreparation['bindParams'] 
            );
            
            $stmt->execute();
            $result = $stmt->get_result();
            $stmt->close();
        }
        
        if( !$result ){
            return false;
        }
        
        if( $result->num_rows !== 1 ){
            $return = $result->num_rows;
        }
        else {
            $return = $result->fetch_assoc();
        }
        
        $result->free();        
        return $return;
    }
    
    function selectQuery( string $query, array $bindParams=[] )
    {
        if( empty($bindParams) ){
            $result = $this->mysqli->query( $query );
        }
        else 
        {
            $statementPreparation   = $this->statementPreparation( $query, $bindParams );            
            $stmt                   = $this->mysqli->prepare( $statementPreparation['query'] );
            
            $stmt->bind_param( 
                self::getMysqliParamsType($statementPreparation['bindParams']), 
                ...$statementPreparation['bindParams'] 
            );
            
            $stmt->execute();
            $result = $stmt->get_result();
            $stmt->close();
        }
        
        if( !$result ){
            return false;
        }
        
        if( $result->num_rows == 0 ){
            return [];
        }
        
        $rows = [];
        while( $row = $result->fetch_assoc() ){
            $rows[] = $row;
        }
        
        $result->free();
        return $rows;
    }    

    private function statementPreparation( string $query, array $bindParams=[] ) 
    {
        $mysqlQuery             = "";
        $mysqlBindParams        = [];
        $mysqlBindParamsOrder   = [];

        $offset = 0;
        $pos    = strpos( $query, ':' );
        while( $pos !== false )
        {
            $matchKey = "";
            foreach( array_keys($bindParams) as $key ){
                if( str_starts_with(substr( $query, $pos + 1 ), $key) && strlen($key) > strlen($matchKey) ){
                    $matchKey = $key;
                }
            }

            $mysqlQuery             .=  substr($query, $offset, ($pos - $offset) ).'?';
            $offset                 =   $pos + strlen($matchKey) + 1;
            $mysqlBindParams[]      =   $bindParams[ $matchKey ];
            $mysqlBindParamsOrder[] =   $matchKey;
            $pos                    =   strpos( $query, ':', $offset );
        }
        $mysqlQuery .= substr($query, $offset);
        
        return [
            'query'             => $mysqlQuery,
            'bindParams'        => $mysqlBindParams,
            'bindParamsOrder'   => $mysqlBindParamsOrder,
        ];
    }

    
    private static function getMysqliParamsType($params) 
    {
        $returnTypeString = "";
        foreach( $params as $param ){
            if( is_int($param) ){
                $returnTypeString .= "i";
            }
            elseif( is_numeric($param) ){
                $returnTypeString .= "d";
            }
            else {
                $returnTypeString .= "s"; 
            }
        }
        
        return $returnTypeString;
    }    
    
    function insertQuery( string $query, array $bindParams=[], $multiple=false )
    {
        if( empty($bindParams) )
        {
            $result = $this->mysqli->query( $query );            
            return $result? $this->mysqli->insert_id: false;
        }
        
        $statementPreparation   = $this->statementPreparation( $query, $multiple? $bindParams[0]: $bindParams );
        $stmt                   = $this->mysqli->prepare( $statementPreparation['query'] );
        
        if( !$multiple )
        {
            $stmt->bind_param( 
                self::getMysqliParamsType($statementPreparation['bindParams']), 
                ...$statementPreparation['bindParams'] 
            );
            
            $execute    = $stmt->execute();
            $insertId   = $stmt->insert_id;
            $stmt->close();
            
            return $execute? $insertId: false;
        }
        
        $return             = [];
        $bindParamsValues   = $statementPreparation['bindParamsOrder'];
        
        $stmt->bind_param( 
            self::getMysqliParamsType($statementPreparation['bindParams']), 
            ...$bindParamsValues 
        );
        
        foreach( $bindParams as $bindParamsItem )
        {
            $i = 0;
            foreach( $statementPreparation['bindParamsOrder'] as $key )
            {
                $bindParamsValues[$i] = $bindParamsItem[ $key ];
                $i++;
            }
            
            $return[] = $stmt->execute()? $stmt->insert_id: false;
        }
        
        $stmt->close();        
        return $return;
    }
    
    function query( string $query, array $bindParams=[], $multiple=false )
    {
        if( empty($bindParams) )
        {
            $result = $this->mysqli->query( $query );
            return $result? $this->mysqli->affected_rows: false;
        }
        
        $statementPreparation   = $this->statementPreparation( $query, $multiple? $bindParams[0]: $bindParams );
        $stmt                   = $this->mysqli->prepare( $statementPreparation['query'] );
        
        if( !$multiple )
        {
            $stmt->bind_param( 
                self::getMysqliParamsType($statementPreparation['bindParams']), 
                ...$statementPreparation['bindParams'] 
            );
            
            $execute        = $stmt->execute();
            $affectedRows   = $stmt->affected_rows;
            $stmt->close();
            
            return $execute? $affectedRows: false;
        }
        
        $affectedRows       = 0;
        $bindParamsValues   = $statementPreparation['bindParamsOrder'];
        
        $stmt->bind_param( 
            self::getMysqliParamsType($statementPreparation['bindParams']), 
            ...$bindParamsValues 
        );
        
        foreach( $bindParams as $bindParamsItem )
        {
            $i = 0;
            foreach( $statementPreparation['bindParamsOrder'] as $key )
            {
                $bindParamsValues[$i] = $bindParamsItem[ $key ];
                $i++;
            }
            
            if( !$stmt->execute() )
            {
                $stmt->close();
                return false;
            }
            
            $affectedRows += $stmt->affected_rows;
        }
        
        $stmt->close();        
        return $affectedRows;
    }
    
    function escape_string( string $string ): string
    {
        return $this->mysqli->real_escape_string( $string );
    }    
}