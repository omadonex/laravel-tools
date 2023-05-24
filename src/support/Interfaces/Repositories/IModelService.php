<?php

namespace Omadonex\LaravelTools\Support\Interfaces\Repositories;

use Illuminate\Database\Eloquent\Model;

interface IModelService
{
    public function create(array $data, bool $fresh = true): Model;

    public function createT(int|string $modelId, string $lang, array $dataT): void;

    public function createWithT(string $lang, array $data, array $dataT, $fresh = true): Model;

    public function update(int|string|Model $moid, array $data, bool $returnModel = false): bool|Model;

    public function updateT(int|string $modelId, string $lang, array $dataT): void;

    public function updateWithT(int|string|Model $moid, string $lang, array $data, array $dataT, bool $returnModel = true): bool|Model;

    public function delete(int|string|Model $moid): int|string;

    public function deleteT(int|string $modelId, ?string $lang): void;

    public function deleteWithT(int|string|Model $moid, ?string $lang = null): void;

    public function checkDelete(Model $model): void;
}