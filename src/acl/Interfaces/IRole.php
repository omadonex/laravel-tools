<?php

namespace Omadonex\LaravelTools\Acl\Interfaces;

interface IRole
{
    const ROOT = 'root';
    const ADMIN = 'admin';
    const USER = 'user';

    public const RESERVED_ROLE_IDS = [
        self::ROOT,
        self::ADMIN,
        self::USER,
    ];
}