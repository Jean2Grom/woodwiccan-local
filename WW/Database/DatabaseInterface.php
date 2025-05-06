<?php
namespace WW\Database;

/**
 * Interface for handler class for database drivers
 * 
 * @author Jean2Grom
 */
interface DatabaseInterface 
{
    public function fetchQuery( string $query, array $bindParams=[] );
    public function selectQuery( string $query, array $bindParams=[] );
    public function insertQuery( string $query, array $bindParams=[], $multiple=false );
    public function query( string $query, array $bindParams=[], $multiple=false );
    public function escape_string( string $string ): string;    
}