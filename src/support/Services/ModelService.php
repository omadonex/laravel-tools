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

abstract class ModelService implements IModelService
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

    public function create(array $data, bool $fresh = true): Model
    {
        $model = $this->modelRepository->create($data, $fresh);

        event(new ModelCreated($model, $data, $this->aclService->id()));

        return $model;
    }

    public function createT(int|string $modelId, string $lang, array $dataT): void
    {
        $this->modelRepository->createT($modelId, $lang, $dataT);

        event(new ModelCreatedT($modelId, $this->modelRepository->getModelClass(), $lang, $dataT, $this->aclService->id()));
    }

    public function createWithT(string $lang, array $data, array $dataT, $fresh = true): Model
    {
        $model = $this->create($data, $fresh);
        $this->createT($model->getKey(), $lang, $dataT);

        return $model;
    }

    public function update(int|string|Model $moid, array $data, bool $returnModel = false): bool|Model
    {
        list ($model, $oldData) = $this->modelRepository->getCurrData($moid, array_keys($data));
        $result = $this->modelRepository->update($model, $data, $returnModel);

        event(new ModelUpdated($model, $oldData, $data, $this->aclService->id()));

        return $result;
    }

    public function updateT(int|string $modelId, string $lang, array $dataT): void
    {
        list ($model, $oldDataT) = $this->modelRepository->getCurrDataT($modelId, $lang, array_keys($dataT));
        $this->modelRepository->updateT($modelId, $lang, $dataT);

        event(new ModelUpdatedT($modelId, $this->modelRepository->getModelClass(), $lang, $oldDataT, $dataT, $this->aclService->id()));
    }

    public function updateWithT(int|string|Model $moid, string $lang, array $data, array $dataT, bool $returnModel = true): bool|Model
    {
        $id = $moid instanceof Model ? $moid->getKey() : $moid;
        $this->updateT($id, $lang, $dataT);

        return $this->update($moid, $data, $returnModel);
    }

    public function delete(int|string|Model $moid): int|string
    {
        list ($model, $data) = $this->modelRepository->getCurrData($moid);
        $id = $model->getKey();

        $this->checkDelete($model);
        $this->modelRepository->delete($model);

        event(new ModelDeleted($id, $this->modelRepository->getModelClass(), $data, $this->aclService->id()));

        return $id;
    }

    public function deleteT(int|string $modelId, ?string $lang): void
    {
        $langList = $lang ? [$lang] : $this->localeService->getLangList();
        foreach ($langList as $key) {
            list ($model, $data) = $this->modelRepository->getCurrDataT($modelId, $key);
            $this->modelRepository->deleteT($modelId, $key);

            event(new ModelDeletedT($modelId, $this->modelRepository->getModelClass(), $lang, $data, $this->aclService->id()));
        }
    }

    public function deleteWithT(int|string|Model $moid, ?string $lang = null): void
    {
        $id = $this->delete($moid);
        $this->deleteT($id, $lang);
    }

    public function checkDelete(Model $model): void
    {
        // Implement staff in descendants if need
    }
}
