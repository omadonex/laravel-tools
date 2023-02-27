<?php

namespace Omadonex\LaravelTools\Support\Services;

use Illuminate\Database\Eloquent\Model;
use Omadonex\LaravelTools\Support\Models\HistoryEvent;
use Omadonex\LaravelTools\Support\Repositories\ModelRepository;
use Omadonex\LaravelTools\Support\Traits\HistoryServiceTrait;

abstract class ModelService
{
    use HistoryServiceTrait;

    protected ModelRepository $modelRepository;

    public function __construct(ModelRepository $modelRepository)
    {
        $this->modelRepository = $modelRepository;
    }

    public function create(array $data, $fresh = true, $stopPropagation = false): Model
    {
        $model = $this->modelRepository->create($data, $fresh, $stopPropagation);
        $this->writeToHistory(app('acl')->id(), $model->getKey(), get_class($model), HistoryEvent::CREATE, [], ['__common' => $data]);

        return $model;
    }

    public function createT(int|string $modelId, string $lang, array $dataT): void
    {
        $this->modelRepository->createT($modelId, $lang, $dataT);
        $this->writeToHistory(app('acl')->id(), $modelId, $this->modelRepository->getTranslateClass(), HistoryEvent::CREATE_T, [], ['__t' => ['__lang' => $lang, '__id' => $modelId] + $dataT]);
    }

    public function createWithT(string $lang, array $data, array $dataT, $fresh = true, $stopPropagation = false): Model
    {
        $model = $this->create($data, $fresh, $stopPropagation);
        $this->createT($model->getKey(), $lang, $dataT);

        return $model;
    }

    public function update(int|string|Model $moid, array $data, bool $returnModel = false, bool $stopPropagation = false): bool|Model
    {
        $model = $this->modelRepository->find($moid);
        $oldData = [];
        foreach ($data as $key => $value) {
            if ($value != $model->$key) {
                $oldData[$key] = $model->$key;
            } else {
                unset($data[$key]);
            }
        }

        if (!empty($data)) {
            $this->writeToHistory(app('acl')->id(), $model->getKey(), get_class($model), HistoryEvent::UPDATE, ['__common' => $oldData], ['__common' => $data]);
        }

        return $this->modelRepository->update($moid, $data, $returnModel, $stopPropagation);
    }

    public function updateT(int|string $id, string $lang, array $dataT): void
    {
        $model = $this->modelRepository->findT($id, $lang);
        $oldData = [];
        foreach ($dataT as $key => $value) {
            if ($value != $model->$key) {
                $oldData[$key] = $model->$key;
            } else {
                unset($dataT[$key]);
            }
        }

        if (!empty($dataT)) {
            $this->writeToHistory(app('acl')->id(), $id, $this->modelRepository->getTranslateClass(), HistoryEvent::UPDATE_T, ['__t' => ['__lang' => $lang, '__id' => $id] + $oldData], ['__t' => ['__lang' => $lang, '__id' => $id] + $dataT]);
        }

        $this->modelRepository->updateT($id, $lang, $dataT);
    }

    public function updateWithT(int|string|Model $moid, string $lang, array $data, array $dataT, bool $returnModel = true, bool $stopPropagation = false): bool|Model
    {
        $id = $moid instanceof Model ? $moid->getKey() : $moid;
        $this->updateT($id, $lang, $dataT);

        return $this->update($moid, $data, $returnModel, $stopPropagation);
    }

    public function delete(int|string|Model $moid): int
    {
        $model = $this->modelRepository->find($moid);
        $id = $model->getKey();

        $this->checkDelete($model);
        $this->modelRepository->delete($model);

        $this->writeToHistory(app('acl')->id(), $id, $this->modelRepository->getTranslateClass(), HistoryEvent::DELETE, [], []);

        return $id;
    }

    public function deleteT(int|string $id, ?string $lang): void
    {
        $this->modelRepository->deleteT($id, $lang);
        $this->writeToHistory(app('acl')->id(), $id, $this->modelRepository->getTranslateClass(), $lang === null ? HistoryEvent::DELETE_T_ALL : HistoryEvent::DELETE_T, [], []);
    }

    public function deleteWithT(int|string|Model $moid, ?string $lang = null): void
    {
        $id = $this->delete($moid);
        $this->deleteT($id, $lang);
    }

    abstract public function checkDelete(Model $model): void;
}
