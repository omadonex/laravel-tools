<?php

namespace Omadonex\LaravelTools\Support\Repositories;

use Illuminate\Support\Facades\DB;
use Omadonex\LaravelTools\Support\Classes\Utils\UtilsFilter;
use Omadonex\LaravelTools\Support\Classes\Utils\UtilsUserLabel;
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

    public function grid($options = [])
    {
        $userSql = UtilsUserLabel::selectSql('user_id');

        $sql = /* @lang MySQL */ "
            SELECT
                cs.id,
                cs.name,                
                cs.user_id,
                cs.page_id,
                cs.table_id,
                cs.columns,
                {$userSql}
            FROM
                support_column_set AS cs                
                left join users as u on u.id = cs.user_id
        ";

        return $this->list($options, DB::table(DB::raw("({$sql}) as temp")));
    }
}
