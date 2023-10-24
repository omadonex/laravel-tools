<?php

namespace Omadonex\LaravelTools\Support\Services;

class TableService extends OmxService
{
    protected static array $tables = [];

    public static function data(string $tableIndex): array
    {
        return static::$tables[$tableIndex];
    }

    public static function title(string $tableIndex): string
    {
        return self::data($tableIndex)['title'] ?? '';
    }
}