<?php

namespace Omadonex\LaravelTools\Support\Constructor\Template;

use Omadonex\LaravelTools\Acl\Models\Role;
use Omadonex\LaravelTools\Acl\Models\User;
use Omadonex\LaravelTools\Acl\ModelView\RoleView;
use Omadonex\LaravelTools\Acl\ModelView\UserView;
use Omadonex\LaravelTools\Support\Services\OmxService;

abstract class TableService extends OmxService implements ITableService
{
    abstract protected function tables(): array;

    private function tablesDefault(): array
    {
        return [
            self::ROLE => [
                'modelView' => RoleView::class,
                'title' => 'Системные роли',
                'path' => Role::getPath(),
                'captions' => [
                    'create' => 'Создание роли',
                    'edit' => 'Редактирование роли',
                ],
            ],
            self::USER => [
                'modelView' => UserView::class,
                'title' => 'Системные пользователи',
                'path' => User::getPath(),
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
}