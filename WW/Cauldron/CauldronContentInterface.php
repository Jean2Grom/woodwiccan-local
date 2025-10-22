<?php
namespace WW\Cauldron;

interface CauldronContentInterface 
{
    public function value();
    public function content();
    public function display( ?string $filename=null, ?int $maxChars=null );
    public function form( ?string $filename=null, ?array $params=null );
    public function edit( ?array $inputs ): self;
    public function delete(): bool;
    public function exist(): bool;
    public function isIngredient(): bool;
    public function isCauldron(): bool;
    public function validate(): bool;
}