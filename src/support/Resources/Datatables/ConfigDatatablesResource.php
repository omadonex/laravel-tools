<?php

namespace Omadonex\LaravelTools\Support\Resources\Datatables;

use Omadonex\LaravelTools\Support\Repositories\ConfigRepository;
use Omadonex\LaravelTools\Support\Resources\ConfigResource;

class ConfigDatatablesResource extends ConfigResource
{
    public function toArray($request): array
    {
        return [
            'id'   => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'value_type_id' => $this->value_type_id,
            'value_type_id_label' => ConfigRepository::VALUE_TYPE_LIST[$this->value_type_id],
            'value' => $this->value,
        ];
    }
}
