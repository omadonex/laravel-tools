<?php

namespace Omadonex\LaravelTools\Acl\Transformers;

use Omadonex\LaravelTools\Acl\Interfaces\IAclService;
use Omadonex\LaravelTools\Acl\Models\User;
use Omadonex\LaravelTools\Common\Tools\Avatar;
use Omadonex\LaravelTools\Support\Transformers\BaseTransformer;

class UserTransformer extends BaseTransformer
{
    public function __construct($response, $params = [])
    {
        parent::__construct($response, $params, false);
    }

    protected function transformers()
    {
        return [
            'actions' => function ($value, $row, $rowOriginal) {
                if ((app('acl')->isRoot() && $row->id == app('acl')->id()) || !in_array($row->id, IAclService::RESERVED_USER_IDS)) {
                    return rowViewIcon(route(User::getRouteName('show'), $row->id))
                        .rowHistoryIcon(route(User::getRouteName('show'), $row->id))
                        .rowEditIcon($row->id);
                }

                return '';
            },
            'avatar' => function ($value, $row, $rowOriginal) {
                $value = Avatar::get($value);

                return
                    "<div class=\"avatar avatar-circle avatar-xs me-2\">
                      <img src=\"{$value}\" class=\"avatar-img\" width=\"30\" height=\"30\">
                    </div>";
            },
            'roles_ids_label' => function ($value, $row, $rowOriginal) {
                $data = json_decode($value);
                if (is_array($data)) {
                    return implode('<br/>', $data);
                }

                return $data;
            },
            'phone' => function ($value, $row, $rowOriginal) {
                if (!$value) {
                    return '';
                }

                return "+{$row->phone_code}{$value}";
            }
        ];
    }
}
