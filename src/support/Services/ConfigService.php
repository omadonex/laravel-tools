<?php

namespace Omadonex\LaravelTools\Support\Services;

use Omadonex\LaravelTools\Acl\Interfaces\IAclService;
use Omadonex\LaravelTools\Locale\Interfaces\ILocaleService;
use Omadonex\LaravelTools\Support\Repositories\ConfigRepository;

class ConfigService extends ModelService
{
    public function __construct(ConfigRepository $configRepository, IAclService $aclService, ILocaleService $localeService)
    {
        parent::__construct($configRepository, $aclService, $localeService);
    }
}
