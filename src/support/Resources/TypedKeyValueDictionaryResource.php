<?php

namespace Omadonex\LaravelTools\Support\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Omadonex\LaravelTools\Support\Models\TypedKeyValueDictionary;

abstract class TypedKeyValueDictionaryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        /** @var TypedKeyValueDictionary $this */

        return [
            'id'   => $this->id,
            'key' => $this->key,
            'name' => $this->name,
            'description' => $this->description,
            'value_type_id' => $this->value_type_id,
            'value' => $this->value,
        ];
    }
}
