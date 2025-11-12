<?php

namespace Omadonex\LaravelTools\Support\Resources\Datatables;

use Omadonex\LaravelTools\Support\Repositories\ConfigRepository;
use Omadonex\LaravelTools\Support\Resources\ConfigResource;

class ConfigDatatablesResource extends TypedKeyValueDictionaryDatatablesResource
{
    public function toArray($request): array
    {
        return [

        ] + parent::toArray($request);
    }
}
