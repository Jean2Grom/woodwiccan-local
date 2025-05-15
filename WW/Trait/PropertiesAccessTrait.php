<?php
namespace WW\Trait;

trait PropertiesAccessTrait
{
    /**
     * Property setting 
     * @param string $name
     * @param mixed $value
     * @return void
     */
    public function __set(string $name, mixed $value): void {
        $this->properties[ $name ] = $value;
    }
    
    /**
     * Property reading
     * @param string $name
     * @return mixed
     */
    public function __get(string $name): mixed {
        return $this->properties[ $name ] ?? null;
    }
}