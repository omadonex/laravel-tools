<?php
/**
 * Created by PhpStorm.
 * User: omadonex
 * Date: 06.02.2018
 * Time: 21:34
 */

namespace Omadonex\LaravelTools\Support\Repositories;

use Illuminate\Database\Eloquent\Model;
use Omadonex\LaravelTools\Support\Classes\Utils\UtilsFilter;
use Omadonex\LaravelTools\Support\Resources\HistoryResource;

class HistoryRepository extends ModelRepository
{
    protected $filterFieldsTypes = [
        'model_id' => ['type' => UtilsFilter::EQUALS],
    ];

    public function __construct(Model $model)
    {
        parent::__construct($model, HistoryResource::class);
    }

    public function grid($options = [])
    {
        $table = $options['params']['table'];
        $lang = $options['params']['lang'];

        $sql = /* @lang MySQL */ "
        SELECT
            h.id,
            h.model_id,
            h.user_id,
            h.history_event_id,
            h.occur_at,
            h.data,
            het.name as event
        FROM
            {$table} AS h        
            LEFT JOIN support_history_event AS he ON he.id = h.history_event_id            
            LEFT JOIN support_history_event_translate AS het ON het.model_id = h.history_event_id AND het.lang = '{$lang}'        
        ";

        return $this->list($options, \DB::table(\DB::raw("({$sql}) as temp")));
    }
}