<?php
namespace WW\Database;

use WW\WoodWiccan;

/**
 * PDO database driver
 * 
 * @author Jean2Grom
 */
class PDO implements DatabaseInterface
{
    public $pdo;
    
    /** 
     * WoodWiccan container class to allow whole access to Kernel
     * @var WoodWiccan
     */
    public WoodWiccan $ww;
    
    function __construct( WoodWiccan $ww, $parameters )
    {
        $this->ww   = $ww;
        
        $dsn    =   $parameters['driver'] ?? "mysql";
        $dsn    .=  ':host='.$parameters['server'];
        $dsn    .=  ';dbname='.$parameters['database'];
        if( $parameters['port'] ){
            $dsn    .=  ';port='.$parameters['port'];            
        }
        if( $parameters['charset'] ){
            $dsn    .=  ';charset='.$parameters['charset'];            
        }
        
        $this->pdo = new \PDO( 
            $dsn,
            $parameters['user'],
            $parameters['password'],
            [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]
        );    
    }
    
    function __destruct() {
        $this->pdo = null;
    }
    
    function cleanupParamKeys( string $queryRaw, array $bindParamsRaw=[] )
    {
        $query      = $queryRaw;
        $bindParams = [];
        foreach( $bindParamsRaw as $keyRaw => $value )
        {
            $key                = str_replace( '-', '__', $keyRaw );
            $query              = str_replace( ':'.$keyRaw, ':'.$key, $query );
            $bindParams[ $key ] = $value;
        }
        
        return [
            'query'         => $query,
            'bindParams'    => $bindParams,
        ];
    }
    
    function fetchQuery( string $queryRaw, array $bindParamsRaw=[] )
    {
        $cleanupParamKeys   = $this->cleanupParamKeys($queryRaw, $bindParamsRaw);
        $query              = $cleanupParamKeys['query'];
        $bindParams         = $cleanupParamKeys['bindParams'];        
        
        if( empty($bindParams) ){
            $stmt = $this->pdo->query( $query );
        }
        else 
        {
            $stmt = $this->pdo->prepare($query);

            foreach( $bindParams as $bindParamsKey => $bindParamsValue ){
                $stmt->bindValue( $bindParamsKey, $bindParamsValue, self::getParamType($bindParamsValue) );
            }
            
            $stmt->execute();
        }
        
        $count = $stmt->rowCount();
        
        if( $count !== 1 ){
            return $count;
        }
        
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
    
    function selectQuery( string $queryRaw, array $bindParamsRaw=[] )
    {
        $cleanupParamKeys   = $this->cleanupParamKeys($queryRaw, $bindParamsRaw);
        $query              = $cleanupParamKeys['query'];
        $bindParams         = $cleanupParamKeys['bindParams'];
        
        if( empty($bindParams) ){
            $stmt = $this->pdo->query( $query );
        }
        else 
        {
            $stmt = $this->pdo->prepare($query);

            foreach( $bindParams as $bindParamsKey => $bindParamsValue ){
                $stmt->bindValue( $bindParamsKey, $bindParamsValue, self::getParamType($bindParamsValue) );
            }
            
            $stmt->execute();
        }
                
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    
    function insertQuery( string $queryRaw, array $bindParamsRaw=[], $multiple=false )
    {
        $cleanupParamKeys   = $this->cleanupParamKeys($queryRaw, $bindParamsRaw);
        $query              = $cleanupParamKeys['query'];
        $bindParams         = $cleanupParamKeys['bindParams'];        
        
        if( empty($bindParams) )
        {
            $result = $this->pdo->query( $query );            
            return $result? $this->pdo->lastInsertId(): false;
        }
        
        $stmt = $this->pdo->prepare($query);
        
        if( !$multiple )
        {
            foreach( $bindParams as $bindParamsKey => $bindParamsValue ){
                $stmt->bindValue( $bindParamsKey, $bindParamsValue, self::getParamType($bindParamsValue) );
            }
            
            return $stmt->execute()? $this->pdo->lastInsertId(): false;
        }
        
        $return             = [];
        $bindParamsKeys     = array_keys( array_values($bindParams)[0] );
        $bindParamsValues   = array_flip( $bindParamsKeys );
        
        foreach( $bindParamsKeys as $key ){
            $stmt->bindParam( $key, $bindParamsValues[ $key ], self::getParamType(array_values($bindParams)[0][ $key ]) ); 
        }
        
        foreach( $bindParams as $bindParamsItem )
        {
            foreach( $bindParamsKeys as $key ){
                $bindParamsValues[ $key ] = $bindParamsItem[ $key ];
            }
            
            $return[] = $stmt->execute()? $this->pdo->lastInsertId(): false;
        }
        
        return $return;
    }
    
    function query( string $queryRaw, array $bindParamsRaw=[], $multiple=false )
    {
        $cleanupParamKeys   = $this->cleanupParamKeys($queryRaw, $bindParamsRaw);
        $query              = $cleanupParamKeys['query'];
        $bindParams         = $cleanupParamKeys['bindParams'];
        
        if( empty($bindParams) )
        {
            $result = $this->pdo->query( $query );
            return $result? $result->rowCount(): false;
        }
        
        $stmt = $this->pdo->prepare($query);
        
        if( !$multiple )
        {
            foreach( $bindParams as $bindParamsKey => $bindParamsValue ){
                $stmt->bindValue( $bindParamsKey, $bindParamsValue, self::getParamType($bindParamsValue) );
            }
            
            return $stmt->execute()? $stmt->rowCount(): false;
        }
        
        $affectedRows       = 0;
        $bindParamsKeys     = array_keys( array_values($bindParams)[0] );
        $bindParamsValues   = array_flip( $bindParamsKeys );
        
        foreach( $bindParamsKeys as $key ){
            $stmt->bindParam( $key, $bindParamsValues[ $key ], self::getParamType(array_values($bindParams)[0][ $key ]) ); 
        }
        
        foreach( $bindParams as $bindParamsItem )
        {
            foreach( $bindParamsKeys as $key ){
                $bindParamsValues[ $key ] = $bindParamsItem[ $key ];
            }
            
            if( !$stmt->execute() ){
                return false;
            }
            
            $affectedRows += $stmt->rowCount();
        }
        
        return $affectedRows;
    }    
    
    private static function getParamType($param) 
    {
        if( is_int($param) ){
            return \PDO::PARAM_INT;
        }
        elseif( is_null($param) || $param === 'NULL' || $param === 'null' ){
            return \PDO::PARAM_NULL;
        }
        
        return \PDO::PARAM_STR;
    }    
    
    function escape_string( string $string ): string
    {
        return htmlspecialchars($string);
    }    
}