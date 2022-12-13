<?php

namespace Omadonex\LaravelTools\Acl\Models;

use Illuminate\Database\Eloquent\Model;
use Omadonex\LaravelTools\Locale\Traits\TranslateTrait;

class PermissionGroup extends Model
{
    use TranslateTrait;

    protected $table = 'acl_permission_groups';
    protected $fillable = [];
    public $incrementing = false;
    public $timestamps = false;

    public $availableRelations = ['translates', 'permissions'];

    public function permissions()
    {
        return $this->hasMany(Permission::class);
    }
}
