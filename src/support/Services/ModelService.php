<?php

namespace Omadonex\LaravelTools\Support\Services;

use Illuminate\Database\Eloquent\Model;
use Omadonex\LaravelTools\Support\Models\HistoryEvent;
use Omadonex\LaravelTools\Support\Repositories\ModelRepository;
use Omadonex\LaravelTools\Support\Traits\HistoryServiceTrait;

abstract class ModelService
{
    use HistoryServiceTrait;

    protected $modelRepository;

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

    public function createT(string $lang, array $dataT, int|string $id, string $class): void
    {
        $this->modelRepository->createT($lang, $id, $dataT);
        $this->writeToHistory(app('acl')->id(), $id, $class, HistoryEvent::CREATE_T, [], ['__t' => ['__lang' => $lang, '__id' => $id] + $dataT]);
    }

    public function createWithT(string $lang, array $data, array $dataT, $fresh = true, $stopPropagation = false): Model
    {
        $model = $this->create($data, $fresh, $stopPropagation);
        $this->createT($lang, $dataT, $model->getKey(), get_class($model));

        return $model;
    }

    public function update(int|string|Model $modelOrId, array $data, bool $returnModel = false, bool $stopPropagation = false): bool|Model
    {
        $model = $this->modelRepository->find($modelOrId);
        $oldData = [];
        foreach ($data as $key => $value) {
            if ($value != $model->$key) {
                $oldData[$key] = $model->$key;
            } else {
                unset($data[$key]);
            }
        }
        $this->writeToHistory(app('acl')->id(), $model->getKey(), get_class($model), HistoryEvent::UPDATE, ['__common' => $oldData], ['__common' => $data]);

        return $this->modelRepository->update($modelOrId, $data, $returnModel, $stopPropagation);
    }

    public function updateT(string $lang, array $dataT, int|string $id, string $class): void
    {
        $model = $this->modelRepository->findT($lang, $id);
        $oldData = [];
        foreach ($dataT as $key => $value) {
            if ($value != $model->$key) {
                $oldData[$key] = $model->$key;
            } else {
                unset($dataT[$key]);
            }
        }
        $this->writeToHistory(app('acl')->id(), $id, $class, HistoryEvent::UPDATE_T, ['__t' => ['__lang' => $lang, '__id' => $id] + $oldData], ['__t' => ['__lang' => $lang, '__id' => $id] + $dataT]);
        $this->modelRepository->updateT($lang, $id, $dataT);
    }

    public function updateWithT(string $lang, int|string|Model $modelOrId, array $data, array $dataT, bool $returnModel = true, bool $stopPropagation = false): bool|Model
    {
        $model = $this->update($modelOrId, $data, true, $stopPropagation);
        $this->updateT($lang, $dataT, $modelOrId, get_class($model));

        return $returnModel ? $model : true;
    }

    public function delete($id): void
    {
        $model = $this->modelRepository->find($id);

        $this->checkDelete($model);
        $this->modelRepository->destroy($id);
        $this->writeToHistory(app('acl')->id(), $model, HistoryEvent::DELETE, [], []);
    }

    abstract public function checkDelete(Model $model): void;
}
