<?php

namespace Omadonex\LaravelTools\Support\Repositories;

use Omadonex\LaravelTools\Support\Classes\Utils\UtilsFilter;
use Omadonex\LaravelTools\Support\Traits\TypedKeyValueDictionaryTrait;

abstract class TypedKeyValueDictionaryRepository extends ModelRepository
{
    use TypedKeyValueDictionaryTrait;

    protected $filterFieldsTypes = [
        'key' => ['type' => UtilsFilter::STRING_LIKE],
        'value_type_id' => ['type' => UtilsFilter::EQUALS],
        'value' => ['type' => UtilsFilter::STRING_LIKE],
    ];

    public function get(string $id): mixed
    {
        $config = $this->find($id);

        return $this->castValue($config->value, $config->value_type_id);
    }
}
