<?php

namespace Omadonex\LaravelTools\Support\Models;

class Config extends TypedKeyValueDictionary
{
    public const HISTORY_ENABLED = true;
    protected $table = 'support_config';

    public static function getPath(): string
    {
        return config('omx.support.support.configPath');
    }

    public static function getFormPath(): string
    {
        return 'omx-bootstrap::pages.config';
    }

    public static function getRouteName(string $resourcePart): string
    {
        return self::getPath() . ".{$resourcePart}";
    }
}
