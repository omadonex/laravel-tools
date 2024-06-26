<?php

namespace Omadonex\LaravelTools\Acl\Services\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Omadonex\LaravelTools\Acl\Events\UserPassChanged;
use Omadonex\LaravelTools\Acl\Events\UserRegistered;
use Omadonex\LaravelTools\Acl\Events\UserRoleAttached;
use Omadonex\LaravelTools\Acl\Events\UserRoleDetached;
use Omadonex\LaravelTools\Acl\Interfaces\IAclService;
use Omadonex\LaravelTools\Acl\Repositories\UserRepository;
use Omadonex\LaravelTools\Locale\Interfaces\ILocaleService;
use Omadonex\LaravelTools\Support\Classes\Exceptions\OmxUserException;
use Omadonex\LaravelTools\Support\Services\ModelService;
use Ramsey\Uuid\Uuid;

class UserService extends ModelService
{
    public function __construct(UserRepository $userRepository, IAclService $aclService, ILocaleService $localeService)
    {
        parent::__construct($userRepository, $aclService, $localeService);
    }

    public function makePassword(string $password): string
    {
        return Hash::make($password);
    }

    public function makeAvatar($avatar): string
    {
        $id = Uuid::uuid4()->toString();
        $avatar->storeAs('public/avatars', $id);

        return "/storage/avatars/{$id}";
    }

    public function create(array $data, bool $fresh = true, bool $event = true): Model
    {
        $userData = [
            'username' => $data['username'],
            'password' => $this->makePassword($data['password']),
            'email' => $data['email'] ?? null,
            'phone' => $data['phone'] ?? null,
            'phone_code' => 7,
            'display_name' => $data['display_name'] ?? null,
            'first_name' => $data['first_name'] ?? null,
            'last_name' => $data['last_name'] ?? null,
            'opt_name' => $data['opt_name'] ?? null,
        ];

        if (array_key_exists('id', $data)) {
            $userData['id'] = $data['id'];
        }

        $avatar = $data['avatar'] ?? null;
        if ($avatar !== null) {
            $userData['avatar'] = $this->makeAvatar($avatar);
        }

        return parent::create($userData, $fresh, $event);
    }

    public function createForce(int $userId, array $data, bool $fresh = true, bool $event = true): Model
    {
        Model::unguard();
        $data['id'] = $userId;
        $user = $this->create($data, $fresh, $event);
        Model::reguard();

        return $user;
    }

    public function update(int|string|Model $moid, array $data, bool $returnModel = false, bool $event = true): bool|Model
    {
        $userData = [];
        foreach ($data as $key => $value) {
            if ($key === 'password') {
                $userData[$key] = $this->makePassword($value);
            } elseif ($key === 'avatar') {
                $userData[$key] = $this->makeAvatar($value);
            } else {
                $userData[$key] = $value;
            }
        }
        $userData['phone_code'] = 7;

        return parent::update($moid, $userData, $returnModel, $event);
    }

    public function checkDelete(Model $model): void
    {
        if ($this->aclService->id() === $model->getKey()) {
            OmxUserException::throw(OmxUserException::ERR_CODE_1002);
        }
    }

    public function setPassword(int|string|Model $moid, string $password): void
    {
        $this->update($moid, [
            'password' => $password,
        ]);

        event(new UserPassChanged($moid instanceof Model ? $moid->getKey() : $moid, $this->aclService->id()));
    }

    public function setAvatar(int|string|Model $moid, $avatar): void
    {
        $this->update($moid, [
            'avatar' => $avatar,
        ]);
    }

    public function changePassword(int|string|Model $moid, string $passwordCurrent, string $passwordNew): void
    {
        $moid = $this->modelRepository->find($moid);
        if (!Hash::check($passwordCurrent, $moid->password)) {
            OmxUserException::throw(OmxUserException::ERR_CODE_1003);
        }

        $this->setPassword($moid, $passwordNew);
    }

    public function register(array $data): Model
    {
        $user = $this->create($data);

        event(new UserRegistered($user->getKey()));

        return $user;
   }

    public function attachRole(int|string|Model $moid, array|string $roleId): void
    {
        $moid = $this->modelRepository->find($moid);
        if ($this->aclService->checkRole($roleId, IAclService::CHECK_TYPE_AND, true, $moid)) {
            OmxUserException::throw(OmxUserException::ERR_CODE_1001);
        }

        $this->aclService->attachRole($roleId, $moid);
        if (!is_array($roleId)) {
            $roleId = [$roleId];
        }

        foreach ($roleId as $id) {
            event(new UserRoleAttached($moid->getKey(), $this->aclService->id(), $id));
        }
    }

    public function detachRole(int|string|Model $moid, array|string $roleId): void
    {
        $moid = $this->modelRepository->find($moid);
        $this->aclService->detachRole($roleId, $moid);
        if (!is_array($roleId)) {
            $roleId = [$roleId];
        }

        foreach ($roleId as $id) {
            event(new UserRoleDetached($moid->getKey(), $this->aclService->id(), $id));
        }
    }
}
