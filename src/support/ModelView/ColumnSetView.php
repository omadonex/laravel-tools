<?php

namespace Omadonex\LaravelTools\Support\ModelView;

class ColumnSetView extends ModelView
{
    protected array $columns = [
        'id'   => ['filter' => self::FILTER_INPUT],
        'name' => ['filter' => self::FILTER_INPUT],
        'table_id' => ['filter' => self::FILTER_INPUT],
        'columns' => ['filter' => self::FILTER_NONE],
    ];
}
