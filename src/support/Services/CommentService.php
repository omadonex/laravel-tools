<?php

namespace Omadonex\LaravelTools\Support\Services;

use Omadonex\LaravelTools\Acl\Interfaces\IAclService;
use Omadonex\LaravelTools\Locale\Interfaces\ILocaleService;
use Omadonex\LaravelTools\Support\Repositories\CommentRepository;

class CommentService extends ModelService
{
    public function __construct(CommentRepository $commentRepository, IAclService $aclService, ILocaleService $localeService)
    {
        parent::__construct($commentRepository, $aclService, $localeService);
    }
}
