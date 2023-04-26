<?php

namespace Omadonex\LaravelTools\Acl\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Omadonex\LaravelTools\Acl\Events\UserRegistered;
use Omadonex\LaravelTools\Acl\Interfaces\IAclService;
use Omadonex\LaravelTools\Acl\Repositories\AclRepository;
use Omadonex\LaravelTools\Acl\Repositories\UserMetaRepository;
use Omadonex\LaravelTools\Acl\Repositories\UserRepository;
use Omadonex\LaravelTools\Support\Classes\Exceptions\OmxUserException;
use Omadonex\LaravelTools\Support\Events\ModelCreated;
use Omadonex\LaravelTools\Support\Events\ModelUpdated;
use Omadonex\LaravelTools\Support\Models\HistoryEvent;
use Omadonex\LaravelTools\Support\Services\ModelService;
use Ramsey\Uuid\Uuid;

class UserService extends ModelService
{
    private AclRepository $aclRepository;
    private UserMetaRepository $userMetaRepository;

    public function __construct(
        AclRepository $aclRepository,
        UserRepository $userRepository,
        UserMetaRepository $userMetaRepository)
    {
        parent::__construct($userRepository);
        $this->aclRepository = $aclRepository;
        $this->userMetaRepository = $userMetaRepository;
    }

    private function makePassword(string $password): string
    {
        return Hash::make($password);
    }

//    private function makeAvatar(int $userId, $avatar): string
//    {
//        return $avatar->move("storage/img/avatars/{$key}", Uuid::uuid4()->toString())
//    }

    public function register(array $data): Model
    {
        $user = $this->create([
            'username' => $data['username'],
            'password' => $data['password'],
            'email' => $data['email'],
            'phone' => null,
            'meta' => [
                'display_name' => null,
                'first_name' => null,
                'last_name' => null,
                'opt_name' => null,
                'avatar' => null,
            ],
        ]);

        event(new UserRegistered($user));

        return $user;
    }

    public function create(array $data, bool $fresh = true, bool $stopPropagation = false): Model
    {
        try {
            DB::beginTransaction();

            $userData = [
                'username' => $data['username'],
                'email' => $data['email'],
                'password' => $this->makePassword($data['password']),
                'phone' => $data['phone'] ?? null,
            ];
            $user = $this->modelRepository->create($userData, $fresh, $stopPropagation);

            $metaData = $data['meta'];
            $userMetaData = [
                'user_id' => $user->getKey(),
                'display_name' => $metaData['display_name'] ?? null,
                'first_name' => $metaData['first_name'] ?? null,
                'last_name' => $metaData['last_name'] ?? null,
                'opt_name' => $metaData['opt_name'] ?? null,
            ];
            $this->userMetaRepository->create($userMetaData);

//            $avatar = $metaData['avatar'] ?? null;
//            if ($avatar !== null) {
//                $this->setAvatar($user, $avatar);
//            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }

        event(new ModelCreated($user));

        return $user;

//            if ($this->history) {
//                unset($userData['password']);
//                unset($userMetaData['user_id']);
//                $historyData = $userData + $metaData;
//                foreach ($historyData as $key => $value) {
//                    if ($value === null) {
//                        unset($historyData[$key]);
//                    }
//                }
//                $this->writeToHistory(app('acl')->id(), $user->getKey(), $this->modelRepository->getModelClass(), HistoryEvent::CREATE, [], ['__common' => $historyData]);
//            }
    }

    public function update(int|string|Model $moid, array $data, bool $returnModel = false, bool $stopPropagation = false): bool|Model
    {
        try {
            DB::beginTransaction();

            $user = $this->modelRepository->find($moid);
            $userMetaData = $data['meta'];
            unset($data['meta']);
            $userData = $data;

            if ($userData['password'] ?? null) {
                $userData['password'] = $this->makePassword($userData['password']);
            }

//            if ($userMetaData['avatar'] ?? null) {
//                $this->setAvatar($user, $userMetaData['avatar']);
//                unset($userMetaData['avatar']);
//            }

            $result = $this->modelRepository->update($user->id, $userData, $returnModel, $stopPropagation);
            $this->userMetaRepository->update($user->id, $userMetaData);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }

        event(new ModelUpdated($user));

        return $result;
    }

    public function setPassword(int|string|Model $moid, string $password): void
    {
        $this->update($moid, [
            'password' => $this->makePassword($password),
        ]);
    }

    public function setAvatar(int|string|Model $moid, $avatar): void
    {
        $key = $moid instanceof Model ? $moid->getKey() : $moid;

        $this->userMetaRepository->update($moid, [
            'meta' => [
                'avatar' => $avatar->move("storage/img/avatars/{$key}", Uuid::uuid4()->toString()),
            ],
        ]);
    }

    public function attachRole(int|string|Model $moid, array|string $roleId): void
    {
        $moid = $this->modelRepository->find($moid);
        if (app('acl')->checkRoleForUser($moid, $roleId, IAclService::CHECK_TYPE_AND, true)) {
            OmxUserException::throw(OmxUserException::ERR_CODE_1001);
        }

        $this->aclRepository->addRole($moid, $roleId);
        if (!is_array($roleId)) {
            $roleId = [$roleId];
        }

        $historyData = [];
        foreach ($roleId as $id) {
            $historyData['role_id'] = $id;
            $this->writeToHistory(app('acl')->id(), $moid->getKey(), $this->modelRepository->getModelClass(), HistoryEvent::UPDATE, [], ['__common' => $historyData]);
        }
    }

    public function detachRole(int|string|Model $moid, array|string $roleId): void
    {
        $moid = $this->modelRepository->find($moid);
        $this->aclRepository->removeRole($moid, $roleId);
        if (!is_array($roleId)) {
            $roleId = [$roleId];
        }

        $historyData = [];
        foreach ($roleId as $id) {
            $historyData['role_id'] = $id;
            $this->writeToHistory(app('acl')->id(), $moid->getKey(), $this->modelRepository->getModelClass(), HistoryEvent::UPDATE, ['__common' => $historyData], []);
        }
    }

    public function checkDelete(Model $model): void
    {
        if (app('acl')->id() === $model->getKey()) {
            OmxUserException::throw(OmxUserException::ERR_CODE_1002);
        }
    }
}
