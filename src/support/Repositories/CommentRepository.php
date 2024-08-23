<?php

namespace Omadonex\LaravelTools\Support\Repositories;

use Omadonex\LaravelTools\Support\Models\Comment;
use Omadonex\LaravelTools\Support\Resources\CommentResource;

class CommentRepository extends ModelRepository
{
    public function __construct(Comment $comment)
    {
        parent::__construct($comment, CommentResource::class);
    }
}
