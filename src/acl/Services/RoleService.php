<?php

namespace Omadonex\LaravelTools\Acl\Services;

use Illuminate\Database\Eloquent\Model;
use Omadonex\LaravelTools\Acl\Repositories\RoleRepository;
use Omadonex\LaravelTools\Support\Services\ModelService;

class RoleService extends ModelService
{
    public function __construct(RoleRepository $roleRepository)
    {
        parent::__construct($roleRepository);
    }

    public function checkDelete(Model $model): void
    {
        /** @var Unit $model */
        if ($model->nomenclatures()->exists()) {
            UserException::throw(UserException::ERR_CODE_1102);
        }
    }
}
