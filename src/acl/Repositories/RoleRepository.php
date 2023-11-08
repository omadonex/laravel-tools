<?php

namespace Omadonex\LaravelTools\Acl\Repositories;

use Illuminate\Support\Collection;
use Omadonex\LaravelTools\Acl\Http\Resources\RoleResource;
use Omadonex\LaravelTools\Acl\Interfaces\IRole;
use Omadonex\LaravelTools\Acl\Models\Role;
use Omadonex\LaravelTools\Support\Repositories\ModelRepository;

class RoleRepository extends ModelRepository
{
    public function __construct(Role $role)
    {
        parent::__construct($role, RoleResource::class);
    }

    public function pluckUnusedRolesNames(?string $emptyOptionName, array $exceptRoleIdList = [IRole::USER]): array
    {
        $roles = $this->list(['closures' => [
            function ($query) use ($exceptRoleIdList) {
                return $query->whereNotIn('id', $exceptRoleIdList);
            }
        ]]);
        $roles->load('translates');
        $collection = collect();
        foreach ($roles as $role) {
            $collection->put($role->getKey(), $role->getTranslate()->name);
        }

        return ($emptyOptionName !== null ? ['' => $emptyOptionName] : []) + $collection->toArray();
    }

    /**
     * @param bool $permissions
     * @return array
     */
    public function getList( bool $permissions = true): Collection
    {
        $relations = ['translates'];
        if ($permissions) {
            $relations[] = 'permissions';
            $relations[] = 'permissions.translates';
        }

        return Role::with($relations)->get();
    }
}
