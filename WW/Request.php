<?php
namespace WW;

/** 
 * Class handeling HTTP Request
 * 
 * @author Jean2Grom
 */
class Request
{
    const DEFAULT_SITE      = "blank";
    
    public $method;
    public $protocoleName;
    public $protocole;
    public $https;
    public $host;
    public $port;
    public $uri;
    public $path;
    public $queryString;
    public $requesterIpAddress;
    public $access;
    
    /** 
     * WoodWiccan container class to allow whole access to Kernel
     * @var WoodWiccan
     */
    public WoodWiccan $ww;
    
    function __construct( WoodWiccan $ww )
    {
        $this->ww = $ww;
        
        $this->method               = $_SERVER["REQUEST_METHOD"] ?? "GET";
        $this->protocoleName        = $_SERVER["SERVER_PROTOCOL"] ?? false;
        $this->https                = $_SERVER["HTTPS"] ?? false;
        $this->protocole            = $_SERVER["HTTP_X_FORWARDED_PROTO"] ?? false;
        $this->host                 = $_SERVER["HTTP_HOST"];
        $this->port                 = $_SERVER["SERVER_PORT"] ?? '';
        $this->uri                  = $_SERVER["SCRIPT_URI"] ?? false;
        $this->path                 = $_SERVER["REQUEST_URI"] ?? $_SERVER["SCRIPT_URL"] ?? $_SERVER["PATH_INFO"] ?? "/";
        $this->queryString          = $_SERVER["QUERY_STRING"] ?? "";
        $this->requesterIpAddress   = self::getRequesterIpAddress();

        if( !$this->protocole && isset($this->https) && $this->https == "on" ){
            $this->protocole = "https";
        }
        elseif( !$this->protocole ){
            $this->protocole = "http";
        }
        
        if( !$this->uri ){
           $this->uri =  $this->protocole."://".$this->host.$this->path;
        }
    }
    
    /**
     * Read request param
     * 
     * @var string $name param name
     * @var ?string $method request method where param has to be read (ie GET, POST...), if not set take request default method
     * @var int $filter add a native php input filter
     * @var array|int $options added specifications for native php filter input
     */
    function param( string $name, ?string $method=null, int $filter=FILTER_DEFAULT, array|int $options=0 )
    {
        if( empty($method) ){
            $paramType = $this->method === 'POST'? INPUT_POST: INPUT_GET;
        }
        else {
            $paramType = strtolower($method) == 'post'? INPUT_POST: INPUT_GET;
        }
        
        return filter_input($paramType, $name, $filter, $options);
    }
    
    /**
     * Fetch all inputs 
     * 
     * @var ?string $method request method where param has to be read (ie GET, POST...), if not set take request default method
     * @var array|int $options added specifications for native php filter input
     * @var bool $add_empty add missing keys as NULL to the return value.
     */
    function inputs(?string $method=null, array|int $options=FILTER_DEFAULT, bool $add_empty=true )
    {
        if( empty($method) ){
            $paramType = $this->method === 'POST'? INPUT_POST: INPUT_GET;
        }
        else {
            $paramType = strtolower($method) == 'post'? INPUT_POST: INPUT_GET;
        }
        
        return filter_input_array($paramType, $options, $add_empty);
    }
    
    /**
     * Read request ip adress, return '127.0.0.1' if localhost detected
     */
    static function getRequesterIpAddress()
    {
        if( $ip = filter_input(INPUT_SERVER, 'REMOTE_ADDR', FILTER_VALIDATE_IP) ){
            return $ip;
        }

        if( substr( filter_input(INPUT_SERVER, 'HTTP_HOST'), 0, 9) === 'localhost' ){
            return '127.0.0.1';
        }

        return filter_input(INPUT_SERVER, 'HTTP_HOST', FILTER_VALIDATE_IP);
    }
}
