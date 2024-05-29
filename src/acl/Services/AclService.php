<?php

namespace Omadonex\LaravelTools\Acl\Services;

use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Route;
use Omadonex\LaravelTools\Acl\Interfaces\IAclRepository;
use Omadonex\LaravelTools\Acl\Interfaces\IAclService;
use Omadonex\LaravelTools\Acl\Interfaces\IRole;
use Omadonex\LaravelTools\Acl\Models\Role;
use Omadonex\LaravelTools\Acl\Models\User;
use Omadonex\LaravelTools\Acl\Repositories\AclRepository;
use Omadonex\LaravelTools\Support\Services\OmxService;

class AclService extends OmxService implements IAclService
{
    protected AclRepository $aclRepository;

    protected $mode;
    protected bool $deepMode;
    protected array $routeMap;

    protected ?User $user;
    protected Collection $roleList;
    protected Collection $permissionList;

    public function __construct(array $moduleArr = [])
    {
        $this->aclRepository = new AclRepository;
        $this->mode = config('omx.acl.acl.mode', self::MODE_DENY);
        $this->deepMode = config('omx.acl.acl.deepMode', true);
        $this->routeMap = $this->generateRouteMap($moduleArr);

        $this->user = null;
        $this->roleList = collect();
        $this->permissionList = collect();
    }

    public function isDeepMode(): bool
    {
        return $this->deepMode;
    }

    public function isLoggedIn(): bool
    {
        return (bool) $this->user;
    }

    public function user(): ?User
    {
        return $this->user;
    }

    public function id(): ?int
    {
        if (!$this->user) {
            return app()->runningInConsole() ? self::CONSOLE_USER_ID : self::SYSTEM_USER_ID;
        }

        return $this->user->getKey();
    }

    public function roles(bool $onlyIds = false): array
    {
        return $onlyIds ? $this->roleList->map->id->toArray() : $this->roleList->toArray();
    }

    public function permissions(bool $onlyIds = false): array
    {
        return $onlyIds ? $this->permissionList->map->id->toArray() : $this->permissionList->toArray();
    }

    public function isRoot(?User $user = null): bool
    {
        list ($roleList, $permissionList) = $this->getRawData($user);

        return $this->rawDataCheckRole($roleList, IRole::ROOT, strict: true);
    }

    public function isAdmin(?User $user = null): bool
    {
        list ($roleList, $permissionList) = $this->getRawData($user);

        return $this->rawDataCheckRole($roleList, IRole::ADMIN, strict: true);
    }

    public function isUser(?User $user = null): bool
    {
        list ($roleList, $permissionList) = $this->getRawData($user);

        return $this->rawDataCheckRole($roleList, IRole::USER, strict: true);
    }

    public function hasAdminAccess(?User $user = null): bool
    {
        list ($roleList, $permissionList) = $this->getRawData($user);

        return $this->rawDataCheckRole($roleList, IRole::ADMIN);
    }

    public function attachRole(array|string $role, ?User $user = null): void
    {
        $user = $user ?: $this->user;
        if ($user) {
            $this->aclRepository->addRole($user, $role);
        }
    }

    public function detachRole(array|string $role, ?User $user = null): void
    {
        $user = $user ?: $this->user;
        if ($user) {
            $this->aclRepository->removeRole($user ?: $this->user, $role);
        }
    }

    public function repository(): IAclRepository
    {
        return $this->aclRepository;
    }

    public function check(array|string $permission, string $type = self::CHECK_TYPE_AND, ?User $user = null): bool
    {
        list ($roleList, $permissionList) = $this->getRawData($user);
        
        return $this->rawDataCheck($roleList, $permissionList, $permission, $type);
    }

    public function checkRole(array|string $role, string $type = self::CHECK_TYPE_AND, bool $strict = false, ?User $user = null): bool
    {
        list ($roleList, $permissionList) = $this->getRawData($user);

        return $this->rawDataCheckRole($roleList, $role, $type, $strict);
    }

    public function checkRoute(string $routeName, ?User $user = null): bool
    {
        list ($roleList, $permissionList) = $this->getRawData($user);

        return $this->rawDataCheckRoute($roleList, $permissionList, $routeName);
    }

    public function setUser(?User $user): void
    {
        if ($user) {
            list ($roleList, $permissionList) = $this->loadCheckedUserData($user);

            $this->roleList = $roleList;
            $this->permissionList = $permissionList;
        }

        $this->user = $user;
    }

    public function getRoutesData(): array
    {
        $routes = Route::getRoutes()->getRoutes();
        $routesData = [
            IAclService::SECTION_DENIED => [],
            IAclService::SECTION_ALLOWED => [],
            IAclService::SECTION_PROTECTED_ROLE => [],
            IAclService::SECTION_PROTECTED_PERMISSION => [],
            IAclService::SECTION_UNSAFE => [],
        ];

        /** @var \Illuminate\Routing\Route $route */
        foreach ($routes as $route) {
            $routeName = $route->getName();
            $routePath = $route->uri();
            $routeMethods = $route->methods();

            if (!$routeName) {
                $routesData[IAclService::SECTION_UNSAFE][] = [
                    'name' => $routeName,
                    'path' => $routePath,
                    'action' => $route->getActionName(),
                    'methods' => $routeMethods,
                ];
                continue;
            }

            //Ищем в группе запрещенных для всех роутов
            if ($this->searchInSection($routeName, IAclService::SECTION_DENIED) === true) {
                $routesData[IAclService::SECTION_DENIED][] = [
                    'name' => $routeName,
                    'path' => $routePath,
                    'methods' => $routeMethods,
                ];
                continue;
            }

            //Ищем в группе разрешенных для всех роутов
            if ($this->searchInSection($routeName, IAclService::SECTION_ALLOWED) === true) {
                $routesData[IAclService::SECTION_ALLOWED][] = [
                    'name' => $routeName,
                    'path' => $routePath,
                    'methods' => $routeMethods,
                ];
                continue;
            }

            //Ищем в группе защищенных роутов (ROLE)
            $accessData = $this->searchInSection($routeName, IAclService::SECTION_PROTECTED_ROLE);
            if ($accessData !== false) {
                $routesData[IAclService::SECTION_PROTECTED_ROLE][] = [
                    'name' => $routeName,
                    'path' => $routePath,
                    'accessData' => $accessData,
                    'methods' => $routeMethods,
                ];
                continue;
            }

            //Ищем в группе защищенных роутов (PERMISSION)
            $accessData = $this->searchInSection($routeName, IAclService::SECTION_PROTECTED_PERMISSION);
            if ($accessData !== false) {
                $routesData[IAclService::SECTION_PROTECTED_PERMISSION][] = [
                    'name' => $routeName,
                    'path' => $routePath,
                    'accessData' => $accessData,
                    'methods' => $routeMethods,
                ];
                continue;
            }

            $routesData[IAclService::SECTION_UNSAFE][] = [
                'name' => $routeName,
                'path' => $routePath,
                'action' => $route->getActionName(),
                'methods' => $routeMethods,
            ];
        }

        $comparator = function ($a, $b) {
            if ($a['path'] === $b['path']) {
                return 0;
            }

            return ($a['path'] < $b['path']) ? -1 : 1;
        };

        usort($routesData[self::SECTION_DENIED], $comparator);
        usort($routesData[self::SECTION_ALLOWED], $comparator);
        usort($routesData[self::SECTION_PROTECTED_ROLE], $comparator);
        usort($routesData[self::SECTION_PROTECTED_PERMISSION], $comparator);
        usort($routesData[self::SECTION_UNSAFE], $comparator);

        return $routesData;
    }

    private function checkAssignDates($value, $nowTs): bool
    {
        //TODO omadonex: корректность проверки даты, учитывая таймзоны

        if ($value->assign_starting_at === null && $value->assign_expires_at === null) {
            return true;
        }

        if ($value->assign_starting_at !== null && $value->assign_expires_at === null) {
            return $value->assign_starting_at < $nowTs;
        }

        if ($value->assign_starting_at === null && $value->assign_expires_at !== null) {
            return $value->assign_expires_at > $nowTs;
        }

        return $value->assign_starting_at < $nowTs && $value->assign_expires_at > $nowTs;
    }

    private function loadCheckedUserData(User $user): array
    {
        $relations = ['roles'];
        if ($this->isDeepMode()) {
            $relations[] = 'roles.permissions';
            $relations[] = 'permissions';
        }
        $user->load($relations);

        $nowTs = Carbon::now()->timestamp;
        $roleList = $user->roles->filter(function ($value, $key) use ($nowTs) {
            return $this->checkAssignDates($value, $nowTs);
        });

        if (!$roleList->count()) {
            $roleList->push(Role::find(IRole::USER));
        }

        if ($this->isDeepMode()) {
            $permissionList = collect();
            foreach ($roleList as $role) {
                $permissionList = $permissionList->concat($role->permissions);
            }
            //Персонально назначенные пользователю привилегии могут иметь срок истечения
            $userPermissionList = $user->permissions->filter(function ($value, $key) use ($nowTs) {
                return $this->checkAssignDates($value, $nowTs);
            });
            $permissionList = $permissionList->concat($userPermissionList);
            $permissionList = $permissionList->unique->id->values();
        }

        return [$roleList, $permissionList];
    }

    private function getRawData(?User $user = null): array
    {
        if ($user) {
            return $this->loadCheckedUserData($user);
        }

        return [$this->roleList, $this->permissionList];
    }

    private function rawDataCheck(Collection $roleList, Collection $permissionList, array|string $permission, string $type = self::CHECK_TYPE_AND): bool
    {
        if (array_intersect([IRole::ROOT, IRole::ADMIN], $roleList->map->id->toArray())) {
            return true;
        }

        if (!is_array($permission)) {
            $permission = [$permission];
        }

        if ($type === self::CHECK_TYPE_AND) {
            return !array_diff($permission, $permissionList->toArray());
        }

        if ($type === self::CHECK_TYPE_OR) {
            return (bool)array_intersect($permission, $permissionList->toArray());
        }

        return false;
    }

    private function rawDataCheckRole(Collection $roleList, array|string $role, string $type = self::CHECK_TYPE_AND, bool $strict = false): bool
    {
        if (!$strict && array_intersect([IRole::ROOT, IRole::ADMIN], $roleList->map->id->toArray())) {
            return true;
        }

        if (!is_array($role)) {
            $role = [$role];
        }

        if ($type === self::CHECK_TYPE_AND) {
            return !array_diff($role, $roleList->map->id->toArray());
        }

        if ($type === self::CHECK_TYPE_OR) {
            return (bool)array_intersect($role, $roleList->map->id->toArray());
        }

        return false;
    }

    private function rawDataCheckRoute(Collection $roleList, Collection $permissionList, string $routeName): bool
    {
        //User is ROOT - assumes has access
        if (array_intersect([IRole::ROOT], $roleList->map->id->toArray())) {
            return true;
        }

        //Ищем в группе запрещенных для всех роутов
        $exists = $this->searchInSection($routeName, self::SECTION_DENIED);
        if ($exists === true) {
            return false;
        }

        //Ищем в группе разрешенных для всех роутов
        $exists = $this->searchInSection($routeName, self::SECTION_ALLOWED);
        if ($exists === true) {
            return true;
        }

        //Ищем в группе защищенных роутов (ROLE)
        $accessData = $this->searchInSection($routeName, self::SECTION_PROTECTED_ROLE);
        if ($accessData !== false) {
            if (is_array($accessData)) {
                $type = $accessData['type'] ?? self::CHECK_TYPE_AND;
                $accessData = $accessData['access'];
            } else {
                $type = self::CHECK_TYPE_AND;
            }

            return $this->rawDataCheckRole($roleList, $accessData, $type);
        }

        //Ищем в группе защищенных роутов (PERMISSION)
        $accessData = $this->searchInSection($routeName, self::SECTION_PROTECTED_PERMISSION);
        if ($accessData !== false) {
            if (is_array($accessData)) {
                $type = $accessData['type'] ?? self::CHECK_TYPE_AND;
                $accessData = $accessData['access'];
            } else {
                $type = self::CHECK_TYPE_AND;
            }

            return $this->rawDataCheck($roleList, $permissionList, $accessData, $type);
        }

        //Если не нашли (роут не указан нигде, значит действуем по настройке режима Acl)
        return $this->mode === self::MODE_ALLOW;
    }

    private function generateRouteMap(array $moduleArr = []): array
    {
        $routeMapEntryList = [config('omx.acl.route')];
        foreach ($moduleArr as $module) {
            $lowerName = $module->getLowerName();
            $routeMapEntryList[] = config("{$lowerName}::omx.acl.route");
        }

        $routeMap = [];
        foreach ([
            IAclService::SECTION_DENIED,
            IAclService::SECTION_ALLOWED,
            IAclService::SECTION_PROTECTED_ROLE,
            IAclService::SECTION_PROTECTED_PERMISSION,
        ] as $section) {
            $routeMap[$section] = [];
            foreach ($routeMapEntryList as $entry) {
                if ($entry) {
                    $routeMap[$section] = array_merge($routeMap[$section], $entry[$section]);
                }
            }
        }

        return $routeMap;
    }

    private function searchInSection(string $routeName, string $section)
    {
        //Ищем в группе роутов обычным способом
        $routes = $this->routeMap[$section];

        //Т.к. в группах deny и allow роуты указаны без ключей, просто перебором, то по группе определяем тип поиска
        if (in_array($section, [self::SECTION_PROTECTED_ROLE, self::SECTION_PROTECTED_PERMISSION])) {
            if (array_key_exists($routeName, $routes)) {
                return $routes[$routeName];
            }
        } else {
            if (in_array($routeName, $routes)) {
                return true;
            }
        }

        //Ищем в группе роутов через wildcard
        return $this->findWildcard($routeName, $routes, in_array($section, [self::SECTION_PROTECTED_ROLE, self::SECTION_PROTECTED_PERMISSION]));
    }

    /**
     * @param string $routeName
     * @param array $routes
     * @param bool $assoc
     * @return array|string|bool
     */
    private function findWildcard(string $routeName, array $routes, bool $assoc = true)
    {
        $routeNameArr = explode('.', $routeName);
        $countParts = count($routeNameArr);
        if ($countParts > 1) {
            $key = null;
            $i = 0;
            $routeNamePath = $routeNameArr[$i];
            while ($i < $countParts - 1) {
                $routeKey = "{$routeNamePath}.*";
                if ($assoc) {
                    if (array_key_exists($routeKey, $routes)) {
                        $key = $routeKey;
                    }
                } else {
                    if (in_array($routeKey, $routes)) {
                        return true;
                    }
                }

                $routeNamePath .= '.' . $routeNameArr[++$i];
            }

            if ($assoc && $key) {
                return $routes[$key];
            }
        }

        return false;
    }
}