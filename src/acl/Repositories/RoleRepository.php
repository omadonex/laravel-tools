<?php

namespace Omadonex\LaravelTools\Acl\Repositories;

use Illuminate\Database\Eloquent\Model;
use Omadonex\LaravelTools\Acl\Http\Resources\RoleResource;
use Omadonex\LaravelTools\Acl\Models\Role;
use Omadonex\LaravelTools\Support\Repositories\ModelRepository;
use Ramsey\Uuid\Uuid;

class RoleRepository extends ModelRepository
{
    public function __construct(Role $role)
    {
        parent::__construct($role, RoleResource::class);
    }

    public function createWithT(string $lang, array $data, array $dataT, bool $fresh = true, bool $stopPropagation = false): Model
    {
        $data['id'] = Uuid::uuid4()->toString();

        return parent::createWithT($lang, $data, $dataT, $fresh, $stopPropagation);
    }
}
