<?php

namespace Omadonex\LaravelTools\Support\Repositories;

use Omadonex\LaravelTools\Support\Classes\Utils\UtilsFilter;
use Omadonex\LaravelTools\Support\Models\ColumnSet;
use Omadonex\LaravelTools\Support\Resources\ColumnSetResource;

class ColumnSetRepository extends ModelRepository
{
    protected $filterFieldsTypes = [
        'table_id' => ['type' => UtilsFilter::EQUALS],
    ];

    public function __construct(ColumnSet $tableColumnSetting)
    {
        parent::__construct($tableColumnSetting, ColumnSetResource::class);
    }

    public function getList(int $userId, string $pageId, string $tableId): array
    {
        return ['' => __('placeholders.filter_columns')] + $this->model->query()
            ->where(function($query) use ($userId) {
                $query
                    ->where('user_id', $userId)
                    ->orWhere('user_id', 0);
            })
            ->where('page_id', $pageId)
            ->where('table_id', $tableId)
            ->get()
            ->map(fn (ColumnSet $columnSetting) => [
                'name'    => $columnSetting->name,
                'columns' => $columnSetting->getAttributes()['columns']
            ])
            ->pluck('name', 'columns')
            ->toArray()
        ;
    }
}
