<?php

namespace Omadonex\LaravelTools\Acl\Traits;

use Omadonex\LaravelTools\Acl\Models\Permission;
use Omadonex\LaravelTools\Acl\Models\Role;

trait AclTrait
{
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'acl_pivot_role_user');
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'acl_pivot_permission_user');
    }
}
