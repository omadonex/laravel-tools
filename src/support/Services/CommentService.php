<?php

namespace Omadonex\LaravelTools\Support\Services;

use Illuminate\Database\Eloquent\Model;
use Omadonex\LaravelTools\Acl\Interfaces\IAclService;
use Omadonex\LaravelTools\Locale\Interfaces\ILocaleService;
use Omadonex\LaravelTools\Support\Classes\Exceptions\OmxUserException;
use Omadonex\LaravelTools\Support\Models\Comment;
use Omadonex\LaravelTools\Support\Repositories\CommentRepository;

class CommentService extends ModelService
{
    public function __construct(CommentRepository $commentRepository, IAclService $aclService, ILocaleService $localeService)
    {
        parent::__construct($commentRepository, $aclService, $localeService);
    }

    public function delete(int|string|Model $moid, bool $event = true): int|string
    {
        $comment = $this->modelRepository->find($moid);
        $comment->comments()->delete();

        return parent::delete($comment, $event);
    }

    public function checkDelete(Model $model): void
    {
        /** @var Comment $model */
        if ($model->user_id !== $this->aclService->id() || !$this->aclService->hasAdminAccess()) {
            OmxUserException::throw(OmxUserException::ERR_CODE_1005);
        }
    }
}
