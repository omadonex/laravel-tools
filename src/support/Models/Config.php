<?php

namespace Omadonex\LaravelTools\Support\Models;

use Illuminate\Database\Eloquent\Model;

class Config extends Model
{
    public const HISTORY_ENABLED = true;

    protected $guarded = [ 'id' ];
    protected $table   = 'support_config';
    protected $fillable = ['name', 'description', 'value_type_id', 'value'];
    public $incrementing = false;

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
