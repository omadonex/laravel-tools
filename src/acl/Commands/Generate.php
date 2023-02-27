<?php

namespace Omadonex\LaravelTools\Acl\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;
use Nwidart\Modules\Facades\Module;
use Omadonex\LaravelTools\Acl\Interfaces\IRole;
use Omadonex\LaravelTools\Acl\Models\PermissionGroup;
use Omadonex\LaravelTools\Acl\Models\PermissionGroupTranslate;
use Omadonex\LaravelTools\Acl\Models\PermissionTranslate;
use Omadonex\LaravelTools\Acl\Models\RoleTranslate;
use Omadonex\LaravelTools\Acl\Models\Permission;
use Omadonex\LaravelTools\Acl\Models\Role;
use Omadonex\LaravelTools\Acl\Services\RoleService;
use Omadonex\LaravelTools\Support\Classes\ConstCustom;

class Generate extends Command
{
    const NWIDART_CLASS = '\Nwidart\Modules\Facades\Module';
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'omx:acl:generate';

    private RoleService $roleService;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate all data for acl based on config files';

    public function __construct(RoleService $roleService)
    {
        parent::__construct();
        $this->roleService = $roleService;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if (!file_exists(lang_path('vendor/omx-acl'))
            || !file_exists(base_path('config/omx/acl/role.php'))
            || !file_exists(base_path('config/omx/acl/permission.php'))
            || !file_exists(base_path('config/omx/acl/route.php'))) {
            $this->error('Error: main config and lang files are not published!');

            return ;
        }

        Role::protectedGenerate()->delete();
        RoleTranslate::protectedGenerate()->delete();
        Permission::truncate();
        PermissionTranslate::truncate();
        PermissionGroup::truncate();
        PermissionGroupTranslate::truncate();

        \DB::table('acl_pivot_permission_role')->where(ConstCustom::DB_FIELD_PROTECTED_GENERATE, true)->delete();

        $aclEntryList = [
            [
                'configRole' => base_path('config/omx/acl/role.php'),
                'configPermission' => base_path('config/omx/acl/permission.php'),
                'langPath' => lang_path('vendor/omx-acl'),
                'module' => 'app',
            ],
        ];

        //TODO omadonex: проверить генерацию модулей
        if (class_exists(self::NWIDART_CLASS)) {
            foreach (Module::all() as $module) {
                $configPath = $module->getExtraPath('Config/omx/acl');
                $aclEntryList[] = [
                    'configRole' => "{$configPath}/role.php",
                    'configPermission' => "{$configPath}/permission.php",
                    'langPath' => $module->getExtraPath('Config/acl/lang'),
                    'module' => $module->getLowerName(),
                ];
            }
        }

        Model::unguard();

        $permissionList = [];
        $roleList = [];
        foreach ($aclEntryList as $aclEntry) {
            $configRole = file_exists($aclEntry['configRole']) ? include $aclEntry['configRole'] : [];
            $configPermission = file_exists($aclEntry['configPermission']) ? include $aclEntry['configPermission'] : [];
            $langPath = $aclEntry['langPath'];
            if (file_exists($langPath)) {
                $langKeyList = array_diff(scandir($langPath), ['.', '..']);
            } else {
                $langKeyList = [config('app.fallback_locale')];
            }

            $permissionList = array_merge($permissionList, $this->createPermission($configPermission, $langPath, $langKeyList, $aclEntry['module']));
            $roleList = array_merge($roleList, $this->createRole($configRole, $langPath, $langKeyList, $aclEntry['module']));
        }

        $roleIdList = Role::all()->pluck('role_id')->toArray();
        \DB::table('acl_pivot_permission_role')->whereNotIn('permission_id', $permissionList)->delete();
        \DB::table('acl_pivot_permission_user')->whereNotIn('permission_id', $permissionList)->delete();
        \DB::table('acl_pivot_role_user')->whereNotIn('role_id', $roleIdList)->delete();

        Model::reguard();
    }

    /**
     * @param array $data
     * @param string $langPath
     * @param array $langKeyList
     * @param string $module
     * @return array
     */
    private function createRole(array $data, string $langPath, array $langKeyList, string $module): array
    {
        $createdList = [];

        if ($module === 'app') {
            $data[IRole::ROOT] = ['is_staff' => false, 'is_hidden' => true, 'sort_index' => -3];
            $data[IRole::ADMIN] = ['is_staff' => true, 'is_hidden' => false, 'sort_index' => -2];
            $data[IRole::USER] = ['is_staff' => false, 'is_hidden' => false, 'sort_index' => -1];
        }

        foreach ($data as $roleId => $roleData) {
            $createdList[] = $roleId;

            $role = $this->roleService->create([
                'id' => $roleId,
                'is_hidden' => $roleData['hidden'] ?? false,
                'is_staff' => $roleData['staff'] ?? false,
                'sort_index' => $roleData['sort_index'] ?? 0,
                ConstCustom::DB_FIELD_PROTECTED_GENERATE => true,
            ]);

            foreach ($langKeyList as $lang) {
                $langFile = "{$langPath}/{$lang}/role.php";
                $langData = file_exists($langFile) ? include $langFile : [];
                $this->roleService->createT($lang, [
                    'name' => $langData[$roleId]['name'] ?? $roleId,
                    'description'  => $langData[$roleId]['description'] ?? $roleId,
                    ConstCustom::DB_FIELD_PROTECTED_GENERATE => true,
                ], $roleId, Role::class);
            }

            foreach ($roleData['permissions'] ?? [] as $permission) {
                $role->permissions()->attach($permission, [ConstCustom::DB_FIELD_PROTECTED_GENERATE => true]);
            }
        }

        return $createdList;
    }

    /**
     * @param array $data
     * @param string $langPath
     * @param array $langKeyList
     * @param string $module
     * @param string|null $permissionGroupId
     * @return array
     */
    private function createPermission(array $data, string $langPath, array $langKeyList, string $module, string $permissionGroupId = null): array
    {
        $createdList = [];

        if ($permissionGroupId === null) {
            $permissionGroupId = $module;
            $this->createPermissionGroup($permissionGroupId, null, 1, $langPath, $langKeyList);
        }

        $permissionGroupSortIndex = 1;
        foreach ($data as $key => $value) {
            if (is_string($value)) {
                $permissionId = $value;
                $createdList[] = $permissionId;
                Permission::create([
                    'id' => $permissionId,
                    'permission_group_id' => $permissionGroupId,
                ]);

                foreach ($langKeyList as $lang) {
                    $langFile = "{$langPath}/{$lang}/permission.php";
                    $langData = file_exists($langFile) ? include $langFile : [];
                    PermissionTranslate::create([
                        'model_id' => $permissionId,
                        'lang' => $lang,
                        'name' => $langData[$permissionId]['name'] ?? $permissionId,
                        'description'  => $langData[$permissionId]['description'] ?? $permissionId,
                    ]);
                }
            } elseif (is_array($value)) {
                $groupId = $key;
                $permissionList = $value;
                $this->createPermissionGroup($groupId, $permissionGroupId, $permissionGroupSortIndex, $langPath, $langKeyList);
                $createdList = array_merge($createdList, $this->createPermission($permissionList, $langPath, $langKeyList, $module, $groupId));
                $permissionGroupSortIndex++;
            }
        }

        return $createdList;
    }

    private function createPermissionGroup(string $id, ?string $parentId, int $sortIndex, string $langPath, array $langKeyList): void
    {
        if (!PermissionGroup::find($id)) {
            PermissionGroup::create([
                'id' => $id,
                'parent_id' => $parentId,
                'sort_index' => $sortIndex,
            ]);

            foreach ($langKeyList as $lang) {
                $langFile = "{$langPath}/{$lang}/permissionGroup.php";
                $langData = file_exists($langFile) ? include $langFile : [];
                PermissionGroupTranslate::create([
                    'model_id' => $id,
                    'lang' => $lang,
                    'name' => $langData[$id]['name'] ?? $id,
                    'description' => $langData[$id]['description'] ?? $id,
                ]);
            }
        }
    }
}
