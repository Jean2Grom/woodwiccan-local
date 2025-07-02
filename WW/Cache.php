<?php
namespace WW;

/**
 * Class handeling Cache files and access
 * 
 * @author Jean2Grom
 */
class Cache 
{
    const DEFAULT_DIRECTORY     = "cache";
    const DEFAULT_DURATION      = 86400;    // 24h
    const DEFAULT_UNIT          = "s";      // 24h
    
    private string $dir;
    public $createFolderRights;
    public $defaultUnit;
    public $defaultDuration;
    public $folders = [];
    
    /** 
     * WoodWiccan container class to allow whole access to Kernel
     * @var WoodWiccan
     */
    public WoodWiccan $ww;
    
    function __construct( WoodWiccan $ww )
    {
        $this->ww = $ww;
        
        $this->createFolderRights   = $this->ww->configuration->read('cache','createFolderRights') 
                                        ?? $this->ww->configuration->createFolderRights();
        $this->dir                  = $this->ww->configuration->read('cache','directory') ?? self::DEFAULT_DIRECTORY;
        $this->defaultUnit          = $this->ww->configuration->read('cache','durationUnit') ?? self::DEFAULT_UNIT;
        $this->defaultDuration      = self::getDuration($this->ww->configuration->read('cache','duration') ?? self::DEFAULT_DURATION, $this->defaultUnit);
        
        foreach( $this->ww->configuration->read('cache','folders') as $cacheConf => $cacheData ){
            $this->folders[ $cacheConf ] = [
                'directory' =>  $cacheData['directory'] ?? $cacheConf,
                'duration'  =>  self::getDuration($cacheData['duration'] ?? $this->defaultDuration, $cacheData['durationUnit'] ?? $this->defaultUnit),
            ];
        }
    }
    
    private static function getDuration( mixed $value, string $unit )
    {
        if( $value === '*' ){
            return '*';
        }
        
        switch( $unit )
        {
            case "weeks":
            case "week":
            case "w":
                $multiplier = 604800;
            break;
            
            case "days":
            case "day":
            case "d":
                $multiplier = 86400;
            break;
            
            case "hours":
            case "hour":
            case "H":
            case "h":
                $multiplier = 3600;
            break;
            
            case "minutes":
            case "minute":
            case "min":
            case "i":
                $multiplier = 60;
            break;
            
            case "s":
            default :
                $multiplier = 1;
            break;
        }
        
        return floor( $value * $multiplier );
    }
    
    function read( string $folder, string $filebasename ): mixed
    {
        $cacheFile  = $this->get( $folder, $filebasename );
        if( !$cacheFile  = $this->get( $folder, $filebasename ) ){
            return null;
        }

        if( !$jsonString = file_get_contents($cacheFile) ){
            return null;
        }

        if( !$jsonData   = json_decode($jsonString, true) ){
            return null;
        }

        return $jsonData['cached'] ?? null;
    }    
    
    function get( string $folder, string $filebasename ): mixed
    {
        $cacheFolder = $this->dir.'/';
        
        if( !isset($this->folders[ $folder ]) )
        {
            $cacheFolder    .=  $folder;
            $cacheDuration  =   $this->defaultDuration;
        }
        else 
        {
            $cacheFolder    .=  $this->folders[ $folder ]['directory'];
            $cacheDuration  =   $this->folders[ $folder ]['duration'];
        }
        
        if( !is_dir($cacheFolder) 
            && !mkdir($cacheFolder, octdec($this->createFolderRights), true)
        ){
            $this->ww->log->error("Can't create cache folder : ".$folder);
            return false;
        }
        
        $filename = $cacheFolder.'/'.$filebasename.".json";
        
        if( file_exists($filename) )
        {
            if( $cacheDuration == '*' 
                || (time() - filemtime($filename)) < (int) $cacheDuration ){
                return $filename;
            }
            
            unlink($filename);  
        }

        return false;
    }
    
    function delete( string $folder, string $filebasename ): bool
    {
        $cacheFolder = $this->dir.'/';
        
        if( !isset($this->folders[ $folder ]) ){
            $cacheFolder    .=  $folder;
        }
        else {
            $cacheFolder    .=  $this->folders[ $folder ]['directory'];
        }
        
        if( !is_dir($cacheFolder) ){
            $this->ww->log->error("Trying to delete ressource under uncreated folder : ".$folder);
            return false;
        }
        
        $filename = $cacheFolder.'/'.$filebasename.".json";
        
        if( file_exists($filename) ){
            unlink($filename);
        }
        
        return true;
    }
    
    function create( string $folder, string $filebasename, mixed $value ): mixed
    {
        $cacheFolder = $this->dir.'/';
        
        if( !isset($this->folders[ $folder ]) ){
            $cacheFolder    .=  $folder;
        }
        else {
            $cacheFolder    .=  $this->folders[ $folder ]['directory'];
        }
        
        if( !is_dir($cacheFolder) 
            && !mkdir($cacheFolder,  octdec($this->createFolderRights), true)
        ){
            $this->ww->log->error("Can't create cache folder : ".$folder);
            return false;
        }
        
        // Writing cache policies files (based on profile)
        $filename = $cacheFolder."/".$filebasename.".json";
        
        if( file_exists($filename) ){
            unlink($filename);
        }
        
        $cacheFileFP = fopen( $filename, 'a');

        fwrite( $cacheFileFP, json_encode([ 'cached' => $value ], JSON_PRETTY_PRINT) );
        fclose( $cacheFileFP );

        return $filename;
    }
    
    function reset(): bool
    {
        if( !is_dir($this->dir) ){
            return false;            
        }
        
        return $this->deleteFolder( $this->dir );
    }
    
    private function deleteFolder( string $folder  ): bool
    {
        if( !is_dir($folder) ){
            return false;
        }
        
        $files = array_diff( scandir($folder), ['.','..'] );
        
        foreach( $files as $file ){
            (is_dir("$folder/$file")) ? $this->deleteFolder("$folder/$file") : unlink("$folder/$file");
        }
        
        return rmdir($folder);
    }

}
