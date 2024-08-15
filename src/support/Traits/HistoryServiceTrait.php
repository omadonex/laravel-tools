<?php

namespace Omadonex\LaravelTools\Support\Traits;

use Carbon\Carbon;
use Omadonex\LaravelTools\Support\Models\History;

trait HistoryServiceTrait
{
    public function writeToHistory($userId, $modelId, $modelClass, $eventId, $oldData, $newData, $dtFormat = 'd.m.Y H:i:s')
    {
        $historyModelClass = "{$modelClass}History";
        /** @var History $historyModel */
        $historyModel = new $historyModelClass;
        $historyModel->model_id = $modelId;
        $historyModel->user_id = $userId;
        $historyModel->history_event_id = $eventId;

        $hiddenFields = defined("{$historyModelClass}::HIDDEN_FIELDS") ? $historyModel::HIDDEN_FIELDS : [];
        $simpleFields = defined("{$historyModelClass}::SIMPLE_FIELDS") ? $historyModel::SIMPLE_FIELDS : [];
        $ignoreFields = defined("{$historyModelClass}::IGNORE_FIELDS") ? $historyModel::IGNORE_FIELDS : [];

        foreach ($oldData as $specKey => $data) {
            foreach ($data as $key => $value) {
                if (in_array($key, $ignoreFields)) {
                    unset($oldData[$specKey][$key]);
                    continue;
                }

                if ($oldData[$specKey][$key] instanceof Carbon) {
                    $oldData[$specKey][$key] = $oldData[$specKey][$key]->format($dtFormat);
                }

                if (in_array($key, $hiddenFields)) {
                    $oldData[$specKey][$key] = __('omx-support::history.hiddenFieldValue');
                }

                if (in_array($key, $simpleFields)) {
                    $oldData[$specKey][$key] = __('omx-support::history.simpleFieldValue');
                }
            }
        }

        foreach ($newData as $specKey => $data) {
            foreach ($data as $key => $value) {
                if (in_array($key, $ignoreFields)) {
                    unset($newData[$specKey][$key]);
                    continue;
                }

                if ($newData[$specKey][$key] instanceof Carbon) {
                    $newData[$specKey][$key] = $newData[$specKey][$key]->format($dtFormat);
                }

                if (in_array($key, $hiddenFields)) {
                    $newData[$specKey][$key] = __('omx-support::history.hiddenFieldValue');
                }

                if (in_array($key, $simpleFields)) {
                    $newData[$specKey][$key] = __('omx-support::history.simpleFieldValue');
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
