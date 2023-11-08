<?php

namespace Omadonex\LaravelTools\Acl\Repositories;

use Illuminate\Support\Collection;
use Omadonex\LaravelTools\Acl\Interfaces\IAclRepository;
use Omadonex\LaravelTools\Acl\Models\Permission;
use Omadonex\LaravelTools\Acl\Models\Role;

class AclRepository implements IAclRepository
{
    public function addRole($user, array|string $role): void
    {
        $user->roles()->attach($role);
    }

    public function addPermissionToRole($role, array|string $permission): void
    {
        $role->permissions()->attach($permission);
    }

    public function addPermissionToUser($user, array|string $permission): void
    {
        $user->permissions()->detach($permission);
    }

    public function removeRole($user, array|string $role): void
    {
        $user->roles()->detach($role);
    }

    public function removePermissionFromRole($role, array|string $permission): void
    {
        $role->permissions()->detach($permission);
    }

    public function removePermissionFromUser($user, array|string $permission): void
    {
        $user->permissions()->detach($permission);
    }

    /**
     * @return array
     */
    public function getAllPermissionList(): array
    {
        return Permission::with('translates')->get()->toArray();
    }

    /**
     * @param bool $permissions
     * @return array
     */
    public function getAllRoleList(bool $permissions = true): Collection
    {
        $relations = ['translates'];
        if ($permissions) {
            $relations[] = 'permissions';
            $relations[] = 'permissions.translates';
        }

        return Role::with($relations)->get();
    }
}