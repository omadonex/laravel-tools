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

    public function createWithT(string $lang, array $data, array $dataT, $fresh = true, $stopPropagation = false): Model
    {
        $model = $this->modelRepository->createWithT($lang, $data, $dataT, $fresh, $stopPropagation);
        $this->writeToHistory(app('acl')->id(), $model->getKey(), get_class($model), HistoryEvent::CREATE_WITH_T, [], ['__common' => $data, '__t' => ['__lang' => $lang] + $dataT]);

        return $model;
    }

    public function createT(string $lang, array $dataT, int|string $id, string $class): void
    {
        $this->modelRepository->createT($lang, $id, $dataT);
        $this->writeToHistory(app('acl')->id(), $id, $class, HistoryEvent::CREATE_T, [], ['__t' => ['__lang' => $lang] + $dataT]);
    }

    public function update(int $id, array $data, $returnModel = false, $stopPropagation = false): Model | bool
    {
        $model = $this->modelRepository->find($id);
        $oldData = [];
        foreach ($data as $key => $value) {
            if ($value != $model->$key) {
                $oldData[$key] = $model->$key;
            } else {
                unset($data[$key]);
            }
        }
        $this->writeToHistory(app('acl')->id(), $model, HistoryEvent::UPDATE, $oldData, $data);

        return $this->modelRepository->update($id, $data, $returnModel, $stopPropagation);
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
