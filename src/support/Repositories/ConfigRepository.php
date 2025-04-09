<?php

namespace Omadonex\LaravelTools\Support\Repositories;

use Omadonex\LaravelTools\Support\Classes\Utils\UtilsFilter;
use Omadonex\LaravelTools\Support\Models\Config;
use Omadonex\LaravelTools\Support\Resources\ConfigResource;

class ConfigRepository extends ModelRepository
{
    const VALUE_TYPE_STRING = 1;
    const VALUE_TYPE_INT = 2;
    const VALUE_TYPE_FLOAT = 3;
    const VALUE_TYPE_BOOL = 4;

    const VALUE_TYPE_LIST = [
        self::VALUE_TYPE_STRING => 'Строка',
        self::VALUE_TYPE_INT => 'Целое число',
        self::VALUE_TYPE_FLOAT => 'Число с плавающей точкой',
        self::VALUE_TYPE_BOOL => 'Логическое значение',
    ];

    protected $filterFieldsTypes = [
        'id' => ['type' => UtilsFilter::STRING_LIKE],
        'value_type_id' => ['type' => UtilsFilter::EQUALS],
        'value' => ['type' => UtilsFilter::STRING_LIKE],
    ];

    public function __construct(Config $action)
    {
        parent::__construct($action, ConfigResource::class);
    }

    private function castValue(mixed $value, int $type): mixed
    {
        switch ($type) {
            case self::VALUE_TYPE_INT: return intval($value);
            case self::VALUE_TYPE_FLOAT: return floatval($value);
            case self::VALUE_TYPE_BOOL: return boolval($value);
        }

        return $value;
    }

    public function get(string $id): mixed
    {
        $config = $this->find($id);

        return $this->castValue($config->value, $config->value_type_id);
    }
}
