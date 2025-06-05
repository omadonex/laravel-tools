<?php

namespace Omadonex\LaravelTools\Support\Repositories;

use Omadonex\LaravelTools\Support\Classes\Utils\UtilsFilter;
use Omadonex\LaravelTools\Support\Models\Config;
use Omadonex\LaravelTools\Support\Resources\ConfigResource;
use Omadonex\LaravelTools\Support\Traits\ConfigSettingTrait;

class ConfigRepository extends ModelRepository
{
    use ConfigSettingTrait;

    protected $filterFieldsTypes = [
        'id' => ['type' => UtilsFilter::STRING_LIKE],
        'value_type_id' => ['type' => UtilsFilter::EQUALS],
        'value' => ['type' => UtilsFilter::STRING_LIKE],
    ];

    public function __construct(Config $action)
    {
        parent::__construct($action, ConfigResource::class);
    }

    public function get(string $id): mixed
    {
        $config = $this->find($id);

        return $this->castValue($config->value, $config->value_type_id);
    }
}
