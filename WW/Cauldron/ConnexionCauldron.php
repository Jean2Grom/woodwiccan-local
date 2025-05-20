<?php
namespace WW\Cauldron;

use WW\Cauldron;
use WW\DataAccess\CauldronDataAccess as DataAccess;
use WW\DataAccess\UserDataAccess;
use WW\Handler\CauldronHandler as Handler;
use WW\Handler\UserHandler;

class ConnexionCauldron extends Cauldron
{
    
    function readInputs( mixed $inputs=null ): self
    {
//$this->ww->dump($inputs);
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
$this->ww->dump($formattedInputs);
        return parent::readInputs( $formattedInputs );
    }

    function create( string $name, ?string $type=null, array $initProperties=[] ): CauldronContentInterface
    {
$this->ww->debug($name, "AAAAAA");
$this->ww->debug($type, "BBBB");
$this->ww->dump($initProperties, "CCCC");

$this->ww->debug->die( "jean");
return $this;

        if( $type && in_array($type, Ingredient::list()) )
        {
            $content            = IngredientHandler::factory($type);
            $content->ww        = $this->ww;
            $content->name      = !empty($name)? $name: $type;

            $content->init( $initProperties['value'] ?? null );
        }
        else 
        {
            $recipe     = $this->ww->configuration->recipe( $type ) 
                            ?? $this->ww->configuration->recipe('folder');
            $content    = $recipe->factory( !empty($name)? $name: $recipe->name, $initProperties );
        }

        $content->priority  =  $initProperties['priority'] ?? 0; 

        $this->add( $content );

        return $content;
    }    

    protected function saveAction(): bool
    {
$this->ww->dump($this->properties); 
        Handler::writeProperties($this); 

$this->ww->dump($this->properties); 
        return true; 

        // $this->position();

        // if( $this->depth > $this->ww->cauldronDepth ){
        //     DataAccess::increaseDepth( $this->ww );
        // }
        
        if( !$this->exist() )
        {
            UserDataAccess::insertConnexion($this->ww, [
                'name' => $this->properties['name']
            ]);

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
        $result = true;
        
        // Deletion of pending deprecated contents
        $result = $result && $this->purge();

        // Saving contents
        foreach( $this->ingredients as $ingredient ){
            $result = $result && $ingredient->save();
        }

        foreach( $this->children as $child ) 
        {
            if( !$child->isContent() ){
                continue;
            }

            $result = $result && $child->saveAction( false );
        }

        return $result;
    }

}

