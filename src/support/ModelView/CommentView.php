<?php

namespace Omadonex\LaravelTools\Support\ModelView;

use Omadonex\LaravelTools\Support\Tools\Lists;

class CommentView extends ModelView
{
    protected array $columns = [
        'id'   => ['filter' => self::FILTER_INPUT],
        'text' => ['filter' => self::FILTER_INPUT],
        'user_id' => ['filter' => self::FILTER_INPUT],
    ];
}
