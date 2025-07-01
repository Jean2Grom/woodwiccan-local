<?php 
namespace WW;

use WW\Cauldron\Recipe;
use WW\Handler\RecipeHandler;
/**
 * Class handeling configuration files 
 * 
 * @author Jean2Grom
 */
class Configuration 
{
    const WW_ENV_VAR_PREFIX         = 'WW_';    
    const WW_ENV_VAR_FILE_SUFFIX    = '_FILE';    
    const DEFAULT_DIRECTORY         = "configuration";
    const CONFIG_FILE               = 'configuration.json';
    const SITES_FILE                = 'sites.json';
    const RECIPES_DIR               = "configuration/cauldron";

    const DEFAULT_STORAGE       = "files";
    const DEFAULT_DIR_RIGHTS    = "755";    // read/execute for all, write limited to self

    public string $dir;
    public $configuration  = [];
    public $sites          = [];

    /** @var ?Recipe[] */
    public $recipes = null;
    public $createFolderRights;
    
    /** 
     * WoodWiccan container class to allow whole access to Kernel
     * @var WoodWiccan
     */
    public WoodWiccan $ww;
    
    /**
     * @param WoodWiccan $ww : container
     * @param string $configurationDirectory : path to configuration files directory
     * @param boolean $mandatory : if set to true, die process if configuration files not found
     */
    function __construct( WoodWiccan $ww, ?string $configurationDirectory=null, bool $mandatory=true )
    {
        $this->ww = $ww;
        
        if( $configurationDirectory ){   
            $this->dir = $configurationDirectory;
        }
        else {
            $this->dir = self::DEFAULT_DIRECTORY;
        }
        
        if( $mandatory
            &&  ( !file_exists($this->dir.'/'.self::CONFIG_FILE) 
                || !file_exists($this->dir.'/'.self::SITES_FILE)  )
        ){
            die("Configuration files unreachable");
        }
        
        $rawConfiguration   = file_get_contents( $this->dir.'/'.self::CONFIG_FILE );
        $rawSites           = file_get_contents( $this->dir.'/'.self::SITES_FILE );
        
        $wwEnvVars = [];
        foreach( getenv() as $envVarName => $envVarValue ){
            if( str_starts_with($envVarName, self::WW_ENV_VAR_PREFIX) ){
                if( str_ends_with($envVarName, self::WW_ENV_VAR_FILE_SUFFIX) 
                    && is_file($envVarValue) 
                ){
                    $wwEnvVars[
                        '<'.substr($envVarName, 0, -strlen(self::WW_ENV_VAR_FILE_SUFFIX)).'>'
                    ] = file_get_contents($envVarValue);
                }
                else {
                    $wwEnvVars['<'.$envVarName.'>'] = $envVarValue;
                }
            }
        }
        
        $rawConfigurationEnvVarIntegrated   = str_replace( array_keys($wwEnvVars), array_values($wwEnvVars), $rawConfiguration );
        $rawSitesEnvVarIntegrated           = str_replace( array_keys($wwEnvVars), array_values($wwEnvVars), $rawSites );
        
        $this->configuration    = json_decode($rawConfigurationEnvVarIntegrated, true);
        $this->sites            = json_decode($rawSitesEnvVarIntegrated, true);

        if( $mandatory
            &&  ( empty($this->configuration) 
                || empty($this->sites)  )
        ){
            die("Configuration files misformatted");
        }
    }
    
    function read( string $section, ?string $variable=null)
    {
        if( $variable && isset($this->configuration[ $section ][ $variable ]) ){
            return $this->configuration[ $section ][ $variable ];
        }
        elseif( $variable && isset($this->sites[ $section ][ $variable ]) ){
            return $this->sites[ $section ][ $variable ];
        }
        elseif( !$variable && isset($this->configuration[ $section ]) ){
            return $this->configuration[ $section ];
        }
        elseif( !$variable && isset($this->sites[ $section ]) ){
            return $this->sites[ $section ];
        }
        
        return null;
    }
    
    function readSiteVar( string $variable, ?Website $website=null )
    {
        if( !$website ){
            $website = $this->ww->website;
        }
        
        foreach( $website->siteHeritages as $section )
        {
            $value = $this->read($section, $variable);
            
            if( !is_null($value) ){
                return $value;
            }
        }
        
        return null;
    }
    
    function readSiteMergedVar( string $variable, ?Website $website=null )
    {
        if( !$website ){
            $website = $this->ww->website;
        }
        
        $return = null;
        foreach( array_reverse($website->siteHeritages) as $section )
        {
            $value = $this->read($section, $variable);
            
            if( is_array($value) ){
                $return = array_replace_recursive($return ?? [], $value);
            }
            elseif( !is_null($value) ){
                $return = $value;
            }
        }
        
        return $return;
    }
    
    
    function getSiteAccessMap()
    {
        $map = [];
        foreach( $this->sites as $site => $siteData )
        {
            if( empty($siteData['access']) ){
                continue;
            }
            
            foreach( $siteData['access'] as $siteaccess ){
                $map[ $siteaccess ] = $site;
            }
        }
        
        return $map;
    }
    
    
    /**
     * Reccursive function for reading heritages configuration cascades
     * 
     * @param string $siteName : configuration name of site to ckeck
     * @return array : ordered list of sites that are herited from
     */
    function getSiteHeritage( string $siteName ): array
    {
        $siteHeritages      = $this->read( $siteName, "siteHeritages" );
        
        if( !$siteHeritages ){
            return [ $siteName ];
        }
        
        $return = [ $siteName ];
        foreach( $siteHeritages as $siteHeritagesItem )
        {
            $return[] = $siteHeritagesItem;                
            foreach( $this->getSiteHeritage($siteHeritagesItem) as  $subSiteHeritagesItem ){
                $return[] = $subSiteHeritagesItem;                
            }
        }
        
        return array_unique($return);
    }

    function recipes(): array
    {
        if( $this->recipes ){
            return $this->recipes;
        }

        $rules      = $this->readSiteMergedVar( 'recipes' );
        $whiteList  = $rules['allowed'] ?? '*';
        $blackList  = $rules['forbidden'] ?? null;

        $recipes = [];
        foreach( $this->getFilesRecursive(self::RECIPES_DIR) as $file )
        {
            $recipe = RecipeHandler::createFromFile( $this->ww, $file );

            if( !$recipe ){
                continue;
            }

            $recipes[ $recipe->name ] = $recipe;
        }
        
        RecipeHandler::resolve($recipes);

        $this->recipes = [];
        foreach( $recipes  as $recipe )
        {
            // White list filtering
            if( is_array($whiteList) && !in_array($recipe->name, $whiteList) ){
                continue;
            }
            // Black list filtering
            if( is_array($blackList) && in_array($recipe->name, $blackList) ){
                continue;
            }

            $this->recipes[ $recipe->name ] = $recipe;
        }

        ksort($this->recipes);
        return $this->recipes;
    }

    private function getFilesRecursive( $dir ): array
    {
        $files = [];
        $handle = opendir( $dir );
        while( false !== ($entry = readdir($handle)) ){
            if( in_array($entry, ['.', '..']) ){
                continue;
            }
            elseif( is_dir($dir.'/'.$entry) ){
                $files = array_merge( $files, $this->getFilesRecursive($dir.'/'.$entry) );
            }
            else {
                $files[] = $dir.'/'.$entry;
            }
        }

        return $files;
    }

    function recipe( string $recipeName ): ?Recipe {
        return $this->recipes()[ $recipeName ] ?? null;
    }

    function storage( ?string $name=null, ?Website $website=null ): string 
    {
        $storage = $this->readSiteVar( "storage", $website );
        return $storage[ $name ?? self::DEFAULT_STORAGE ] ?? 
                $storage[ self::DEFAULT_STORAGE ]."/".Tools::cleanupString($name);
    }

    function createFolderRights()
    {
        if( !$this->createFolderRights ){
            $this->createFolderRights = $this->read( 'system', 'createFolderRights' ) 
                                            ?? self::DEFAULT_DIR_RIGHTS;
        }
        
        return $this->createFolderRights;
    }

    /**
     * Create Folder with correct Folder
     * @var string $folder 
     * @return bool 
     */
    function createFolder( string $folder ): bool
    {
        if( !is_dir($folder) ){
            return mkdir( 
                $folder, 
                octdec($this->createFolderRights()), 
                true 
            );
        }
        
        return true;
    }

}
