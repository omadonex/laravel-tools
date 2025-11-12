<?php

namespace Omadonex\LaravelTools\Support\Traits;

trait TypedKeyValueDictionaryTrait
{
    public const VALUE_TYPE_STRING = 1;
    public const VALUE_TYPE_INT = 2;
    public const VALUE_TYPE_FLOAT = 3;
    public const VALUE_TYPE_BOOL = 4;

    public const VALUE_TYPE_LIST = [
        self::VALUE_TYPE_STRING => 'Строка',
        self::VALUE_TYPE_INT => 'Целое число',
        self::VALUE_TYPE_FLOAT => 'Число с плавающей точкой',
        self::VALUE_TYPE_BOOL => 'Логическое значение',
    ];

    public function castValue(mixed $value, int $type): mixed
    {
        switch ($type) {
            case self::VALUE_TYPE_INT: return intval($value);
            case self::VALUE_TYPE_FLOAT: return floatval($value);
            case self::VALUE_TYPE_BOOL: return boolval($value);
        }

        return $value;
    }
}
