<?php
namespace WW;

use WW\Handler\UserHandler;
use WW\Website;

/** 
 * Class handeling HTTP Request, 
 * determining URL targeted website and ressource
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
     * Class containing website (app) information and aggreging related objects
     * @var Website 
     */
    public Website $website;
    
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

    function getWebsite()
    {
        if( empty($this->website) )
        {
            // Determinating which site is acceded comparing
            // Configuration and URI
            $parsed_url     = parse_url( strtolower($this->uri ?? '/') );
            $this->access   = $parsed_url["host"].$parsed_url['path'];
            $compareAccess  = $this->compareAccess($this->access );

            // if no match and access has "www" for subomain, try whithout (considered default subdomain)
            if( !$compareAccess['matchedSiteAccess'] && str_starts_with($this->access , "www.") )
            {
                $this->access   = substr($this->access , 4);
                $compareAccess  = $this->compareAccess( $this->access  );
            }
            
            if( !$compareAccess['matchedSiteAccess']   ){   
                $this->ww->log->error("Site access is not in configuration file");
            }
            else {
                $this->ww->debug->toResume("Accessing site: \"".$compareAccess['siteName']."\", with site access: \"".$compareAccess['matchedSiteAccess']."\"", 'SITEACCESS');
            }
            
            $this->website = new Website( $this->ww, $compareAccess['siteName']  , $compareAccess['matchedSiteAccess'] );
        }
        
        return $this->website;
    }

    function getUser()
    {
        $user = new User( $this->ww );

        if( $this->param('action') === 'login' ){
            $user->init( UserHandler::login( 
                $this->ww,
                $this->param('username'), 
                $this->param('password')                 
            ) );
        }

        return $user;
    }

    
    function getFullUrl( string $urlPath='', ?array $urlParams=null, ?Website $website=null )
    {
        if( !$website ){
            $website = $this->website;
        }
        
        return $this->website->getFullUrl($urlPath, $urlParams, $this);
    }
    
    private function compareAccess( $access )
    {
        $haystack           = strtolower( $access );
        $siteName           = $this->ww->configuration->read('system','defaultSite') ?? self::DEFAULT_SITE;
        
        $matchedSiteAccess  = "";
        $matchDegree        = 0;
        foreach( $this->ww->configuration->getSiteAccessMap() as $siteAccess => $site )
        {
            $needle = strtolower( $siteAccess );
            
            if( $haystack === $needle
                || (str_starts_with( $haystack, $needle.'/' ) && strlen( $siteAccess ) > $matchDegree)
            ){
                $matchDegree        = strlen($siteAccess);
                $siteName           = $site;
                $matchedSiteAccess  = $siteAccess;
            }
        }
        
        return [ 'siteName' => $siteName, 'matchedSiteAccess' => $matchedSiteAccess ];
    }
    
    static function getRequesterIpAddress()
    {
        return filter_input( 
            INPUT_SERVER, 
            'REMOTE_ADDR', 
            FILTER_VALIDATE_IP 
        ) ?? 
        ( substr( filter_input(INPUT_SERVER, 'HTTP_HOST'), 0, 9) === 'localhost' )? '127.0.0.1': 
            filter_input(INPUT_SERVER, 'HTTP_HOST', FILTER_VALIDATE_IP);
    }
}
