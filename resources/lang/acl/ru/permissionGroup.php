<?php

use Omadonex\LaravelTools\Acl\Interfaces\IAclService;

return [
    IAclService::PARENT_PERMISSION_GROUP_ID => [
        'name' => 'App',
        'description' => 'Родительская группа для всех добавляемых разрешений',
    ],
];