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
$this->ww->debug( $this->id, 'readInputs' );
        $connector = $this->content('user__connexion');
$this->ww->debug( $connector, "connector", 2 );
$this->ww->debug( $this->content, "aaa", 2 );
//$this->generateContent();
//$this->ww->dump( $inputs );

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
//$this->ww->dump( $formattedInputs );
//$this->ww->debug( $this->content, "bbb", 2 );

        $this->content('email')->readInputs( $formattedInputs['content']['email'] );
        $this->content('login')->readInputs( $formattedInputs['content']['login'] );
        $this->content('pass_hash')->readInputs( $formattedInputs['content']['pass_hash'] );
//$this->ww->debug( $this->content('email'), "bbb", 2 );
//        parent::readInputs( $formattedInputs );

// $this->ww->debug( $this->content, "zzz", 2 );
// $this->ww->debug->die('jean');

        // if( $connector ){
        //     $this->addIngredient($connector);
        // }

        return $this;
    }


    protected function saveAction(): bool
    {
// $this->ww->dump( $this->id, 'saveAction' );
// $this->ww->debug->die('jean');

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

    protected function generateContent(): void
    {
        foreach( $this->ingredients as $ingredient ){
            if( $ingredient->name === 'user__connexion' )
            {
                $this->content  = [ $ingredient ];
                $connexionId    = $ingredient->value();   
            }
        }
        
        if( empty($connexionId) ){
            $data = [
                'email'     => "",
                'login'     => "",
                'pass_hash' => "",
            ];
        }
        elseif( $connexionId === $this->ww->user->id )
        {
            $data = [
                'email'     => $this->ww->user->email,
                'login'     => $this->ww->user->login,
                'pass_hash' => $this->ww->user->pass_hash,
            ];
        }
        elseif( !$data = DataAccess::fetchConnectedData( 
                $this->ww, 
                'user__connexion', 
                [ 'id' => $connexionId ]
            ) 
        ){
            $data = [
                'email'     => "",
                'login'     => "",
                'pass_hash' => "",
            ];
        }

        foreach( ['email', 'login', 'pass_hash'] as $stringItem )
        {
            $content          = IngredientHandler::factory('string');
            $content->ww      = $this->ww;
            $content->name    = $stringItem;
            $content->priority= 0;
            $content->init($data[ $stringItem ]);

            $this->content[] = $content;
        }
        
        return;
    }


    function clone(): self
    {
        Handler::writeProperties( $this );
        $cloneProperties = $this->properties;
        unset( $cloneProperties['id'] );
        
        $clone = Handler::createFromData( $this->ww, $cloneProperties );

        foreach( $this->contents() as $content )
        {
            // Cauldron case 
            if( is_a($content, self::class) ){
                Handler::setParenthood( $clone, $content->clone() );
            }
            // Ingredient case
            elseif( $content->name !== 'user__connexion' )
            { 
                IngredientHandler::writeProperties($content);
                $cloneContentProperties = $content->properties;
                unset( $cloneContentProperties['id'] );
                unset( $cloneContentProperties['cauldron_fk'] );

                $clone->content[] = IngredientHandler::createFromData( 
                    $clone, 
                    $content->type, 
                    $cloneContentProperties 
                );
            }
        }

        return $clone;
    }    

}

