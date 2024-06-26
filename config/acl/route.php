<?php

use Omadonex\LaravelTools\Acl\Interfaces\IAclService;

return [
    //Доступ закрыт для всех, кроме ROOT
    IAclService::SECTION_DENIED => [
    ],

    //Доступ открыт для всех
    IAclService::SECTION_ALLOWED => [
    ],

    //Требуется роль для доступа (для ROOT | ADMIN все открыто)
    IAclService::SECTION_PROTECTED_ROLE => [
    ],

    //Требуется разрешение для доступа (для ROOT | ADMIN все открыто)
    IAclService::SECTION_PROTECTED_PERMISSION => [
    ],
];
