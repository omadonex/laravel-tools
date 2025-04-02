<?php

namespace Omadonex\LaravelTools\Acl\Transformers;

use Omadonex\LaravelTools\Acl\Interfaces\IRole;
use Omadonex\LaravelTools\Support\Transformers\BaseTransformer;

class RoleTransformer extends BaseTransformer
{
    public function __construct($response, $params = [])
    {
        parent::__construct($response, $params, false);
    }


    protected function transformers()
    {
        return [
            'actions' => function ($value, $row, $rowOriginal) {
                $actions =
                    rowViewIcon(route('root.acl.role.show', $row->id))
                    .rowHistoryIcon(route('root.acl.role.show', $row->id));

                if (!in_array($row->id, IRole::RESERVED_ROLE_IDS)) {
                    $actions .=
                        rowEditIcon($row->id)
                        .rowDeleteIcon($row->id);
                }

                return $actions;
            },
            'is_staff' => $this->makeBooleanIcon(),
            'is_hidden' => $this->makeBooleanIcon(),
        ];
    }
}
