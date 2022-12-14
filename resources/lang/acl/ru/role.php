<?php

use Omadonex\LaravelTools\Acl\Interfaces\IRole;

return [
    IRole::USER => [
        'name' => 'Пользователь',
        'description' => 'Роль по умолчанию для всех пользователей',
    ],

    IRole::ROOT => [
        'name' => 'Root',
        'description' => 'Роль для технического пользователя с самыми широкими правами (root)',
    ],

    IRole::ADMIN => [
        'name' => 'Администратор',
        'description' => 'Роль для системного пользователя с самыми широкими правами (admin)',
    ],
];