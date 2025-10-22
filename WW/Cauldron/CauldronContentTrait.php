<?php
namespace WW\Cauldron;

trait CauldronContentTrait
{
    function display( ?string $filename=null, ?int $maxChars=null )
    {
        if( !$filename ){
            $filename = strtolower( $this->type );
        }
        
        $instanciedClass    = (new \ReflectionClass($this))->getName();
        $file               = $this->ww->website->getFilePath( $instanciedClass::DIR."/view/".$filename.'.php');
        
        if( !$file ){
            $file = $this->ww->website->getFilePath( $instanciedClass::DIR."/view/default.php");
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


    function form( ?string $filename=null, ?array $params=null )
    {
        if( !$filename ){
            $filename = strtolower( $this->type );
        }
        
        $instanciedClass    = (new \ReflectionClass($this))->getName();
        $file               = $this->ww->website->getFilePath( $instanciedClass::DIR."/edit/".$filename.'.php');
        
        if( !$file ){
            $file = $this->ww->website->getFilePath( $instanciedClass::DIR."/edit/default.php");
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
