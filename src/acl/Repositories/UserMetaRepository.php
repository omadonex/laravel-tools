<?php

namespace Omadonex\LaravelTools\Acl\Repositories;

use Omadonex\LaravelTools\Acl\Http\Resources\UserMetaResource;
use Omadonex\LaravelTools\Acl\Models\UserMeta;
use Omadonex\LaravelTools\Support\Repositories\ModelRepository;

class UserMetaRepository extends ModelRepository
{
    public function __construct(UserMeta $userMeta)
    {
        parent::__construct($userMeta, UserMetaResource::class);
    }
}
