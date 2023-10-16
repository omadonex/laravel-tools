<?php

namespace Omadonex\LaravelTools\Support\Services;

use Omadonex\LaravelTools\Acl\Interfaces\IAclService;
use Omadonex\LaravelTools\Locale\Interfaces\ILocaleService;
use Omadonex\LaravelTools\Support\Repositories\ColumnSetRepository;

class ColumnSetService extends ModelService
{
    public function __construct(ColumnSetRepository $tableColumnSettingRepository, IAclService $aclService, ILocaleService $localeService)
    {
        parent::__construct($tableColumnSettingRepository, $aclService, $localeService);
    }
}
