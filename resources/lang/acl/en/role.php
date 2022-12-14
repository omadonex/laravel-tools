<?php

use Omadonex\LaravelTools\Acl\Interfaces\IRole;

return [
    IRole::USER => [
        'name' => 'User',
        'description' => 'Default role for all users',
    ],

    IRole::ROOT => [
        'name' => 'Root',
        'description' => 'A role for a technical super user (root)',
    ],

    IRole::ADMIN => [
        'name' => 'Admin',
        'description' => 'A role for a system super user (admin)',
    ],
];