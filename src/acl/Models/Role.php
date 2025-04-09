<?php

namespace Omadonex\LaravelTools\Acl\Models;

use Illuminate\Database\Eloquent\Model;
use Omadonex\LaravelTools\Support\Classes\ConstCustom;
use Omadonex\LaravelTools\Support\Traits\ProtectedGenerateTrait;
use Omadonex\LaravelTools\Locale\Traits\TranslateTrait;

class Role extends Model
{
    use TranslateTrait;
    use ProtectedGenerateTrait;

    public const MODEL_SHOW_URL = 'admin.acl.role.show';
    public const HISTORY_ENABLED = true;

    protected $table = 'acl_role';
    protected $fillable = ['id', 'is_staff', 'is_hidden', 'sort_index'];
    public $incrementing = false;
    public $timestamps = false;

    protected $casts = [
        ConstCustom::DB_FIELD_PROTECTED_GENERATE => 'boolean',
        'is_staff' => 'boolean',
        'is_hidden' => 'boolean',
    ];

    public $availableRelations = ['translates', 'permissions'];

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'acl_pivot_permission_role');
    }

    public static function getPath(): string
    {
        return config('omx.acl.acl.rolePath');
    }

    public static function getFormPath(): string
    {
        return 'omx-bootstrap::pages.role';
    }

    public static function getRouteName(string $resourcePart): string
    {
        return self::getPath() . ".{$resourcePart}";
    }
}
