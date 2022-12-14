<?php

namespace Omadonex\LaravelTools\Acl\Interfaces;

interface IAclService
{
    const CHECK_TYPE_AND = 'and';
    const CHECK_TYPE_OR = 'or';

    const MODE_DENY = 'deny';
    const MODE_ALLOW = 'allow';

    const SECTION_ALLOWED = 'allowed';
    const SECTION_DENIED = 'denied';
    const SECTION_PROTECTED = 'protected';
    const SECTION_UNSAFE = 'unsafe';

    const PARENT_PERMISSION_GROUP_ID= 'app';

    const ASSIGN_TYPE_SYSTEM = 1;
    const ASSIGN_TYPE_ROOT = 2;
    const ASSIGN_TYPE_USER = 3;

    public function id(): ?int;

    public function check(array|string $permission, string $type = self::CHECK_TYPE_AND): bool;

    public function checkRole(array|string $role, string $type = self::CHECK_TYPE_AND, bool $strict = false): bool;

    public function checkRoute(string $routeName): bool;

    public function checkForUser($user, array|string $permission, string $type = self::CHECK_TYPE_AND): bool;

    public function checkRoleForUser($user, array|string $role, string $type = self::CHECK_TYPE_AND, bool $strict = false): bool;

    public function checkRouteForUser($user, string $routeName): bool;

    public function setUser($user): void;

    public function user();
}