<?php

namespace Omadonex\LaravelTools\Support\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Omadonex\LaravelTools\Support\Classes\ConstCustom;
use Omadonex\LaravelTools\Support\Classes\Exceptions\OmxClassNotUsesTraitException;
use Omadonex\LaravelTools\Support\Classes\Exceptions\OmxModelCanNotBeDisabledException;
use Omadonex\LaravelTools\Support\Classes\Exceptions\OmxModelCanNotBeEnabledException;
use Omadonex\LaravelTools\Support\Classes\Exceptions\OmxModelNotSearchedException;
use Omadonex\LaravelTools\Support\Classes\Exceptions\OmxModelNotSmartFoundException;
use Omadonex\LaravelTools\Support\Classes\Utils\UtilsFilter;
use Omadonex\LaravelTools\Support\Interfaces\Repositories\IModelRepository;
use Omadonex\LaravelTools\Support\Traits\CanBeEnabledTrait;
use Omadonex\LaravelTools\Support\Transformers\PaginateResourceCollection;

abstract class ModelRepository implements IModelRepository
{
    protected $model;
    protected $modelClass;
    protected $resourceClass;
    protected $filterFieldsTypes = [];

    public function __construct(Model $model, $resourceClass = null)
    {
        $this->model = $model;
        $this->modelClass = get_class($model);
        $this->resourceClass = $resourceClass;
    }

    protected function getRealOptions($options)
    {
        $keysValues = [
            'exceptions' => false,
            'resource' => false,
            'resourceClass' => null,
            'resourceParams' => [],
            'relations' => false,
            'trashed' => null,
            'smart' => false,
            'smartField' => null,
            'enabled' => null,
            'paginate' => false,
            'page' => null,
            'filter' => null,
            'search' => null,
            'closures' => [],
            'noExec' => false,
        ];

        $realOptions = [];
        foreach ($keysValues as $key => $value) {
            $realOptions[$key] = array_key_exists($key, $options) ? $options[$key] : $value;
        }

        return $realOptions;
    }

    protected function attachRelations($qb, $options)
    {
        $prop = 'availableRelations';
        if (($options['relations'] === true)
            && property_exists($this->modelClass, $prop)
            && is_array($this->model->$prop)) {
            $qb->with($this->model->$prop);
        }

        if (is_array($options['relations'])) {
            $qb->with($options['relations']);
        }

        return $qb;
    }

    protected function getPaginatedResult($qb, $paginate, $page)
    {
        if (!$paginate) {
            return $qb->get();
        }

        return $qb->paginate(($paginate === true) ? $this->model->getPerPage() : $paginate, ['*'], 'page', $page);
    }

    /**
     * @param $options
     * @return mixed
     * @throws OmxClassNotUsesTraitException
     */
    protected function makeQB($options, $qb = null)
    {
        $qb = $qb ?: $this->model->query();

        if (!is_null($options['trashed'])) {
            if (!in_array(SoftDeletes::class, class_uses($this->modelClass))) {
                throw new OmxClassNotUsesTraitException($this->modelClass, SoftDeletes::class);
            }

            if ($options['trashed'] === ConstCustom::DB_QUERY_TRASHED_WITH) {
                $qb->withTrashed();
            }

            if ($options['trashed'] === ConstCustom::DB_QUERY_TRASHED_ONLY) {
                $qb->onlyTrashed();
            }
        }

        if (!is_null($options['enabled'])) {
            if (!in_array(CanBeEnabledTrait::class, class_uses($this->modelClass))) {
                throw new OmxClassNotUsesTraitException($this->modelClass, CanBeEnabledTrait::class);
            }
            $qb->byEnabled($options['enabled']);
        }

        if ($options['filter'] !== null) {
            $qb = UtilsFilter::apply($qb, $options['filter'], $this->filterFieldsTypes);
        }

        if ($options['search'] !== null) {
            $search = $options['search'];
            $qb->where(function ($query) use ($search) {
                foreach ($search['columns'] as $column) {
                    $query->orWhere($column, 'like', "%{$search['value']}%");
                }
            });
        }

        foreach ($options['closures'] as $closure) {
            if (is_callable($closure)) {
                $qb = $closure($qb);
            }
        }

        return $this->attachRelations($qb, $options);
    }

    public function getModel()
    {
        return $this->model;
    }

    public function getModelClass()
    {
        return $this->modelClass;
    }

    public function getTranslateClass()
    {
        return (new \ReflectionClass($this->getModel()))->getName() . 'Translate';
    }

    public function query()
    {
        return $this->model->query();
    }

    public function getAvailableRelations()
    {
        return $this->model->availableRelations ?: [];
    }

    public function toResource($modelOrCollection, $resource = false, $resourceClass = null, $resourceParams = [], $paginate = false)
    {
        if (!$resource) {
            return $modelOrCollection;
        }

        $finalResourceClass = $resourceClass ?: $this->resourceClass;

        if ($modelOrCollection instanceof Model) {
            if ($resourceParams) {
                return new $finalResourceClass($modelOrCollection, $resourceParams);
            }

            return new $finalResourceClass($modelOrCollection);
        }

        if ($paginate) {
            return new PaginateResourceCollection($modelOrCollection, $finalResourceClass);
        }

        return $finalResourceClass::collection($modelOrCollection);
    }

    /**
     * @param $id
     * @param $options
     * @return mixed|null|PaginateResourceCollection
     * @throws OmxClassNotUsesTraitException
     * @throws OmxModelNotSmartFoundException
     */
    private function doFind($id, $options)
    {
        $realOptions = $this->getRealOptions($options);

        $field = $this->model->getKeyName();
        if ($realOptions['smart']) {
            $field = $realOptions['smartField'] ?: $this->model->getRouteKeyName();
        }
        $model = $this->makeQB($realOptions)->where($field, $id)->first();
        if (is_null($model)) {
            if ($realOptions['exceptions']) {
                throw new OmxModelNotSmartFoundException($this->model, $id, $field);
            }

            return null;
        }

        return $this->toResource($model, $realOptions['resource'], $realOptions['resourceClass'], $realOptions['resourceParams']);
    }

    public function find($modelOrId, $options = [])
    {
        if ($modelOrId instanceof Model) {
            return $modelOrId;
        }

        return $this->doFind($modelOrId, $options);
    }

    public function getCurrData(int|string|Model $moid, array $keyList = []): array
    {
        $model = $this->find($moid);
        if ($model === null) {
            return [null, []];
        }

        $currData = empty($keyList) ? $model->getAttributes() : [];
        if (!empty($keyList)) {
            foreach ($keyList as $key) {
                $currData[$key] = $model->$key;
            }
        }

        return [$model, $currData];
    }

    public function findT(int|string $modelId, string $lang): ?Model
    {
        return call_user_func_array("{$this->getTranslateClass()}::where", [
            [
                'model_id' => $modelId,
                'lang' => $lang,
            ],
        ])->first();
    }

    public function getCurrDataT(int|string $modelId, string $lang, array $keyList = []): array
    {
        $model = call_user_func_array("{$this->getTranslateClass()}::where", [
            [
                'model_id' => $modelId,
                'lang' => $lang,
            ],
        ])->first();

        if ($model === null) {
            return [null, []];
        }

        $currData = empty($keyList) ? $model->getAttributes() : [];
        if (!empty($keyList)) {
            foreach ($keyList as $key) {
                $currData[$key] = $model->$key;
            }
        }

        return [$model, $currData];
    }


    public function search($options = [])
    {
        $realOptions = $this->getRealOptions($options);
        $model = $this->makeQB($realOptions)->first();
        if (is_null($model)) {
            if ($realOptions['exceptions']) {
                throw new OmxModelNotSearchedException($this->model);
            }

            return null;
        }

        return $this->toResource($model, $realOptions['resource'], $realOptions['resourceClass'], $realOptions['resourceParams'], false);
    }

    public function list($options = [], $qb = null)
    {
        $realOptions = $this->getRealOptions($options);
        $newQb = $this->makeQB($realOptions, $qb);

        if ($realOptions['noExec']) {
            return $newQb;
        }
        $collection = $this->getPaginatedResult($newQb, $realOptions['paginate'], $realOptions['page']);

        return $this->toResource($collection, $realOptions['resource'], $realOptions['resourceClass'], $realOptions['resourceParams'], $realOptions['paginate']);
    }

    public function grid(array $options = [])
    {
        return $this->list($options);
    }

    public function agrCount($options = [])
    {
        $realOptions = $this->getRealOptions($options);

        return $this->makeQB($realOptions)->count();
    }

    public function create(array $data, bool $fresh = true): Model
    {
        $model = $this->getModel()->create($data);
        if ($fresh) {
            $model = $model->fresh();
        }

        return $model;
    }

    public function createT(int|string $modelId, string $lang, array $dataT): void
    {
        call_user_func_array("{$this->getTranslateClass()}::create", [
            [
                'model_id' => $modelId,
                'lang' => $lang,
            ] + $dataT,
        ]);
    }

    public function update(int|string|Model $moid, array $data, bool $returnModel = false): bool|Model
    {
        $model = $this->find($moid);
        $result = $model->update($data);

        return $returnModel ? $model : $result;
    }

    public function updateT(int|string $modelId, string $lang, array $dataT): void
    {
        call_user_func_array("{$this->getTranslateClass()}::where", [
            [
                'model_id' => $modelId,
                'lang' => $lang,
            ],
        ])->update($dataT);
    }

    public function delete(int|string|Model $moid): void
    {
        if ($moid instanceof Model) {
            $moid->delete();
        } else {
            $this->getModel()->destroy($moid);
        }
    }

    public function deleteT(int|string $modelId, ?string $lang): void
    {
        call_user_func_array("{$this->getTranslateClass()}::where", [
            [
                'model_id' => $modelId,
            ] + ($lang === null ? [] : ['lang' => $lang]),
        ])->delete();
    }

    public function updateOrCreate($data)
    {
        return $this->getModel()->updateOrCreate($data);
    }

    public function enable($id)
    {
        $modelClass = get_class($this->getModel());
        if (!in_array(CanBeEnabledTrait::class, class_uses($modelClass))) {
            throw new OmxClassNotUsesTraitException($modelClass, CanBeEnabledTrait::class);
        }

        $model = $this->find($id);
        if (!$model->canEnable()) {
            throw new OmxModelCanNotBeEnabledException($this->getModel()->cantEnableText());
        }

        $model->enable();
    }

    public function disable($id)
    {
        $modelClass = get_class($this->getModel());
        if (!in_array(CanBeEnabledTrait::class, class_uses($modelClass))) {
            throw new OmxClassNotUsesTraitException($modelClass, CanBeEnabledTrait::class);
        }

        $model = $this->find($id);
        if (!$model->canDisable()) {
            throw new OmxModelCanNotBeDisabledException($this->getModel()->cantDisableText());
        }

        $model->disable();
    }

    public function clear($force = false)
    {
        if ($force) {
            $this->query()->forceDelete();
        } else {
            $this->query()->delete();
        }
    }

    public function pluck(string $name = 'name', string $id = 'id'): Collection
    {
        return $this->model::pluck($name, $id);
    }
}