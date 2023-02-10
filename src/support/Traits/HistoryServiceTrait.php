<?php

namespace Omadonex\LaravelTools\Support\Traits;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Omadonex\LaravelTools\Support\Models\History;

trait HistoryServiceTrait
{
    public function writeToHistory($userId, Model $model, $eventId, $oldData, $newData)
    {
        $historyModelClass = get_class($model) . 'History';
        /** @var History $historyModel */
        $historyModel = new $historyModelClass;
        $historyModel->model_id = $model->getKey();
        $historyModel->user_id = $userId;
        $historyModel->history_event_id = $eventId;
        $historyModel->data = [
            'old' => $oldData,
            'new' => $newData,
        ];
        $historyModel->occur_at = Carbon::now();
        $historyModel->save();
    }
}
