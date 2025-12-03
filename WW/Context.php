<?php
namespace WW;

use WW\Trait\ShortcutAccessTrait;

/**
 * Layout class that handle display
 * 
 * @author Jean2Grom
 */
class Context 
{
    use ShortcutAccessTrait;

    const DEFAULT_FILE  = "default";    
    const DIR           = "context";
    
    const DISPLAY_DIR          = "display/context";
    
    const IMAGES_SUBFOLDER          = "assets/images";
    const JS_SUBFOLDER              = "assets/js";
    const CSS_SUBFOLDER             = "assets/css";
    const FONTS_SUBFOLDER           = "assets/fonts";
    
    const CSS_FILE_DISPLAY          = "css-file.php";
    const JS_FILE_DISPLAY           = "js-file.php";
    const IMAGE_FILE_DISPLAY        = "image-file.php";
    const FAVICON_FILE_DISPLAY      = "context/favicon-file.php";
    
    public $name;
    public $execFile;
    public $website;
    

    public ?string $displayFile         = null;
    public ?string $displayFileConf     = null;
    public bool $displayFileOnExecution = false;

    private $css    = [];
    private $js     = [];
    private $jsLib  = [];
    
    private $customVars  = [];
    
    /** 
     * WoodWiccan container class to allow whole access to Kernel
     * @var WoodWiccan
     */
    public WoodWiccan $ww;
    
    function __construct( Website $website, ?string $initialContext=null )
    {
        $this->website  = $website;
        $this->ww       = $this->website->ww;
        
        $this->name     = $initialContext ?? self::DEFAULT_FILE;
        
        if( strcasecmp(substr( $this->name, -4), ".php") == 0 ){
             $this->name = substr( $this->name, 0, -4);
        }
        
        if( empty($this->name) ){
            $this->ww->log->error("Context implemented with empty initilialisation");
        }
    }
    
    function set( string $context )
    {
        if( strcasecmp(substr($context, -4), ".php") == 0 ){
            $context = substr($context, 0, -4);
        }
        
        $this->name     = $context;
        
        if( empty($this->name) ){
            $this->ww->log->error("Context has been set with empty value");
        }
        
        return $this;
    }
    
    /**
     * search, memorise and return module display file, memorise it 
     * @var ?string $filename forced filename, if null will search module's name based filename
     * @var bool $mandatory, if true will end process (usefull for including file)
     * @return ?string full path file to be displayed
     */
    function displayFile( ?string $filename=null, bool $mandatory=true )
    {
        // If displayed file for conf is already memorised
        if( $this->displayFile && $this->displayFileConf === $filename ){
            return $this->displayFile;
        }
        
        $this->displayFileConf = $filename;

        if( !$filename ){
            $filename = $this->name.".php";
        }
        elseif( strcasecmp(substr($filename, -4), ".php") != 0 ){
            $filename .=  ".php";
        }

        $this->displayFile = $this->ww->website->getFilePath( self::DISPLAY_DIR."/".$filename );
        
        if( !$this->displayFile ){
            $this->ww->log->error("Can't get view file: ".$filename, $mandatory);
        }
        
        $this->ww->debug->toResume("Design file to be included : \"".$this->displayFile."\"", 'CONTEXT');
        return $this->displayFile;
    }
    
    /** 
     * prepare visualisation file to be displayed at module execution
     * @var ?string $filename forced filename, if null will search module's name based filename
     * @return bool
     */
    function display( ?string $filename=null )
    {
        $this->displayFileOnExecution = (bool) $this->displayFile( $filename, false );;
        return $this->displayFileOnExecution;
    }
    
    /**
     * Access internal css file ressource that can be access from web browser,  
     * Resolving fallbacks and returning relative webpath
     * 
     * @param string $cssFile 
     * @return string|null
     */
    function cssSrc( string $cssFile ): ?string {
        return $this->ww->website->getWebPath( self::CSS_SUBFOLDER."/".$cssFile );
    }
    
    function addCssFile( string $cssFile ): bool
    {
        $cssWebPath = $this->cssSrc( $cssFile );
        
        if( $cssWebPath 
            && !in_array($cssWebPath, $this->css)
        ){
            $this->css[] = $cssWebPath;
        }
        else {
            return false;
        }
        
        return true;
    }
    
    function getCssFiles(): array {
        return $this->css;
    }
    
    function jsSrc( string $jsFile ): ?string {
        return $this->ww->website->getWebPath( self::JS_SUBFOLDER."/".$jsFile );
    }
    
    function addJsFile( string $jsFile ): bool
    {
        $jsWebPath = $this->jsSrc( $jsFile );
        
        if( $jsWebPath 
            && !in_array($jsWebPath, $this->js) 
        ){
            $this->js[] = $jsWebPath;
        }
        else {
            return false;
        }
        
        return true;
    }
    
    function getJsFiles(): array {
        return $this->js;
    }
    
    function addJsLibFile( string $jsFile ): bool
    {
        $jsWebPath = $this->ww->website->getWebPath( self::JS_SUBFOLDER."/".$jsFile );
        
        if( $jsWebPath 
            && !in_array($jsWebPath, $this->jsLib) 
        ){
            $this->jsLib[] = $jsWebPath;
        }
        else {
            return false;
        }
        
        return true;
    }
    
    function getJsLibFiles(): array {
        return $this->jsLib;
    }
    
    function imageSrc( string $imageFile ): ?string {
        return $this->ww->website->getWebPath( self::IMAGES_SUBFOLDER."/".$imageFile );
    }
    
    function getImageFile( string $imageFile ): ?string {
        return $this->imageSrc( $imageFile );
    }
    
    function favicon( string $iconFile="favicon.ico" ): void
    {
        $displayFilePath = $this->ww->website->getFilePath( 
            Website::INCLUDE_DIR."/".self::FAVICON_FILE_DISPLAY 
        );

        if( empty($displayFilePath) )
        {
            $this->ww->log->error("Can't get FAVICON file display file");
            return;
        }
        
        $iconSrc = $this->imageSrc( $iconFile );
        if( empty($iconSrc) )
        {
            $this->ww->log->error("Can't get FAVICON file");
            return;
        }
        
        include $displayFilePath;
        
        return;
    }
    
    
    function getFontFile( $filename )
    {
        $fullPath = $this->ww->website->getFilePath(self::FONTS_SUBFOLDER."/".$filename );
        
        if( !$fullPath ){
            return false;
        }
        
        return "/".$fullPath;
    }
    
    /**
     * @param string $filename file to be searched
     * @return ?string full path string if file fond, null if not
     */
    function getIncludeViewFile( string $filename ): ?string
    {
        $fullPath = $this->ww->website->getFilePath( Website::INCLUDE_DIR."/".$filename ) 
                        ?? $this->ww->website->getFilePath( $filename );
        
        if( !$fullPath )
        {
            $this->ww->log->error("CONTEXT Ressource view file to be Included: \"".$filename."\" not found", 'CONTEXT');
            return null;
        }
        
        $this->ww->debug->toResume("Ressource view file to be Included: \"".$fullPath."\"", 'CONTEXT');
        return $fullPath;
    }
    
    function include( $filename, ?array $params=null ): void
    {
        $fullPath = $this->getIncludeViewFile($filename);

        if( !$fullPath ){
            return;
        }

        foreach( $params ?? [] as $includedFunctionParamName => $includedFunctionParamValue ){
            $$includedFunctionParamName = $includedFunctionParamValue;
        }

        include $fullPath;
        return;
    }

    function execute()
    {
        $this->execFile = $this->website->getFilePath( self::DIR."/". $this->name.".php" );
        
        if( !$this->execFile )
        {
            $this->ww->debug->toResume("Context File: \"". $this->name."\" not found, searching for ".self::DEFAULT_FILE." file", 'CONTEXT');
            $this->execFile = $this->website->getFilePath( self::DIR."/".self::DEFAULT_FILE.".php" );
        }
        
        if( !$this->execFile ){
            $this->ww->log->error("Context File: ". $this->name." not found", true);
        }        
        
        $this->ww->debug->toResume("Executing file: \"".$this->execFile."\"", 'CONTEXT');
        
        include $this->execFile;
        if( $this->displayFileOnExecution ){
            include $this->displayFile();
        }
        
        return $this;
    }
    
    function addVar( string $name, mixed $value ): void {
        $this->customVars[ $name ] = $value;
    }
    
    function addArrayItems( string $arrayName, array $values ): void {
        $this->customVars[ $arrayName ] = array_replace($this->customVars[ $arrayName ] ?? [], $values);
    }
    
    function getVar( string $name ): mixed {
        return $this->customVars[ $name ] ?? null;
    }
    
    function __get( string $name ): mixed {
        return $this->getVar($name);
    }
}