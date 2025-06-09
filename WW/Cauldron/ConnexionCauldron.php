<?php
namespace WW\Cauldron;

use WW\Cauldron;
use WW\DataAccess\CauldronDataAccess as DataAccess;
use WW\Handler\CauldronHandler as Handler;
use WW\Handler\IngredientHandler;

class ConnexionCauldron extends Cauldron
{
    function __construct()
    {
        $this->data = (object) [
            'connector' => 'user__connexion',
            'table'     => 'user__connexion',
            'field'     => 'id',
        ];
    }

    function readInputs( mixed $inputs=null ): self
    {
        $formattedInputs                                = $inputs;
        $formattedInputs['content']['email']['name']    = 'email';
        $formattedInputs['content']['email']['type']    = 'string';
        $formattedInputs['content']['login']['name']    = 'login';
        $formattedInputs['content']['login']['type']    = 'string';

        $passwordInputs     = $formattedInputs['content']['password'];
        unset($formattedInputs['content']['password']);

        $passHashInputs = [ 
            'name'  => 'pass_hash', 
            'type'  => 'string', 
            'value' => $this->content('pass_hash')?->value() ?? "",
        ];
        if( !empty($passwordInputs['value']) 
            && $passwordInputs['value'] === $passwordInputs['confirm_value'] 
        ){
            $passHashInputs['value'] = password_hash($passwordInputs['value'], PASSWORD_DEFAULT);
        }
        
        $formattedInputs['content']['pass_hash'] = array_replace_recursive(
            $passHashInputs,
            $formattedInputs['content']['pass_hash'] ?? []
        );

        $connector = $this->content('user__connexion');

        parent::readInputs( $formattedInputs );

        if( $connector ){
            $this->addIngredient($connector);
        }

        return $this;
    }


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

        if( $connexionId = $this->content('user__connexion')?->value() ){
            return (DataAccess::updateConnectedData(
                $this->ww, 
                "user__connexion", 
                $values, 
                [ "id" => $connexionId ]
            ) !== false);
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
                'name'  => 'user__connexion',
                'value' => $newConnexion
            ];

            $ingredient = IngredientHandler::createFromData( $this, 'integer', $data );

            return $ingredient->save();
        }
    }


    protected function deleteAction(): bool
    {
        $result = true;

        if( $connector = $this->content('user__connexion') )
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
        if( !$connexionId = $this->content('user__connexion')?->value() ){
            return;
        }

        if( $connexionId === $this->ww->user->id )
        {
            $this->create( 'email', 'string', ['value' => $this->ww->user->email] );
            $this->create( 'login', 'string', ['value' => $this->ww->user->login] );
            $this->create( 'pass_hash', 'string', ['value' => $this->ww->user->pass_hash] );

            return;
        }

        if( !$data = DataAccess::selectConnectedData( 
                $this->ww, 
                'user__connexion', 
                [ 'id' => $connexionId ]
            ) 
        ){
            return;
        }

        $this->create( 'email', 'string', [ 'value' => $data[0]['email'] ] );
        $this->create( 'login', 'string', [ 'value' => $data[0]['login'] ] );
        $this->create( 'pass_hash', 'string', [ 'value' => $data[0]['pass_hash'] ] );

        return;
    }

}

