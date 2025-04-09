<?php

namespace Omadonex\LaravelTools\Support\ModelView;

use Omadonex\LaravelTools\Support\Tools\Lists;

class ConfigView extends ModelView
{
    protected array $columns = [
        'name'                => ['filter' => self::FILTER_INPUT],
        'description'         => ['filter' => self::FILTER_INPUT, 'className' => 'wrap-words'],
        'value_type_id_label' => ['filter' => self::FILTER_SELECT, 'orderable' => false, 'searchable' => false, 'style' => 'min-width: 200px;', 'keyColumn' => 'value_type_id'],
        'value'               => ['filter' => self::FILTER_INPUT, 'orderable' => false],
    ];

    public function filterCallbackList(string $column): \Closure
    {
        return [
            'value_type_id_label' => Lists::get('configValueType', closure: true),
        ][$column];
    }
}
