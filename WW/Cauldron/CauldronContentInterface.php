<?php
namespace WW\Cauldron;

interface CauldronContentInterface 
{
    public function value();
    public function content();
    public function display( ?string $filename=null, ?int $maxChars=null );
    public function edit( ?string $filename=null, ?array $params=null );
    public function readInputs( mixed $input ): self;
    public function delete(): bool;
    public function exist(): bool;
    public function isIngredient(): bool;
    public function isCauldron(): bool;
    public function validate(): bool;
}