<?php

namespace Omadonex\LaravelTools\Acl\Repositories;

use Illuminate\Support\Collection;
use Omadonex\LaravelTools\Acl\Http\Resources\UserResource;
use Omadonex\LaravelTools\Acl\Models\User;
use Omadonex\LaravelTools\Support\Classes\Utils\UtilsUserLabel;
use Omadonex\LaravelTools\Support\Repositories\ModelRepository;

class UserRepository extends ModelRepository
{
    public function __construct(User $user)
    {
        parent::__construct($user, UserResource::class);
    }

    public function pluckExt(string $name = 'name', string $id = 'id'): Collection
    {
        $userList = $this->model->get()
            ->map(function (User $user) {
                return [
                    'id'   => $user->id,
                    'name' => UtilsUserLabel::getFromModel($user),
                ];
            });

        return $userList->pluck($name, $id);
    }
}
