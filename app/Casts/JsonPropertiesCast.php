<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use App\Casts\JsonProperties;

class JsonPropertiesCast implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        return new JsonProperties(
            $model,
            $key,
            json_decode($value, true) ?? [],
            $model->getAllowedJsonProperties($key)
        );
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        if ($value instanceof JsonProperties) {
            $value = $value->toArray();
        }

        return json_encode($value);
    }
}
