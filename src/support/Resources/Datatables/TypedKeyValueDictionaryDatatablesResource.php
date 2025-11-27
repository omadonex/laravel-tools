<?php

namespace Omadonex\LaravelTools\Support\Resources\Datatables;

use Omadonex\LaravelTools\Support\Repositories\TypedKeyValueDictionaryRepository;
use Omadonex\LaravelTools\Support\Resources\OmxJsonResource;

abstract class TypedKeyValueDictionaryDatatablesResource extends OmxJsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            'id'   => $this->id,
            'key' => $this->key,
            'name' => $this->name,
            'description' => $this->description,
            'value_type_id' => $this->value_type_id,
            'value_type_id_label' => TypedKeyValueDictionaryRepository::VALUE_TYPE_LIST[$this->value_type_id],
            'value' => $this->value,
        ];
    }
}
