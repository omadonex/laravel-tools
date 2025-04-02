<?php

namespace Omadonex\LaravelTools\Acl\ModelView;

use Omadonex\LaravelTools\Support\ModelView\ModelView;

class UserView extends ModelView
{
    protected array $columns = [
        'avatar'          => ['type' => 'none', 'filter' => self::FILTER_NONE, 'searchable' => false, 'orderable' => false],
        'id'              => ['type' => 'int', 'filter' => self::FILTER_INPUT],
        'username'        => ['type' => 'string', 'filter' => self::FILTER_INPUT],
        'email'           => ['type' => 'string', 'filter' => self::FILTER_INPUT],
        'phone'           => ['type' => 'string', 'filter' => self::FILTER_INPUT],
        'roles_ids_label' => ['type' => 'none', 'filter' => self::FILTER_NONE, 'searchable' => false, 'orderable' => false],
        'display_name'    => ['type' => 'string', 'filter' => self::FILTER_INPUT],
        'first_name'      => ['type' => 'string', 'filter' => self::FILTER_INPUT],
        'last_name'       => ['type' => 'string', 'filter' => self::FILTER_INPUT],
        'opt_name'        => ['type' => 'string', 'filter' => self::FILTER_INPUT],
    ];
}
