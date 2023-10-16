<?php

namespace Omadonex\LaravelTools\Support\Tools;


use Omadonex\LaravelTools\Support\ModelView\ModelView;

class View
{
    public static function columnsInfo(ModelView $view, array $filter, string $tableId): array
    {
        $filterColumns = data_get($filter, [$tableId, 'columns']);
        $columnsData = $view->getColumnsData(empty($filterColumns) ? [] : json_decode($filterColumns));
        $columns = array_keys($columnsData);

        return [$columnsData, $columns];
    }
}
