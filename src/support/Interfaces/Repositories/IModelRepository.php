<?php

namespace Omadonex\LaravelTools\Support\Interfaces\Repositories;

use Illuminate\Database\Eloquent\Model;
use Omadonex\LaravelTools\Support\Classes\Exceptions\OmxClassNotUsesTraitException;
use Omadonex\LaravelTools\Support\Classes\Exceptions\OmxModelNotSearchedException;
use Omadonex\LaravelTools\Support\Classes\Exceptions\OmxModelNotSmartFoundException;

interface IModelRepository
{
    /**
     * Возвращает класс модели
     * @return mixed
     */
    public function getModel();

    /**
     * Возвращает название класса модели
     * @return string
     */
    public function getModelClass();

    /**
     * Возвращает Query builder для построения запросов
     * @return mixed
     */
    public function query();

    /**
     * Возвращает массив доступных связей модели, либо пустой массив, если свойство отсутствует
     * @return array
     */
    public function getAvailableRelations();

    /**
     * Приводит результат запроса в состояние ресурса
     *
     * @param $modelOrCollection
     * @param false $resource
     * @param null $resourceClass
     * @param array $resourceParams
     * @param false $paginate
     * @return mixed
     */
    public function toResource($modelOrCollection, $resource = false, $resourceClass = null, $resourceParams = [], $paginate = false);

    /**
     * @param $modelOrId
     * @param array $options
     *
     * 'exceptions' => false | true
     * 'resource' => false | true
     * 'resourceClass' => null | class
     * 'relations' => false | true | array
     * 'trashed' => null | with | only
     * 'smart' => false | true
     * 'smartField' => null | string
     * 'enabled' => null | false | true
     * 'closures' => []
     *
     * @throws OmxModelNotSmartFoundException
     * @throws OmxClassNotUsesTraitException
     * @return mixed
     */
    public function find($modelOrId, $options = []);

    /**
     * Выполняет поиск по заданным критериям, может генерировать исключение
     * @param array $options
     *
     * 'exceptions' => false | true
     * 'resource' => false | true
     * 'resourceClass' => null | class
     * 'relations' => false | true | array
     * 'trashed' => null | with | only
     * 'enabled' => null | false | true
     * 'closures' => []
     *
     * @throws OmxModelNotSearchedException
     * @throws OmxClassNotUsesTraitException
     * @return mixed
     */
    public function search($options = []);

    /**
     * Получает коллекцию элементов, загружая указанные связи и учитывая `enabled`
     * Возвращает пагинатор либо коллекцию, если кол-во элементов не указано, то оно будет взято из модели
     * @param array $options
     *
     * 'resource' => false | true
     * 'resourceClass' => null | class
     * 'relations' => false | true | array
     * 'trashed' => null | with | only
     * 'enabled' => null | false | true
     * 'paginate' => false | true | integer
     * 'closures' => []
     *
     * @throws OmxClassNotUsesTraitException
     * @return mixed
     */
    public function list($options = []);

    /**
     * Агрегатная функция подсчета количества элементов
     * @param array $options
     *
     * 'trashed' => null | with | only
     * 'enabled' => null | false | true
     * 'closures' => []
     *
     * @throws OmxClassNotUsesTraitException
     * @return mixed
     */
    public function agrCount($options = []);

    /**
     * Создает новую модель по введенным данным и возвращает ее
     */
    public function create(array $data, bool $fresh = true, bool $stopPropagation = false): Model;

    /**
     * Создает новую модель вместе со связанной моделью переводов
     */
    public function createWithT(string $lang, array $data, array $dataT, bool $fresh = true, bool $stopPropagation = false): Model;

    /**
     * Создает новый перевод для модели
     */
    public function createT(string $lang, int|string $id, array $dataT): void;

    /**
     * Обновляет поля модели и возвращает обновленную модель
     */
    public function update(int|string|Model $modelOrId, array $data, bool $returnModel = false, bool $stopPropagation = false): bool|Model;

    /**
     * Обновляет поля модели вместе со связанной моделью переводов
     */
    public function updateWithT(string $lang, int|string|Model $modelOrId, array $data, array $dataT, bool $returnModel = true, bool $stopPropagation = false): bool|Model;

    /**
     * Обновляет перевод для модели
     */
    public function updateT(string $lang, int|string $id, array $dataT): void;

    /**
     * Обновляет существущую либо создает новую модель
     * @param $data
     * @return mixed
     */
    public function updateOrCreate($data);

    /**
     * Удаляет модель
     * @param $id
     * @return mixed
     */
    public function destroy($id);

    /**
     * Выполняет попытку удаления модели, необходимо переопределять
     * @param $id
     * @return mixed
     */
    public function tryDestroy($id);

    /**
     * Включение
     * @param $id
     * @return mixed
     */
    public function enable($id);

    /**
     * Отключение
     * @param $id
     * @return mixed
     */
    public function disable($id);

    /**
     * Удаляет все записи в таблице
     * @param $force
     * @return mixed
     */
    public function clear($force = false);
}