<?php

namespace Omadonex\LaravelTools\Support\Resources\Datatables;

use Illuminate\Http\Resources\Json\JsonResource;
use Omadonex\LaravelTools\Support\Repositories\ConfigRepository;

abstract class TypedKeyValueDictionaryDatatablesResource extends JsonResource
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
            'value_type_id_label' => ConfigRepository::VALUE_TYPE_LIST[$this->value_type_id],
            'value' => $this->value,
        ];
    }
}
