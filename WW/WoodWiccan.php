<?php
namespace WW;

use WW\DataAccess\CauldronDataAccess;
use WW\DataAccess\WitchDataAccess;

/**
 * WoodWiccan container class to allow whole access to Kernel
 * 
 * @author Jean2Grom
 */
class WoodWiccan 
{
    /** 
     * Class dedicated for debugging WW projects
     * @var Debug 
     */
    public Debug $debug;
    
    /** 
     * Class handeling configuration files 
     * @var Configuration 
     */
    public Configuration $configuration;
    
    /** 
     * Class handeling Log files and process
     * @var Log 
     */
    public Log $log;
    
    /** 
     * Class handeling Database connexions and requesting
     * @var Database 
     */
    public Database $db;
    
    /** 
     * Class handeling Cache files and access
     * @var Cache 
     */
    public Cache $cache;
    
    /** 
     * Class handeling HTTP Request, 
     * determining URL targeted website and ressource
     * @var Request 
     */
    public Request $request;
    
    /** 
     * Class containing website (app) information and aggreging related objects
     * @var Website 
     */
    public Website $website;
    
    /** 
     * Class handeling User information and security access policies
     * @var User 
     */
    public User $user;
    
    /**
     * Class that handles witch summoning and modules invocation
     * @var Cairn
     */
    public Cairn $cairn;
    
    /**
     * Current depth of witches matriarcat arborescence tree
     * @var int 
     */
    public int $depth;
    
    /**
     * Current depth of cauldron arborescence tree
     * @var int 
     */
    public int $cauldronDepth;
    
    
    public function __construct() 
    {
        try {
            $this->debug            =   new Debug( $this ); 
            $this->configuration    =   new Configuration( $this );
            $this->log              =   new Log( $this );
            $this->db               =   new Database( $this );
            $this->cache            =   new Cache( $this );
        }
        catch( \Exception $e ){
            $this->log->error(
                "WW construct: ".$e->getMessage(), 
                true, 
                [ 
                    'file' => $e->getFile(), 
                    'line' => $e->getLine() 
                ]
            );
        }
    }
    
    public function injest(): self
    {
        try {
            $this->depth            = WitchDataAccess::getDepth( $this );
            $this->cauldronDepth    = CauldronDataAccess::getDepth( $this );

            $this->request          = new Request( $this );
            if( $systemDebugIPFilter = $this->configuration->read('system', 'debug') ){
                $this->debug->addEnableCondition( 
                    in_array( $this->request->requesterIpAddress, $systemDebugIPFilter )
                );    
            }
            
            $this->website          = $this->request->getWebsite();
            $this->debug->addEnableCondition( $this->website->debug );
            
            $this->user             = $this->request->getUser();
            $this->cairn            = $this->website->getCairn();
            
            $this->cairn->summon();
        }
        catch( \Exception $e ){
            $this->log->error(
                "\nWW injest throwed ".get_class($e)."\n".$e->getMessage(), 
                true, 
                [
                    'file' => $e->getFile(), 
                    'line' => $e->getLine() 
                ]
            );
        }
        
        return $this;
    }
    
    public function run(): self
    {
        try {
            $this->cairn->sabbath();
            $this->website->display();
        }
        catch( \Exception $e ){
            $this->log->error(
                "\nWW run throwed ".get_class($e)."\n".$e->getMessage(), 
                true, 
                [ 
                    'file' => $e->getFile(), 
                    'line' => $e->getLine() 
                ]
            );
        }
        
        $this->debug->display();
        
        return $this;
    }
    
    public function debug( mixed $variable, string $userPrefix='', int $depth=1 ){
        return $this->debug->dump( $variable, $userPrefix, $depth );
    }
    
    public function dump( mixed $variable, string $userPrefix='', int $depth=10 ){
        return $this->debug->dump( $variable, $userPrefix, $depth );
    }
    
    public function witch( ?string $witchName=null ){
        return $this->cairn->witch( $witchName );
    }
}
