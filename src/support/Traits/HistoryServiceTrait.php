<?php

namespace Omadonex\LaravelTools\Support\Traits;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Omadonex\LaravelTools\Support\Models\History;

trait HistoryServiceTrait
{
    public function writeToHistory($userId, Model $model, $eventId, $oldData, $newData, $dtFormat = 'd.m.Y H:i:s')
    {
        $historyModelClass = get_class($model) . 'History';
        /** @var History $historyModel */
        $historyModel = new $historyModelClass;
        $historyModel->model_id = $model->getKey();
        $historyModel->user_id = $userId;
        $historyModel->history_event_id = $eventId;

        foreach ($oldData as $key => $value) {
            if ($oldData[$key] instanceof Carbon) {
                $oldData[$key] = $oldData[$key]->format($dtFormat);
            }
        }

        foreach ($newData as $key => $value) {
            if ($newData[$key] instanceof Carbon) {
                $newData[$key] = $newData[$key]->format($dtFormat);
            }
        }

        $historyModel->data = [
            'old' => $oldData,
            'new' => $newData,
        ];
        $historyModel->occur_at = Carbon::now();
        $historyModel->save();
    }
}
