<?php

namespace Omadonex\LaravelTools\Acl\Models;

use Illuminate\Database\Eloquent\Model;
use Omadonex\LaravelTools\Support\Classes\ConstCustom;
use Omadonex\LaravelTools\Support\Traits\ProtectedGenerateTrait;
use Omadonex\LaravelTools\Locale\Traits\TranslateTrait;

class Role extends Model
{
    use TranslateTrait, ProtectedGenerateTrait;

    protected $table = 'acl_roles';
    protected $fillable = ['is_root', 'is_staff'];
    public $incrementing = false;
    public $timestamps = false;

    protected $casts = [
        ConstCustom::DB_FIELD_PROTECTED_GENERATE => 'boolean',
    ];

    public $availableRelations = ['translates', 'permissions'];

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'acl_pivot_permission_role');
    }
}
