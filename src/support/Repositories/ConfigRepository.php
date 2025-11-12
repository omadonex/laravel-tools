<?php

namespace Omadonex\LaravelTools\Support\Repositories;

use Omadonex\LaravelTools\Support\Models\Config;
use Omadonex\LaravelTools\Support\Resources\ConfigResource;

class ConfigRepository extends TypedKeyValueDictionaryRepository
{
    public function __construct(Config $action)
    {
        parent::__construct($action, ConfigResource::class);
    }
}
