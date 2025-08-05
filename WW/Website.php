<?php
namespace WW;

/**
 * Class containing website (app) information and aggreging related objects
 * 
 * @author Jean2Grom
 */
class Website 
{
    const SITES_DIR             = "sites";
    const DEFAULT_SITE_DIR      = "sites/default";
    
    const VIEW_DIR              = "view";
    const INCLUDE_VIEW_DIR      = "view/include";

    public $name;
    public $currentAccess;
    public $site;
    
    public $access;
    public $manage;
    public $sitesRestrictions;
    
    public $baseUri;
    public $urlPath;
    public $modulesList;
    private $rootUrl;
    
    public $modules;
    public $status;
    
    public bool $debug;    
    public $extensions;
    
    public $heritages;
    
    public $defaultContext;
    
    /**
     * Layout class that handle display
     * @var Context
     */    
    public Context $context;
    
    /**
     * class that handles witch summoning and modules invocation
     * @var Cairn
     */
    public Cairn $cairn;
    
    /** 
     * WoodWiccan container class to allow whole access to Kernel
     * @var WoodWiccan
     */
    public WoodWiccan $ww;
    
    function __construct( WoodWiccan $ww, string $name, ?string $siteAccess=null )
    {
        $this->ww               = $ww;
        $this->name             = $name;
        
        // Reading non heritable confs publiciables
        $this->access   = $this->ww->configuration->read($this->name, "access");
        $this->manage   = $this->ww->configuration->read($this->name, "manage");
        $this->site     = $this->ww->configuration->read($this->name, "site") ?? $this->name;
        
        if( $this->site !== $this->name ){
            $this->ww->debug->toResume("URL site is acceded by: \"".$this->site."\"", 'WEBSITE');
        }
        
        
        $this->heritages        = $this->ww->configuration->getSiteHeritage( $this->name );
        $this->heritages[]      = "global";
        
        $this->modules              = $this->ww->configuration->readSiteMergedVar('modules', $this) ?? [];
        $witchesConf                = $this->ww->configuration->readSiteMergedVar('witches', $this) ?? [];
        
        $this->debug                = (bool) $this->ww->configuration->readSiteVar('debug', $this);
        $this->status               = $this->ww->configuration->readSiteVar('status', $this);
        $defaultContext             = $this->ww->configuration->readSiteVar('defaultContext', $this);
        if( !empty($defaultContext) ){
            $this->defaultContext       = $defaultContext;
        }
        
        $this->sitesRestrictions    = [ $this->site ];
        foreach( $this->manage ?? [] as $adminisratedSite )
        {
            if( $adminisratedSite == '*' )
            {
                $this->sitesRestrictions = false;
                break;
            }
            
            $this->sitesRestrictions[] = $adminisratedSite;
        }
        
        $this->currentAccess    = $siteAccess ?? array_values($this->access ?? [])[0] ?? '';
        $firstSlashPosition     = strpos($this->currentAccess, '/');
        $this->baseUri          = ($firstSlashPosition !== false)? substr( $this->currentAccess, $firstSlashPosition ): '';
        $this->urlPath          = Tools::urlCleanupString( substr( $this->ww->request->access, strlen($this->currentAccess) ) );
        
        foreach( $this->modules as $moduleName => $moduleConf ){
            foreach( $moduleConf['witches'] ?? [] as $moduleWitchName => $moduleWitchConf ){
                if( empty($witchesConf[ $moduleWitchName ]) )
                {
                    $witchesConf[ $moduleWitchName ] = array_replace_recursive( 
                        $moduleWitchConf, 
                        [ 'module' => $moduleName ] 
                    );
                }
            }
        }
        
        $this->cairn    = new Cairn( $this->ww, $witchesConf, $this );
        $this->context  = new Context( $this, $this->defaultContext );
    }
    
    /**
     * Name reading
     * @return string
     */
    public function __toString(): string {
        return $this->name;
    }


    function get(string $name): mixed {
        return $this->ww->configuration->readSiteVar($name, $this);
    }
    
    function getCairn(): Cairn {
        return $this->cairn;
    }
    
    function getUrlSearchParameters()
    {
        return [
            'site'  => $this->site,
            'url'   => $this->urlPath,
        ];
    }
    
    function display()
    {
        $this->context->display();
        
        return $this;
    }

    function getViewFilePath( string $filename ): ?string {
        return $this->getFilePath( self::VIEW_DIR."/".$filename );
    }
    
    function getIncludeViewFilePath( string $filename ): ?string {
        return $this->getFilePath( self::INCLUDE_VIEW_DIR."/".$filename );
    }
    
    function getFilePath( string $filename ): ?string
    {
        // Looking in this site
        $filePath = self::SITES_DIR.'/'.$this->name.'/'.$filename;
        
        if( is_file($filePath) ){
            return $filePath;
        }
        
        // Looking in herited sites
        foreach( $this->heritages as $heritedSite )
        {
            $filePath = self::SITES_DIR.'/'.$heritedSite.'/'.$filename;

            if( file_exists($filePath) ){
                return $filePath;
            }
        }
        
        // Looking in extension files
        /*
        $extensions = $this->ww->configuration->readSiteMergedVar('extensions', $this);
        if( is_array($extensions) ){
            foreach( $extensions as $extension )
            {
                $filePath = "extensions/".$extension."/".$filename;
                
                if( file_exists($filePath) ){
                    return $filePath;
                }
            }
        }
        */
        
        // Looking in default site
        $filePath = self::DEFAULT_SITE_DIR.'/'.$filename;
        
        if( is_file($filePath) ){
            return $filePath;
        }
        
        // Looking in system files
        $filePath = "system/".$filename;
        
        if( is_file($filePath) ){
            return $filePath;
        }
        
        return null;
    }
    
    
    function getWebPath( string $filename ): ?string
    {
        $filePath = $this->ww->website->getFilePath( $filename );
        
        if( $filePath ){
            return '/'.$filePath;
        }
        
        return null;
    }    
    
    function listModules()
    {
        if( !empty($this->modulesList) ){
            return $this->modulesList;
        }
        
        $modulesList = [];
        foreach( $this->heritages as $siteItem )
        {
            if( $siteItem == "global" ){
                $dir = self::DEFAULT_SITE_DIR;
            } 
            else {
                $dir = self::SITES_DIR.'/'.$siteItem;
            }
            
            if( is_dir($dir) ){
                $modulesList = array_merge($modulesList, $this->recursiveRead( $dir, $dir ));
            }
        }
        
        $modulesList = array_unique($modulesList);
        
        sort($modulesList);
        
        $this->modulesList = $modulesList;
        
        return $this->modulesList;
    }
    
    private function recursiveRead( $dir, $prefix )
    {
        $modulesList = [];
        
        $dirContentArray = array_diff( scandir($dir), array('..', '.') );
        
        foreach( $dirContentArray as $dirContent ){
            if( is_dir($dir.'/'.$dirContent) && $dirContent !== self::VIEW_DIR ){

                $modulesList = array_merge( $modulesList, $this->recursiveRead( $dir.'/'.$dirContent, $prefix ) );
            }
            else 
            {
                $moduleName = $dir.'/'.$dirContent;
                if( substr($moduleName, 0, strlen($prefix)) == $prefix ){
                    $moduleName = substr($moduleName, strlen($prefix) + 1);
                }
                
                $moduleName = substr($moduleName, 0, strripos($moduleName, ".php") );

                if( $moduleName ){
                    $modulesList[] = $moduleName;
                }
            }
        }

        return $modulesList;
    }
    
    function listContexts()
    {
        $contextsList = [];
        
        foreach( $this->heritages as $siteItem )
        {
            if( $siteItem == "global" ){
                $dir = "sites/default/".Context::DIR;
            }
            else {
                $dir = "sites/".$siteItem."/".Context::DIR;
            }
            
            if( is_dir($dir) ){
                foreach( array_diff(scandir( $dir ), ['..', '.'] ) as $contextFile )
                {
                    $contextName = substr($contextFile, 0, strripos($contextFile, ".php"));
                    
                    if( !in_array($contextName, $contextsList) ){
                        $contextsList[] = $contextName;
                    }
                }
            }
        }
        
        sort($contextsList);
        
        return $contextsList;
    }
    
    function setRootUrl( string $rootUrl )
    {
        $this->rootUrl = $rootUrl;
        
        return $this;
    }
    
    function getRootUrl()
    {
        if( !$this->rootUrl ){
            $this->rootUrl = ($this->baseUri)? $this->baseUri: '/';
        }
        return $this->rootUrl;
    }
    
    function getFullUrl( string $urlPath='', ?array $urlParams=null, ?Request $request=null ): string
    {
        if( !$request ){
            $request = $this->ww->request;
        }
        
        $fullUrl    =   $request->protocole.'://';
        if( strstr($this->currentAccess, '/') ){
            $fullUrl .= dirname($this->currentAccess);
        }
        else {
            $fullUrl .= $this->currentAccess;
        }
        
        if( substr($fullUrl, -( strlen($request->port) + 1 )) !== ':'.$request->port ){
            $fullUrl .= ':'.$request->port;
        }
        
        $fullUrl    .=  $this->getUrl( $urlPath, $urlParams );
        
        return $fullUrl;
    }
    
    function getUrl( string $urlPath='', ?array $urlParams=null ): string
    {
        $url    = $this->baseUri;
        if( !empty($urlPath) && !str_starts_with($urlPath, '/') ){
            $url .= '/';
        }
        $url    .=  $urlPath;
        
        if( empty($url) ){
            $url = '/';
        }
        
        if( !empty($urlParams) ){
            $url .= '?'.http_build_query($urlParams);
        }

        return $url;
    }

    function include( $filename, ?array $params=null ): void
    {
        foreach( $params ?? [] as $name => $value ){
            $$name = $value;
        }

        $fullPath = $this->getFilePath( self::INCLUDE_VIEW_DIR."/".$filename);

        if( !$fullPath ){
            $this->ww->log->error( "Ressource file to be Included: \"".$filename."\" not found", false);
        }
        else
        {
            $this->ww->debug->toResume("Ressource view file to be Included: \"".$fullPath."\"", 'INCLUDE');
            include $fullPath;
        }

        return;
    }

}
