<?php

namespace Omadonex\LaravelTools\Support\Traits;

use Carbon\Carbon;
use Omadonex\LaravelTools\Support\Models\History;

trait HistoryServiceTrait
{
    public function writeToHistory($userId, $modelId, $modelClass, $eventId, $oldData, $newData, $hiddenFields = ['password'], $dtFormat = 'd.m.Y H:i:s')
    {
        $historyModelClass = "{$modelClass}History";
        /** @var History $historyModel */
        $historyModel = new $historyModelClass;
        $historyModel->model_id = $modelId;
        $historyModel->user_id = $userId;
        $historyModel->history_event_id = $eventId;

        foreach ($oldData as $specKey => $data) {
            foreach ($data as $key => $value) {
                if ($oldData[$specKey][$key] instanceof Carbon) {
                    $oldData[$specKey][$key] = $oldData[$specKey][$key]->format($dtFormat);
                }

                if (in_array($key, $hiddenFields)) {
                    $oldData[$specKey][$key] = __('omx-support::history.hiddenFieldValue');
                }
            }
        }

        foreach ($newData as $specKey => $data) {
            foreach ($data as $key => $value) {
                if ($newData[$specKey][$key] instanceof Carbon) {
                    $newData[$specKey][$key] = $newData[$specKey][$key]->format($dtFormat);
                }

                if (in_array($key, $hiddenFields)) {
                    $newData[$specKey][$key] = __('omx-support::history.hiddenFieldValue');
                }
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
