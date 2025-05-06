<?php
namespace WW;

/**
 * Class dedicated for debugging WW projects
 * Can display big var debugs, exec times, 
 * and can log in file if wanted
 * 
 * @author Jean2Grom
 */
class Debug 
{
    const CSS_STYLE = [
        'width'             => 'max-content',
        'overflow-y'        => 'auto',
        'padding'           => '7px 15px',
        'background-color'  => '#1a1a1a',
        'color'             => '#358a2e',
        'box-shadow'        => '3px 3px 5px #aaa',
        'max-height'        => '90%',
        'text-align'        => 'left',
        'text-transform'    => 'none',
        'position'          => 'absolute',
        'z-index'           => 99999999,
        'left'              => '2%',
        'top'               => '2%',
        'border-radius'     => '10px',
    ];
    
    const PHP_ERROR_LEVELS = [
        E_ERROR             => "E_ERROR",
        E_WARNING           => "E_WARNING",
        E_PARSE             => "E_PARSE",
        E_NOTICE            => "E_NOTICE",
        E_CORE_ERROR        => "E_CORE_ERROR",
        E_CORE_WARNING      => "E_CORE_WARNING",
        E_COMPILE_ERROR     => "E_COMPILE_ERROR",
        E_COMPILE_WARNING   => "E_COMPILE_WARNING",
        E_USER_ERROR        => "E_USER_ERROR",
        E_USER_WARNING      => "E_USER_WARNING",
        E_USER_NOTICE       => "E_USER_NOTICE",
        E_STRICT            => "E_STRICT",
        E_RECOVERABLE_ERROR => "E_RECOVERABLE_ERROR",
        E_DEPRECATED        => "E_DEPRECATED",
        E_USER_DEPRECATED   => "E_USER_DEPRECATED",
        E_ALL               => "E_ALL"
    ];
    
    /**
     * To enable / disable the debug
     * 
     * @var bool
     */
    public $enabled;
    
    public $buffer = [];
    public $resume = [];
    public $databaseAnalysis = [];
    
    private $refNanoTime;
    private $databaseRefNanoTime;
    
    /**
     * Bytes value of implementation of class
     * Update it for exec memory usage analysis
     * 
     * @var int
     */
    public $refMemoryUsage;
    
    /** 
     * WoodWiccan container class to allow whole access to Kernel
     * @var WoodWiccan
     */
    public WoodWiccan $ww;
    
    /**
     * 
     * @param WoodWiccan $ww container
     */
    function __construct( WoodWiccan $ww )
    {
        $this->ww           = $ww;
        $this->enabled      = false;
        $this->refNanoTime = hrtime(true);
        
        set_error_handler(function($errno, $errstr, $errfile, $errline) {
            $errLevel = "PHP ERROR HANDLED: ";
            $errLevel .= self::PHP_ERROR_LEVELS[$errno] ?? '';
            
            $this->dump( $errstr, $errLevel, 1, ['file' => $errfile, 'line' => $errline] );
        });
    }
    
    /**
     * Print a variable dump formatted and processed if big variable
     * 
     * @param mixed $variable : variable to dump
     * @param string $userPrefix : prefix a header to the dump
     * @param int $depth : max depth to explore, ignored if set to 0 
     * @param array|null $callerArray : [ 'file' => File full path name, 'line' => int line number of caller file ]
     * @return $this
     */
    function dump( mixed $variable, string $userPrefix='', int $depth=10, ?array $callerArray=null ): self
    {
        if( $this->enabled )
        {
            ob_start();
            
            if( $userPrefix !== '' ){
                echo $userPrefix."\n";
            }
            
            echo $this->prefix($callerArray);
            
            if( $depth == 0 ){
                var_dump($variable);
            }
            else {
                $this->debugPrint($variable, $depth);
            }
            
            $this->buffer[] = ob_get_contents();
            ob_end_clean();
        }
        
        return $this;
    }
    
    /**
     * Recursive function to print analysis
     * 
     * @param mixed $variable
     * @param int $depth
     * @param int $i
     * @param array $objects
     * @return type
     */
    private function debugPrint( mixed $variable, int $depth=10, int $i=0, array $objects=[] )
    {
        $search     = array("\0", "\a", "\b", "\f", "\n", "\r", "\t", "\v");
        $replace    = array('\0', '\a', '\b', '\f', '\n', '\r', '\t', '\v');
        
        $string     = '';
        
        switch( gettype($variable) ) 
        {
            case 'boolean':
                $string .= $variable? 'true': 'false'; 
            break;
            
            case 'resource':
                $string .= '[resource]';
            break;
            
            case 'NULL':
                $string .= "null";
            break;
            
            case 'unknown type': 
                $string .= '???';
            break;
            
            case 'string':
                $len        = strlen( $variable );
                $variable   = str_replace( $search, $replace, $variable );
                
                $string .= '"'.$variable.'"';
            break;
            
            case 'array':
                $len = count( $variable );
                
                if( $i == $depth ){
                    $string .= 'array('.$len.') {...}';  
                }
                elseif( !$len ){
                    $string .= 'array(0) {}';
                }
                else 
                {
                    $keys   = array_keys( $variable );
                    $spaces = str_repeat( ' ', $i*2 );
                    
                    $string .= "array($len)\n".$spaces.'{';
                    
                    foreach( $keys as $key ) 
                    {
                        $string .=  "\n".$spaces."  [".( is_numeric($key)? $key : '"'.$key.'"' )."] => ";
                        $string .=  $this->debugPrint( $variable[$key], $depth, $i+1, $objects );
                    }
                    
                    $string .=  "\n".$spaces.'}';
                }
            break;
                
            case 'object':
                $id = array_search( $variable, $objects, true );
                
                if( $id!==false ){
                    $string .=  get_class($variable).'#'.( $id+1 ).' {...}'; 
                }
                elseif( $i==$depth ){
                    $string .=  get_class($variable).' {...}'; 
                }
                elseif( $i > 0 && get_class($variable) === "WW\\WoodWiccan" ){
                    $string .=  get_class($variable).' {...}'; 
                }
                else 
                {
                    $id     = array_push( $objects, $variable );
                    $array  = (array) $variable;
                    $spaces = str_repeat( ' ', $i*2 );
                    
                    $string .=  get_class($variable)."#$id\n".$spaces.'{';
                    
                    $properties = array_keys($array);
                    foreach( $properties as $property ) 
                    {
                        $name   = str_replace( "\0", ':', trim($property) );
                        
                        $string .= "\n".$spaces."  [\"".$name."\"] => ";
                        $string .= $this->debugPrint( $array[$property], $depth, $i+1, $objects );
                    }
                    
                    $string.= "\n".$spaces.'}';
                }
            break;
            
            default :
                $string .= $variable;
            break;
        }
        
        if( $i>0 ){
            return $string;
        }
        
        echo $string;
        
        return;
    }
    
    /**
     * 
     * usage :
     * $debug->time();
     * 
     * -> code to check execution time
     * 
     * $debug->time();
     * 
     * -> code to check execution time ...
     * 
     * $debug->time(); ... 
     * 
     * etc...
     * 
     * @param boolean $display set to false for disable printTime
     * @return int microtime current value stored
     */
    public function time( bool $display=true )
    {
        $time = hrtime(true);
        
        if( $display && $this->refNanoTime )
        {
            $nanoSec        =   $time - $this->refNanoTime;
            $secDiff        =   floor( $nanoSec/1e+9 );
            $nanoSec        -=  $secDiff * 1e+9;
            $mSecDiff       =   floor( $nanoSec/1e+6 );
            $nanoSec        -=  $mSecDiff * 1e+6;
            $microSecDiff   =   floor( $nanoSec/1e+3 );
            $nanoSec        -=  $microSecDiff * 1e+3;
            
            $this->dump( $secDiff." seconds, ".$mSecDiff." milliseconds and ".$microSecDiff." microseconds", "Time" );
        }
        
        $this->refNanoTime = $time;
        
        return $this->refNanoTime;
    }    
    
    /**
     * 
     * usage :
     * $debug->memory();
     * 
     * -> code to check execution memory usage
     * 
     * $debug->memory();
     * 
     * -> code to check execution memory usage
     * 
     * $debug->time(); ... 
     * 
     * etc...
     * 
     * @param boolean $display for disable print (initialize only)
     * @return int microtime current value stored
     */
    public function memory( bool $display=true )
    {
        $mem = memory_get_usage();
        
        if( $display && $this->refMemoryUsage ){
            $this->dump( ($mem - $this->refMemoryUsage)." bytes allocated since last memory print", "Memory" );
        }
        
        $this->refMemoryUsage = $mem;
        
        return $this->refMemoryUsage;
    }
    
    /**
     * Get the Log class prefix
     * 
     * @param array|null $callerArray : [ 'file' => File full path name, 'line' => int line number of caller file ]
     * @param bool $addDateTimeIp 
     * @return string
     */
    function prefix( ?array $callerArray=null, bool $addDateTimeIp=true )
    {
        if( empty($this->ww->log) ){
            return '';
        }
        
        return $this->ww->log->prefix($callerArray, $addDateTimeIp)."\n\n";
    }
    
    /**
     * 
     * @return void
     */
    function display(): void
    {
        if( !$this->enabled ){
            return;
        }
        
        if( !empty($this->resume) )
        {
            ob_start();
            
            echo "**********\n";
            echo "* RESUME *\n";
            echo "**********\n";
            foreach( $this->resume as $resumeItem ){
                echo "\n".$resumeItem['userPrefix'].' '.trim($resumeItem['callerArray']).' '.$resumeItem['variable'];
            }
            
            $this->buffer[] = ob_get_contents();
            ob_end_clean();
        }
        
        if( !empty($this->databaseAnalysis) )
        {
            ob_start();
            
            echo "Database\n";
            echo "========\n";
            foreach( $this->databaseAnalysis as $DAItem => $DAData )
            {
                $nanoSec        =   $DAData['time'];
                $secDiff        =   floor( $nanoSec/1e+9 );
                $nanoSec        -=  $secDiff * 1e+9;
                $mSecDiff       =   floor( $nanoSec/1e+6 );
                $nanoSec        -=  $mSecDiff * 1e+6;
                $microSecDiff   =   floor( $nanoSec/1e+3 );
                $nanoSec        -=  $microSecDiff * 1e+3;
                
                echo $DAItem.' Request quantity='.$DAData['requests'].' Execution time='.$secDiff." seconds, ".$mSecDiff." milliseconds and ".$microSecDiff." microseconds"."\n";
            }
            
            $this->buffer[] = ob_get_contents();
            ob_end_clean();
        }
        
        if( !empty($this->buffer) )
        {
            $style = self::CSS_STYLE;
            
            $styleAttribute = "";
            foreach( $style as $property => $value ){
                $styleAttribute .= $property.": ".$value."; "; 
            }
                        
            echo "<div id=\"ww-debug\" style=\"".$styleAttribute."\">";
            
            echo "<div style=\"color: red;position: fixed;cursor: pointer\" ";
            echo "onclick=\"toggleWcDebug();\">[X]</div>";
            
            echo "<pre style=\"margin-top: 25px;\">";
            foreach( $this->buffer as $buffer ){
                echo $buffer."\n\n\n";
            }
            echo "</pre></div>";
            
            echo "<script>";
            echo    "function toggleWcDebug(){ ";
            echo        "if( document.getElementById('ww-debug').style.display !== 'none' ){ document.getElementById('ww-debug').style.display = 'none' } ";
            echo        "else { document.getElementById('ww-debug').style.display = 'block'; } ";
            echo    "} ";
            echo    "document.addEventListener('keyup', (event) => { ";
            echo        "if( event.key === 'Escape' ){ toggleWcDebug(); } ";
            echo    "}); ";
            echo "</script>";
        }
    }
    
    /**
     * Stop execution and throw anonymous custom exception to container 
     * that display debug data
     * 
     * @param string $msg
     * @return void
     * @throws \Exception
     */
    function throwException( string $msg ): void {
        throw new class( $msg ) extends \Exception {
            public function __construct( $message ) 
            {
                parent::__construct( $message );
        
                foreach( $this->getTrace() as $trace ){
                    if( $trace["file"] !== __FILE__ )
                    {
                        $this->file = $trace["file"];
                        $this->line = $trace["line"];
                        break;
                    }
                }
            }
        };
    }
    
    /**
     * Other name for throwException
     * @param string $msg
     * @return void
     */
    function die( string $msg ): void {
        $this->throwException($msg);
    }
    
    /**
     * 
     * @param string $variable
     * @param string $userPrefix
     * @param array|null $callerArray
     * @return void
     */
    function toResume( string $variable, string $userPrefix='', ?array $callerArray=null ): void
    {
        if( $this->enabled ){
            $this->resume[] = [
                'variable'       => $variable,
                'userPrefix'     => $userPrefix,
                'callerArray'    => $this->prefix($callerArray, false),
            ];
         }
    }
    
    /**
     * 
     * @param string $type
     * @return void
     */
    function databaseAnalysePrepare( string $type ): void
    {
        if( !$this->enabled ){
            return;
        }
        
        if( !isset($this->databaseAnalysis[ $type ]) ){
            $this->databaseAnalysis[ $type ] = [
                'requests'  => 0,
                'time'      => 0,
            ];
        }
        
        $this->databaseRefNanoTime = hrtime(true);
    }
    
    /**
     * 
     * @param string $type
     * @return void
     */
    function databaseAnalyse( string $type ): void
    {
        if( !$this->enabled ){
            return;
        }
        
        $time = hrtime(true) - $this->databaseRefNanoTime;
        
        $this->databaseAnalysis[ $type ]['requests']++;
        $this->databaseAnalysis[ $type ]['time'] += $time;
    }
    
    /**
     * enable debug output
     */
    function enable(){
        $this->enabled = true;
    }
    
    /**
     * disable debug output
     */
    function disable(){
        $this->enabled = false;
    }
    
    /**
     * disable debug output
     */
    function addEnableCondition( bool $condition ){
        $this->enabled = $this->enabled && $condition;
    }
    
}
