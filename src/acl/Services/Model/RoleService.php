<?php

namespace Omadonex\LaravelTools\Acl\Services\Model;

use Illuminate\Database\Eloquent\Model;
use Omadonex\LaravelTools\Acl\Interfaces\IAclService;
use Omadonex\LaravelTools\Acl\Interfaces\IRole;
use Omadonex\LaravelTools\Acl\Repositories\RoleRepository;
use Omadonex\LaravelTools\Locale\Interfaces\ILocaleService;
use Omadonex\LaravelTools\Support\Classes\Exceptions\OmxUserException;
use Omadonex\LaravelTools\Support\Services\ModelService;
use Ramsey\Uuid\Uuid;

class RoleService extends ModelService
{
    public function __construct(RoleRepository $roleRepository, IAclService $aclService, ILocaleService $localeService)
    {
        parent::__construct($roleRepository, $aclService, $localeService);
    }

    public function create(array $data, bool $fresh = true, bool $event = true): Model
    {
        if (empty($data['id'])) {
            $data['id'] = Uuid::uuid4()->toString();
        }

        return parent::create($data, $fresh, $event);
    }

    public function checkDelete(Model $model): void
    {
        if (in_array($model->getKey(), IRole::RESERVED_ROLE_IDS)) {
            OmxUserException::throw(OmxUserException::ERR_CODE_1004);
        }
    }
}