<?php

namespace Omadonex\LaravelTools\Acl\Models;

use Omadonex\LaravelTools\Locale\Traits\TranslateTrait;
use Omadonex\LaravelTools\Support\Models\OmxModel;

class Permission extends OmxModel
{
    use TranslateTrait;

    protected $table = 'acl_permission';
    protected $fillable = ['sort_index'];
    public $incrementing = false;
    public $timestamps = false;

    public $availableRelations = ['translates', 'roles', 'group'];

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'acl_pivot_permission_role');
    }

    public function group()
    {
        return $this->belongsTo(PermissionGroup::class, 'permission_group_id');
    }
}
