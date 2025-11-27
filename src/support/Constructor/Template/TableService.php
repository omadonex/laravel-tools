<?php

namespace Omadonex\LaravelTools\Support\Constructor\Template;

use Omadonex\LaravelTools\Acl\Models\Role;
use Omadonex\LaravelTools\Acl\Models\User;
use Omadonex\LaravelTools\Acl\ModelView\RoleView;
use Omadonex\LaravelTools\Acl\ModelView\UserView;
use Omadonex\LaravelTools\Support\ReadyCRUDPages\ConfigPage\Models\Config;
use Omadonex\LaravelTools\Support\ReadyCRUDPages\ConfigPage\ModelView\ConfigView;
use Omadonex\LaravelTools\Support\Services\OmxService;

abstract class TableService extends OmxService implements ITableService
{
    abstract protected function tables(): array;

    private function tablesDefault(): array
    {
        return [
            self::CONFIG => [
                'modelView' => ConfigView::class,
                'title' => 'Параметры системы',
                'path' => Config::getPath(),
                'formPath' => Config::getFormPath(),
                'captions' => [
                    'create' => 'Создание параметра',
                    'edit' => 'Редактирование параметра',
                ],
            ],
            self::ROLE => [
                'modelView' => RoleView::class,
                'title' => 'Системные роли',
                'path' => Role::getPath(),
                'formPath' => Role::getFormPath(),
                'captions' => [
                    'create' => 'Создание роли',
                    'edit' => 'Редактирование роли',
                ],
            ],
            self::USER => [
                'modelView' => UserView::class,
                'title' => 'Системные пользователи',
                'path' => User::getPath(),
                'formPath' => User::getFormPath(),
                'captions' => [
                    'create' => 'Создание пользователя',
                    'edit' => 'Редактирование пользователя',
                ],
                'modalWidth' => 700,
            ],
        ];
    }

    public function data(string $tableIndex): array
    {
        $tables = $this->tablesDefault() + $this->tables();

        return $tables[$tableIndex];
    }

    public function title(string $tableIndex): string
    {
        return $this->data($tableIndex)['title'] ?? '';
    }

    public function getActualData($tableData, $tableInfo): array
    {
        if (!array_key_exists('form', $tableData) && !array_key_exists('formCreate', $tableData) && !array_key_exists('formEdit', $tableData)) {
            $tableData['noForm'] = true;
        } else {
            $tableData['noForm'] = false;
            if (!array_key_exists('formCreate', $tableData)) {
                $tableData['formCreate'] = $tableData['form'];
            }

            if (!array_key_exists('formEdit', $tableData)) {
                $tableData['formEdit'] = $tableData['form'];
            }
        }

        $keyList = [
            'table',
            'modelView',
            'title',
            'noForm',
            'form',
            'formCreate',
            'formEdit',
            'captions',
            'modalWidth',
        ];

        $data = [];
        foreach ($keyList as $key) {
            $data[$key] = $tableInfo[$key] ?? ($tableData[$key] ?? null);
        }

        return $data;
    }
}