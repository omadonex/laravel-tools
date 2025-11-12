<?php

namespace Omadonex\LaravelTools\Support\ReadyCRUDPages\ConfigPage\Repositories;

use Omadonex\LaravelTools\Support\ReadyCRUDPages\ConfigPage\Models\Config;
use Omadonex\LaravelTools\Support\ReadyCRUDPages\ConfigPage\Resources\ConfigResource;
use Omadonex\LaravelTools\Support\Repositories\TypedKeyValueDictionaryRepository;

class ConfigRepository extends TypedKeyValueDictionaryRepository
{
    public function __construct(Config $action)
    {
        parent::__construct($action, ConfigResource::class);
    }
}
