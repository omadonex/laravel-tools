<?php

namespace Omadonex\LaravelTools\Support\Interfaces\Repositories;

use Illuminate\Database\Eloquent\Model;

interface IModelService
{
    public function create(array $data, bool $fresh = true, bool $event = true): Model;

    public function createT(int|string $modelId, string $lang, array $dataT, bool $event = true): void;

    public function createWithT(string $lang, array $data, array $dataT, $fresh = true, bool $event = true): Model;

    public function update(int|string|Model $moid, array $data, bool $returnModel = false, bool $event = true): bool|Model;

    public function updateT(int|string $modelId, string $lang, array $dataT, bool $event = true): void;

    public function updateWithT(int|string|Model $moid, string $lang, array $data, array $dataT, bool $returnModel = true, bool $event = true): bool|Model;

    public function delete(int|string|Model $moid, bool $event = true): int|string;

    public function deleteT(int|string $modelId, ?string $lang, bool $event = true): void;

    public function deleteWithT(int|string|Model $moid, ?string $lang = null, bool $event = true): void;

    public function checkDelete(Model $model): void;
}