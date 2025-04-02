<?php

namespace Omadonex\LaravelTools\Acl\ModelView;

use Omadonex\LaravelTools\Support\ModelView\ModelView;
use Omadonex\LaravelTools\Support\Tools\Lists;

class RoleView extends ModelView
{
    protected array $columns = [
        'id'          => ['type' => 'string', 'filter' => self::FILTER_INPUT],
        'name'        => ['type' => 'translate', 'translate' => ['field' => 'name'], 'filter' => self::FILTER_INPUT],
        'description' => ['type' => 'translate', 'translate' => ['field' => 'description'], 'filter' => self::FILTER_INPUT],
        'is_staff'    => ['type' => 'bool', 'filter' => self::FILTER_SELECT, 'style' => 'min-width: 120px'],
        'is_hidden'   => ['type' => 'bool', 'filter' => self::FILTER_SELECT, 'style' => 'min-width: 120px'],
    ];

    public function filterCallbackList(string $column): \Closure
    {
        return [
            'is_staff' => Lists::get('yesNo', closure: true),
            'is_hidden' => Lists::get('yesNo', closure: true),
        ][$column];
    }
}
