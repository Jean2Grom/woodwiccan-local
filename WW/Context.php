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

    const DIR                   = "context";
    const DISPLAY_DIR           = "display/context";
    
    const IMAGES_SUBFOLDER          = "assets/images";
    const JS_SUBFOLDER              = "assets/js";
    const CSS_SUBFOLDER             = "assets/css";
    const FONTS_SUBFOLDER           = "assets/fonts";
    
    const DOM_FILE_DISPLAY          = "dom.php";
    
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
        
        $this->name     = $initialContext ?? "default";
        
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

        if( !$context ){
            $this->ww->log->error("Cannot set context with empty value");
        }
        else {
            $this->name     = $context;
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
    
    function css(): void
    {
        $displayFilePath = $this->ww->website->getFilePath( 
            Website::INCLUDE_DIR."/".self::DOM_FILE_DISPLAY
        );
        
        if( empty($displayFilePath) )
        {
            $this->ww->log->error("Can't get DOM display file");
            return;
        }

        foreach( $this->getCssFiles() as $cssFile )
        {
            $dom        = "link";
            $attributes = [ 
                'rel'   => "stylesheet", 
                'type'  => "text/css", 
                'href'  => $cssFile, 
            ];
            
            include $displayFilePath;
        }

        return;
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
    
    function js(): void
    {
        $displayFilePath = $this->ww->website->getFilePath( 
            Website::INCLUDE_DIR."/".self::DOM_FILE_DISPLAY
        );
        
        if( empty($displayFilePath) )
        {
            $this->ww->log->error("Can't get DOM display file");
            return;
        }

        foreach( $this->getJsFiles() as $jsFile )
        {
            $dom        = "script";
            $attributes = [ 'src'  => $jsFile ];
            
            include $displayFilePath;
        }

        return;
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

    function jsLibs(): void
    {
        $displayFilePath = $this->ww->website->getFilePath( 
            Website::INCLUDE_DIR."/".self::DOM_FILE_DISPLAY
        );
        
        if( empty($displayFilePath) )
        {
            $this->ww->log->error("Can't get DOM display file");
            return;
        }

        foreach( $this->getJsLibFiles() as $jsLibFile )
        {
            $dom        = "script";
            $attributes = [ 'src' => $jsLibFile ];
            
            include $displayFilePath;
        }

        return;
    }

    function image( string $imageFile ): void 
    {
        $displayFilePath = $this->ww->website->getFilePath( 
            Website::INCLUDE_DIR."/".self::DOM_FILE_DISPLAY
        );

        if( empty($displayFilePath) )
        {
            $this->ww->log->error("Can't get DOM display file");
            return;
        }
        
        $imageSrc = $this->imageSrc( $imageFile );
        if( !$imageSrc )
        {
            $this->ww->log->error("Can't get IMAGE file");
            return;
        }

        $dom        = "img";
        $attributes = [ 'src' => $imageSrc ];
        
        include $displayFilePath;
        
        return;
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
            Website::INCLUDE_DIR."/".self::DOM_FILE_DISPLAY
        );

        if( empty($displayFilePath) )
        {
            $this->ww->log->error("Can't get DOM display file");
            return;
        }
        
        $imagePath = $this->ww->website->getFilePath(self::IMAGES_SUBFOLDER."/".$iconFile);
        if( !$imagePath )
        {
            $this->ww->log->error("Can't get FAVICON file");
            return;
        }
        
        $dom        = "link";        
        $attributes = [ 
            'rel'   => "icon",
            'type'  => mime_content_type( $imagePath ),
            'href'  => $this->imageSrc( $iconFile ),
        ];
        
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
            $this->ww->debug->toResume("Context File: \"". $this->name."\" not found, searching for default file", 'CONTEXT');
            $this->execFile = $this->website->getFilePath( self::DIR."/default.php" );
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