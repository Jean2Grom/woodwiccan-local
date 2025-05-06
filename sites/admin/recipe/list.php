<?php /** @var WW\Module $this */

use WW\DataAccess\RecipeDataAccess;

$recipes = $this->ww->configuration->recipes();

$recipeArray = RecipeDataAccess::readUsage(
    $this->ww, 
    array_keys($recipes)
);

foreach( $recipes as $recipe ){
    $recipeArray[ $recipe->name ]['name'] = $recipe->name;
}

$this->view();