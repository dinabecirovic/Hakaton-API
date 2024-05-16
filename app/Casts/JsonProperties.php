<?php

namespace App\Casts;

use Illuminate\Database\Eloquent\Model;

class JsonProperties
{
    protected $model;
    protected $attributes = [];
    protected $allowedProperties = [];
    protected $attributeName;

    public function __construct(Model $model, string $attributeName, array|null $attributes = [], array $allowedProperties = [])
    {
        $this->model = $model;
        $this->attributeName = $attributeName;
        $this->attributes = $attributes ?? [];
        $this->allowedProperties = $allowedProperties;

        // Initialize attributes based on allowed properties, ensuring defaults are set
        foreach ($allowedProperties as $property => $defaultValue) {
            // Only set default value if property is not already set
            if (!isset($this->attributes[$property])) {
                $this->attributes[$property] = $defaultValue;
            }
        }
    }

    /**
     * 
     */
    public function __get($name)
    {
        if (array_key_exists($name, $this->allowedProperties)) {
            return $this->attributes[$name];
        } else {
            throw new \Exception("Property {$name} is not defined.");
        }
    }

    /**
     * 
     */
    public function __set($name, $value)
    {
        if (array_key_exists($name, $this->allowedProperties)) {
            if (!isset($this->attributes[$name]) || $this->attributes[$name] !== $value) {
                $this->attributes[$name] = $value;
                $this->markAttributeAsDirty();
            }
        } else {
            throw new \Exception("Property {$name} is not defined.");
        }
    }

    protected function markAttributeAsDirty()
    {
        $this->model->setAttribute($this->attributeName, $this->attributes);
    }

    /**
     * 
     */
    public function toArray()
    {
        return $this->attributes;
    }
}
