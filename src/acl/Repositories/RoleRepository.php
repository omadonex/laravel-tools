<?php

namespace Omadonex\LaravelTools\Acl\Repositories;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Omadonex\LaravelTools\Acl\Http\Resources\RoleResource;
use Omadonex\LaravelTools\Acl\Interfaces\IRole;
use Omadonex\LaravelTools\Acl\Models\Role;
use Omadonex\LaravelTools\Locale\Interfaces\ILocaleService;
use Omadonex\LaravelTools\Support\Classes\Utils\UtilsFilter;
use Omadonex\LaravelTools\Support\Repositories\ModelRepository;

class RoleRepository extends ModelRepository
{
    protected $filterFieldsTypes = [
        'id'          => [ 'type' => UtilsFilter::STRING_LIKE ],
        'name'        => [ 'type' => UtilsFilter::STRING_LIKE ],
        'description' => [ 'type' => UtilsFilter::STRING_LIKE ],
        'is_staff'    => [ 'type' => UtilsFilter::YES_NO ],
        'is_hidden'   => [ 'type' => UtilsFilter::YES_NO ],
    ];

    protected ILocaleService $localeService;

    public function __construct(Role $role, ILocaleService $localeService)
    {
        parent::__construct($role, RoleResource::class);
        $this->localeService = $localeService;
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

    public function grid($options = [])
    {
        $lang = $this->localeService->getLocaleCurrent();

        $sql = /* @lang MySQL */ "
        SELECT
            r.id,
            t.name,
            t.description,
            r.is_staff,
            r.is_hidden
        FROM
            acl_role AS r
            LEFT JOIN acl_role_translate AS t ON t.model_id = r.id AND t.lang = '{$lang}'
        ";

        return $this->list($options, DB::table(DB::raw("({$sql}) as temp")));
    }
}
