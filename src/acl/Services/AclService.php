<?php

namespace Omadonex\LaravelTools\Acl\Services;

use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Route;
use Omadonex\LaravelTools\Acl\Interfaces\IAclService;
use Omadonex\LaravelTools\Acl\Interfaces\IRole;
use Omadonex\LaravelTools\Acl\Models\Role;

class AclService implements IAclService
{
    protected $mode;
    protected bool $deepMode;
    protected array $routeMap;

    protected $user;
    protected Collection $roleList;
    protected Collection $permissionList;

    protected array $relationArr;

    public function __construct(array $moduleArr = [])
    {
        $this->mode = config('omx.acl.acl.mode', self::MODE_DENY);
        $this->deepMode = config('omx.acl.acl.deepMode', true);
        $this->routeMap = $this->generateRouteMap($moduleArr);

        $this->user = null;
        $this->roleList = collect();
        $this->permissionList = collect();

        $this->relationArr = [];
    }

    public function isDeepMode(): bool
    {
        return $this->deepMode;
    }

    public function isLoggedIn(): bool
    {
        return (bool) $this->user;
    }

    public function user()
    {
        return $this->user;
    }

    public function id(): ?int
    {
        if (!$this->user) {
            return null;
        }

        return $this->user->getKey();
    }

    public function roles(bool $onlyNames = false): array
    {
        return $onlyNames ? $this->roleList->map->id->toArray() : $this->roleList->toArray();
    }

    public function permissions(bool $onlyNames = false): array
    {
        return $onlyNames ? $this->permissionList->map->id->toArray() : $this->permissionList->toArray();
    }

    public function isRoot(): bool
    {
        return $this->roleList->count() === 1 && $this->roleList->first()->id === IRole::ROOT;
    }

    public function isAdmin(): bool
    {
        return $this->roleList->count() === 1 && $this->roleList->first()->id === IRole::ADMIN;
    }

    public function isUser(): bool
    {
        return $this->roleList->count() === 1 && $this->roleList->first()->id === IRole::USER;
    }

    public function check(array|string $permission, string $type = self::CHECK_TYPE_AND): bool
    {
        //User not logged in - assumes no permission
        if (!$this->isLoggedIn()) {
            return false;
        }

        //User is ROOT or ADMIN - assumes has permission
        if ($this->isRoot() || $this->isAdmin()) {
            return true;
        }

        if (!is_array($permission)) {
            $permission = [$permission];
        }

        if ($type === self::CHECK_TYPE_AND) {
            return !array_diff($permission, $this->permissionList->toArray());
        }

        if ($type === self::CHECK_TYPE_OR) {
            return (bool)array_intersect($permission, $this->permissionList->toArray());
        }

        return false;
    }

    public function checkRole(array|string $role, string $type = self::CHECK_TYPE_AND, bool $strict = false): bool
    {
        //User not logged in - assumes no role
        if (!$this->isLoggedIn()) {
            return false;
        }

        //Not strict check and User is ROOT or ADMIN - assumes has role
        if (!$strict && ($this->isRoot() || $this->isAdmin())) {
            return true;
        }

        if (!is_array($role)) {
            $role = [$role];
        }

        if ($type === self::CHECK_TYPE_AND) {
            return !array_diff($role, $this->roleList->map->id->toArray());
        }

        if ($type === self::CHECK_TYPE_OR) {
            return (bool)array_intersect($role, $this->roleList->map->id->toArray());
        }

        return false;
    }

    public function checkRoute(string $routeName): bool
    {
        //User not logged in - assumes no access
        if (!$this->isLoggedIn()) {
            return false;
        }

        //User is ROOT - assumes has access
        if ($this->isRoot()) {
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

        //Ищем в группе роутов с разрешениями
        $permission = $this->searchInSection($routeName, self::SECTION_PROTECTED);
        //Если не нашли (роут не указан нигде, значит действуем по настройке режима Acl)
        if ($permission === false) {
            return $this->mode === self::MODE_ALLOW;
        }

        //Проверяем либо группу разрешений, либо одно разрешение
        if (is_array($permission)) {
            $type = $permission['type'] ?? self::CHECK_TYPE_AND;
            $permission = $permission['permission'];
        } else {
            $type = self::CHECK_TYPE_AND;
        }

        return $this->check($permission, $type);
    }

    public function checkForUser($user, array|string $permission, string $type = self::CHECK_TYPE_AND): bool
    {
        return $this->runForUser($user, 'check', [$permission, $type]);
    }

    public function checkRoleForUser($user, array|string $role, string $type = self::CHECK_TYPE_AND, bool $strict = false): bool
    {
        return $this->runForUser($user, 'checkRole', [$role, $type, $strict]);
    }

    public function checkRouteForUser($user, string $routeName): bool
    {
        return $this->runForUser($user, 'checkRoute', [$routeName]);
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

    public function setUser($user): void
    {
        $this->user = $user;

        $relations = ['roles'];
        if ($this->isDeepMode()) {
            $relations[] = 'roles.permissions';
            $relations[] = 'permissions';
        }
        $user->load($relations);

        $nowTs = Carbon::now()->timestamp;
        $this->roleList = $user->roles->filter(function ($value, $key) use ($nowTs) {
            return $this->checkAssignDates($value, $nowTs);
        });

        if (!$this->roleList->count()) {
            $this->roleList->push(Role::find(IRole::USER));
        }

        if ($this->isDeepMode()) {
            foreach ($this->roleList as $role) {
                $this->permissionList = $this->permissionList->concat($role->permissions);
            }
            //Персонально назначенные пользователю привилегии могут иметь срок истечения
            $userPermissionList = $user->permissions->filter(function ($value, $key) use ($nowTs) {
                return $this->checkAssignDates($value, $nowTs);
            });
            $this->permissionList = $this->permissionList->concat($userPermissionList);
            $this->permissionList = $this->permissionList->unique->id->values();
        }
    }

    public function getRoutesData(): array
    {
        $routes = Route::getRoutes()->getRoutes();
        $routesData = [];

        /** @var \Illuminate\Routing\Route $route */
        foreach ($routes as $route) {
            $routeName = $route->getName();
            $routePath = $route->getPath();
            $routeMethods = $route->getMethods();

            //Ищем в группе запрещенных для всех роутов
            if ($this->searchInSection($routeName, self::SECTION_DENIED) === true) {
                $routesData[self::SECTION_DENIED][] = ['name' => $routeName, 'path' => $routePath, 'methods' => $routeMethods];
                continue;
            }

            //Ищем в группе разрешенных для всех роутов
            if ($this->searchInSection($routeName, self::SECTION_ALLOWED) === true) {
                $routesData[self::SECTION_ALLOWED][] = ['name' => $routeName, 'path' => $routePath, 'methods' => $routeMethods];
                continue;
            }

            //Ищем в группе роутов с разрешениями
            $permission = $this->searchInSection($routeName, self::SECTION_PROTECTED);
            if ($permission === false) {
                $routesData[self::SECTION_UNSAFE][] = ['name' => $routeName, 'path' => $routePath, 'action' => $route->getActionName(), 'methods' => $routeMethods];
            } else {
                $routesData[self::SECTION_PROTECTED][] = ['name' => $routeName, 'path' => $routePath, 'permissionData' => $permission, 'methods' => $routeMethods];
            }
        }

        $comparator = function ($a, $b) {
            if ($a['path'] === $b['path']) {
                return 0;
            }

            return ($a['path'] < $b['path']) ? -1 : 1;
        };

        usort($routesData[self::SECTION_DENIED], $comparator);
        usort($routesData[self::SECTION_ALLOWED], $comparator);
        usort($routesData[self::SECTION_PROTECTED], $comparator);
        usort($routesData[self::SECTION_UNSAFE], $comparator);

        return $routesData;
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
            IAclService::SECTION_PROTECTED,
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
        if ($section === self::SECTION_PROTECTED) {
            if (array_key_exists($routeName, $routes)) {
                return $routes[$routeName];
            }
        } else {
            if (in_array($routeName, $routes)) {
                return true;
            }
        }

        //Ищем в группе роутов через wildcard
        return $this->findWildcard($routeName, $routes, $section === self::SECTION_PROTECTED);
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

    private function runForUser($user, $func, $params)
    {
        $currUser = $this->user;
        $currPermissions = $this->permissionList;
        $currRoles = $this->roleList;

        $this->setUser($user);
        $result = call_user_func_array([$this, $func], $params);

        $this->user = $currUser;
        $this->permissions = $currPermissions;
        $this->roles = $currRoles;

        return $result;
    }
}