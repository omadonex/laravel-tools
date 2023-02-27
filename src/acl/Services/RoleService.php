<?php

namespace Omadonex\LaravelTools\Acl\Services;

use Illuminate\Database\Eloquent\Model;
use Omadonex\LaravelTools\Acl\Repositories\RoleRepository;
use Omadonex\LaravelTools\Support\Services\ModelService;
use Ramsey\Uuid\Uuid;

class RoleService extends ModelService
{
    public function __construct(RoleRepository $roleRepository)
    {
        parent::__construct($roleRepository);
    }

    public function create(array $data, $fresh = true, $stopPropagation = false): Model
    {
        if (empty($data['id'])) {
            $data['id'] = Uuid::uuid4()->toString();
        }

        return parent::create($data, $fresh, $stopPropagation);
    }

    public function checkDelete(Model $model): void
    {

    }
}
