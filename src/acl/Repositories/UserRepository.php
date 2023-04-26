<?php

namespace Omadonex\LaravelTools\Acl\Repositories;

use Omadonex\LaravelTools\Acl\Http\Resources\UserResource;
use Omadonex\LaravelTools\Acl\Models\User;
use Omadonex\LaravelTools\Support\Repositories\ModelRepository;

class UserRepository extends ModelRepository
{
    public function __construct(User $user)
    {
        parent::__construct($user, UserResource::class);
    }
}
