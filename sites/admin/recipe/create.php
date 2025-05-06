<?php /** @var WW\Module $this */

use WW\Handler\RecipeHandler;
use WW\Cauldron\Ingredient;

$possibleActionsList = [
    'publish',
];

$action = $this->ww->request->param('action');
if( !in_array($action, $possibleActionsList) ){
    $action = false;
}

$recipe      = RecipeHandler::createFromData($this->ww, []);

$recipes     = $this->ww->configuration->recipes();
$ingredients    = Ingredient::list();

$possibleTypes = [];
foreach( $ingredients as $ingredient ){
    $possibleTypes[ $ingredient ] = $ingredient;
}
foreach( $recipes as $recipeItem ){
    $possibleTypes[ $recipeItem->name ] = $recipeItem->name;
}

$globalRequireInputPrefix = "GLOBAL_RECIPE_REQUIREMENTS";

switch( $action )
{
    case 'publish':

        if( empty($this->ww->request->param("name")) ){
            $this->ww->user->addAlert([
                'level'     =>  'error',
                'message'   =>  "Recipe must have a name"
           ]);        
        }
        else 
        {
            $inputs = $this->ww->request->inputs();

            $composition = [];
            foreach( array_keys($inputs) as $inputName ){
                if( substr($inputName, -5) === "-name" && strlen($inputName) > 5 )
                {
                    $name = substr($inputName, 0, -5);
                    $type = $inputs[ $name."-type" ] ?? null;

                    if( isset($possibleTypes[ $type ]) ){
                        $composition[] = [
                            "mandatory" => !empty($inputs[ $name."-mandatory" ]),
                            "name"      => $name,
                            "type"      => $type,
                            "require"   => getRequire( $inputs, $name ),
                        ];
                    }
                }
            }

            $recipe->name        = $this->ww->request->param("name");
            $recipe->require     = getRequire( $inputs, $globalRequireInputPrefix );
            $recipe->composition = $composition;

            if( !$recipe->save($this->ww->request->param("file")) ){
                $this->ww->user->addAlert([
                    'level'     =>  'error',
                    'message'   =>  "Recipe creation failed"
               ]);    
            }
            else 
            {
                $this->ww->user->addAlert([
                    'level'     =>  'success',
                    'message'   =>  "Recipe \"".$recipe->name."\" created"
               ]);
               header( 'Location: '.$this->ww->website->getFullUrl('recipe/edit', ['recipe' => $recipe->name]) );
               exit();
            }
        }
    break;
}

function getRequire( $inputs, $name )
{
    $require    = [];
    if( !empty($inputs[ $name."-accepted" ]) ){
        $require['accept'] = $inputs[ $name."-accepted" ];
    }
    if( !empty($inputs[ $name."-refused" ]) ){
        $require['refuse'] = $inputs[ $name."-refused" ];
    }
    if( $inputs[ $name."-min" ] > 0 ){
        $require['min'] = $inputs[ $name."-min" ];
    }
    if( $inputs[ $name."-max" ] > 0 ){
        $require['max'] = $inputs[ $name."-max" ];
    }

    return $require;
}

$this->view('recipe/edit');