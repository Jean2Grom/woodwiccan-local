<?php
namespace WW\Cauldron;

use WW\Cauldron;
use WW\DataAccess\CauldronDataAccess as DataAccess;

class ProfilesCauldron extends Cauldron
{
    public ?array $profiles = null;
    public ?array $selected = null;

    function __construct()
    {
        $this->data = (object) [
            'connector' => 'ww-profiles',
            'table'     => 'user__profiles',
            'field'     => 'id',
        ];
    }

    function edit( ?string $filename=null, ?array $params=null )
    {
        if( is_null($this->profiles) )
        {
            $condition = [];
            if( $this->ww->website->sitesRestrictions ){
                $condition = [ 'site' => array_merge($this->ww->website->sitesRestrictions, ['*']) ];
            }

            $this->profiles = [];
            foreach( DataAccess::selectConnectedData( 
                $this->ww, 
                'user__profile', 
                $condition
            ) as $profile ){
                $this->profiles[ $profile['id'] ] = $profile;
            }
        }

        if( is_null($this->selected) )
        {
            $this->selected = [];
            foreach( $this->ingredients as $integerIngredient ){
                if( $integerIngredient->value() ){
                    $this->selected[] = $integerIngredient->value();
                }
            }
        }

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

    function readInputs( mixed $inputs=null ): self
    {
        $formattedInputs = $inputs;

        $profileInput = [];
        foreach( $formattedInputs['content']['user__profile'] ?? [] as $userProfileID ){
            $profileInput[] = [
                'name'  => 'user__profile',
                'type'  => 'integer',
                'value' => $userProfileID,
            ];
        }

        $formattedInputs['content'] = $profileInput;

        parent::readInputs( $formattedInputs );

        return $this;
    }

}

