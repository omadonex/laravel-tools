<?php

namespace Omadonex\LaravelTools\Support\ReadyCRUDPages\ConfigPage\Resources\Datatables;

use Omadonex\LaravelTools\Support\Resources\Datatables\TypedKeyValueDictionaryDatatablesResource;

class ConfigDatatablesResource extends TypedKeyValueDictionaryDatatablesResource
{
    public function toArray($request): array
    {
        return [

        ] + parent::toArray($request);
    }
}
