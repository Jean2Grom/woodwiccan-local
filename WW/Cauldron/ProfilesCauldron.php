<?php
namespace WW\Cauldron;

use WW\Cauldron;
use WW\DataAccess\CauldronDataAccess as DataAccess;
use WW\Handler\CauldronHandler as Handler;
use WW\Handler\IngredientHandler;

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
        //$connector = $this->content('ww-connexion');

        parent::readInputs( $formattedInputs );

        // if( $connector ){
        //     $this->addIngredient($connector);
        // }

        return $this;
    }

/*
    protected function saveAction(): bool
    {
        $this->position();

        if( $this->depth > $this->ww->cauldronDepth ){
            DataAccess::increaseDepth( $this->ww );
        }
        
        if( !$this->exist() )
        {
            Handler::writeProperties($this); 
            $result = DataAccess::insert($this); 
            
            if( $result ){
                $this->id = (int) $result;
            }
        }
        else 
        {
            $properties = $this->properties;

            Handler::writeProperties($this);
            $result = DataAccess::update( $this, array_diff_assoc($this->properties, $properties) );
        }
        
        if( $result === false ){
            return false;
        }

        //$result = true;
        
        // Deletion of pending deprecated contents
        //$result = $result && $this->purge();

        $values = [ 'name' => $this->parent?->name ?? $this->name ];
        foreach( ['login', 'email', 'pass_hash'] as $field ){
            $values[ $field ] = $this->content( $field )?->value() ?? "";
        }

        if( $connexionId = $this->content('ww-connexion')?->value() ){
            return DataAccess::updateConnectedData(
                $this->ww, 
                "user__connexion", 
                $values, 
                [ "id" => $connexionId ]
            );
        }
        else 
        {
            $newConnexion = DataAccess::insertConnectedData(
                $this->ww, 
                "user__connexion", 
                $values
            );


            if( !$newConnexion ){
                return false;
            }

            $data = [ 
                'name'  => 'ww-connexion',
                'value' => $newConnexion
            ];

            $ingredient = IngredientHandler::createFromData( $this, 'integer', $data );

            return $ingredient->save();
        }
    }


    protected function deleteAction(): bool
    {
        $result = true;

        if( $connector = $this->content('ww-connexion') )
        {
            if( $connector->value() ){
                $result = $result && DataAccess::deleteConnectedData(
                    $this->ww, 
                    'user__connexion', 
                    [ 'id' => $connector->value() ]
                );
            }

            $result = $result && $connector->delete();
        }

        if( $result === false ){
            return false;
        }

        // Deletion of pending deprecated contents
        if( $this->purge() === false ){
            return false;
        }
        
        if( $this->exist() ){
            return DataAccess::delete( $this ) !== false;
        }
        
        return true;
    }



    function init()
    {
        if( !$connexionId = $this->content('ww-connexion')?->value() ){
            return;
        }

        if( !$data = DataAccess::getConnectedData( 
                $this->ww, 
                $connexionId 
            ) 
        ){
            return;
        }

        $this->create( 'email', 'string', [ 'value' => $data[0]['email'] ] );
        $this->create( 'login', 'string', [ 'value' => $data[0]['login'] ] );
        $this->create( 'pass_hash', 'string', [ 'value' => $data[0]['pass_hash'] ] );

        return;
    }
    */
}

