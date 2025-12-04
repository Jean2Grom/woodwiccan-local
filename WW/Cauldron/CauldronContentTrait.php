<?php
namespace WW\Cauldron;

trait CauldronContentTrait
{
    /**
     * display the cauldron/ingredient 
     * @var ?string $filename to force the filename to use
     * @var ?int $maxChars if set, put a size limit in display
     * @return void
     */
    function display( ?string $filename=null, ?int $maxChars=null )
    {
        if( !$filename ){
            $filename = strtolower( $this->type );
        }
        
        $instanciedClass    = (new \ReflectionClass($this))->getName();
        $file               = $this->ww->website->getFilePath( $instanciedClass::DIR."/".$filename.'.php' );
        
        if( !$file ){
            $file = $this->ww->website->getFilePath( $instanciedClass::DIR."/default.php");
        }
        
        if( $file )
        {
            ob_start();
            include $file;
            $result = ob_get_contents();
            ob_end_clean();

            $suffix = " (...)";
            if( $this->isIngredient() && $maxChars && strlen($result) > $maxChars && strlen($suffix) < $maxChars )
            {
                $truncated  = substr(  $result, 0, ($maxChars-strlen( $suffix )) );
                $lastSpace  = strrpos( $truncated, " " );

                echo $lastSpace? substr($truncated, 0, $lastSpace): $truncated;
                echo $suffix;
            }
            else {
                echo $result;
            }
        }

        return;
    }

    function displayFilePath( ?string $filename=null )
    {
        if( !$filename ){
            $filename = $this->type.".php";
        }
        elseif( strcasecmp(substr($filename, -4), ".php") != 0 ){
            $filename .=  ".php";
        }

        $instanciedClass    = (new \ReflectionClass($this))->getName();

        return $this->ww->website->getFilePath( $instanciedClass::DISPLAY_DIR."/".$filename ) 
                ?? $this->ww->website->getFilePath( $instanciedClass::DISPLAY_DIR."/default.php" ); 
    }


    /**
     * display the form inputs to edit cauldron/ingredient
     * @var ?string $filename to force the filename to use
     * @var ?array $params associative array that create variables to be used in file
     * @return void
     */
    function form( ?string $filename=null, ?array $params=null )
    {
        if( !$filename ){
            $filename = strtolower( $this->type );
        }
        
        $instanciedClass    = (new \ReflectionClass($this))->getName();
        $file               = $this->ww->website->getFilePath( $instanciedClass::DIR."/form/".$filename.'.php');
        
        if( !$file ){
            $file = $this->ww->website->getFilePath( $instanciedClass::DIR."/form/default.php");
        }
        
        if( !$file ){
            return;
        }
        
        foreach( $params ?? [] as $name => $value ){
            $$name = $value;
        }

        include $file;

        return;
    }
    
    /**
     * 
     */
    function formFilePath( ?string $filename=null )
    {
        if( !$filename ){
            $filename = $this->type.".php";
        }
        elseif( strcasecmp(substr($filename, -4), ".php") != 0 ){
            $filename .=  ".php";
        }

        $instanciedClass    = (new \ReflectionClass($this))->getName();

        return $this->ww->website->getFilePath( $instanciedClass::DISPLAY_DIR."/form/".$filename ) 
                ?? $this->ww->website->getFilePath( $instanciedClass::DISPLAY_DIR."/form/default.php" ); 
    }


    function addJsFile( string $jsFile ){
        return $this->ww->website->context->addJsFile( $jsFile );
    }

    function addCssFile( string $cssFile ){
        return $this->ww->website->context->addCssFile( $cssFile );
    }

    function addJsLibFile( string $jsLibFile ){
        return $this->ww->website->context->addJsLibFile( $jsLibFile );
    }

    function isIngredient(): bool {
        return false;
    }

    function isCauldron(): bool {
        return !$this->isIngredient();
    }

    function validate(): bool {
        return true;
    }
}
