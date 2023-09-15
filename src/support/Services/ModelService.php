<?php

namespace Omadonex\LaravelTools\Support\Services;

use Illuminate\Database\Eloquent\Model;
use Omadonex\LaravelTools\Acl\Interfaces\IAclService;
use Omadonex\LaravelTools\Locale\Interfaces\ILocaleService;
use Omadonex\LaravelTools\Support\Events\ModelCreated;
use Omadonex\LaravelTools\Support\Events\ModelCreatedT;
use Omadonex\LaravelTools\Support\Events\ModelDeleted;
use Omadonex\LaravelTools\Support\Events\ModelDeletedT;
use Omadonex\LaravelTools\Support\Events\ModelUpdated;
use Omadonex\LaravelTools\Support\Events\ModelUpdatedT;
use Omadonex\LaravelTools\Support\Interfaces\Repositories\IModelService;
use Omadonex\LaravelTools\Support\Repositories\ModelRepository;
use Omadonex\LaravelTools\Support\Traits\HistoryServiceTrait;

abstract class ModelService extends OmxService implements IModelService
{
    use HistoryServiceTrait;

    protected ModelRepository $modelRepository;
    protected IAclService $aclService;
    protected ILocaleService $localeService;

    public function __construct(ModelRepository $modelRepository, IAclService $aclService, ILocaleService $localeService)
    {
        $this->modelRepository = $modelRepository;
        $this->aclService = $aclService;
        $this->localeService = $localeService;
    }

    public function repository(): ModelRepository
    {
        return $this->modelRepository;
    }

    public function create(array $data, bool $fresh = true, bool $event = true): Model
    {
        $model = $this->modelRepository->create($data, $fresh);

        if ($event) {
            event(new ModelCreated($model->getKey(), $this->modelRepository->getModelClass(), $this->aclService->id(), $data, $model));
        }

        return $model;
    }

    public function createT(int|string $modelId, string $lang, array $dataT, bool $event = true): void
    {
        $this->modelRepository->createT($modelId, $lang, $dataT);

        if ($event) {
            event(new ModelCreatedT($modelId, $this->modelRepository->getModelClass(), $this->aclService->id(), $dataT, $lang));
        }
    }

    public function createWithT(string $lang, array $data, array $dataT, $fresh = true, bool $event = true): Model
    {
        $model = $this->create($data, $fresh, $event);
        $this->createT($model->getKey(), $lang, $dataT, $event);

        return $model;
    }

    public function update(int|string|Model $moid, array $data, bool $returnModel = false, bool $event = true): bool|Model
    {
        list ($model, $oldData) = $this->modelRepository->getCurrData($moid, array_keys($data));
        $result = $this->modelRepository->update($model, $data, $returnModel);

        if ($event) {
            event(new ModelUpdated($model->getKey(), $this->modelRepository->getModelClass(), $this->aclService->id(), $oldData, $data, $model));
        }

        return $result;
    }

    public function updateT(int|string $modelId, string $lang, array $dataT, bool $event = true): void
    {
        list ($model, $oldDataT) = $this->modelRepository->getCurrDataT($modelId, $lang, array_keys($dataT));
        $this->modelRepository->updateT($modelId, $lang, $dataT);

        if ($event) {
            event(new ModelUpdatedT($modelId, $this->modelRepository->getModelClass(), $this->aclService->id(), $oldDataT, $dataT, $lang));
        }
    }

    public function updateWithT(int|string|Model $moid, string $lang, array $data, array $dataT, bool $returnModel = true, bool $event = true): bool|Model
    {
        $id = $moid instanceof Model ? $moid->getKey() : $moid;
        $this->updateT($id, $lang, $dataT, $event);

        return $this->update($moid, $data, $returnModel, $event);
    }

    public function delete(int|string|Model $moid, bool $event = true): int|string
    {
        list ($model, $data) = $this->modelRepository->getCurrData($moid);
        $id = $model->getKey();

        $this->checkDelete($model);
        $this->modelRepository->delete($model);

        if ($event) {
            event(new ModelDeleted($id, $this->modelRepository->getModelClass(), $this->aclService->id(), $data));
        }

        return $id;
    }

    public function deleteT(int|string $modelId, ?string $lang, bool $event = true): void
    {
        $langList = $lang ? [$lang] : $this->localeService->getLangList();
        foreach ($langList as $key) {
            list ($model, $data) = $this->modelRepository->getCurrDataT($modelId, $key);
            $this->modelRepository->deleteT($modelId, $key);

            if ($event) {
                event(new ModelDeletedT($modelId, $this->modelRepository->getModelClass(), $this->aclService->id(), $data, $key));
            }
        }
    }

    public function deleteWithT(int|string|Model $moid, ?string $lang = null, bool $event = true): void
    {
        $id = $this->delete($moid, $event);
        $this->deleteT($id, $lang, $event);
    }

    public function checkDelete(Model $model): void
    {
        // Implement staff in descendants if need
    }
}
