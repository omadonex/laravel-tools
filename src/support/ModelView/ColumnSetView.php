<?php

namespace Omadonex\LaravelTools\Support\ModelView;

use Omadonex\LaravelTools\Support\Tools\Lists;

class ColumnSetView extends ModelView
{
    protected array $columns = [
        'id'   => ['filter' => self::FILTER_INPUT],
        'name' => ['filter' => self::FILTER_INPUT],
        'user_id_label' => ['filter' => self::FILTER_SELECT, 'searchable' => false, 'className' => 'wrap-words', 'keyColumn' => 'user_id'],
        'page_id' => ['filter' => self::FILTER_INPUT],
        'table_id' => ['filter' => self::FILTER_INPUT],
        'columns' => ['filter' => self::FILTER_NONE],
    ];

    public function filterCallbackList(string $column): \Closure
    {
        return [
            'user_id_label' => Lists::get('user', closure: true),
        ][$column];
    }
}
