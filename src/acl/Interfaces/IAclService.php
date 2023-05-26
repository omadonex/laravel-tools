<?php

namespace Omadonex\LaravelTools\Acl\Interfaces;


use Omadonex\LaravelTools\Acl\Models\User;

interface IAclService
{
    public const CHECK_TYPE_AND = 'and';
    public const CHECK_TYPE_OR = 'or';

    public const MODE_DENY = 'deny';
    public const MODE_ALLOW = 'allow';

    public const SECTION_ALLOWED = 'allowed';
    public const SECTION_DENIED = 'denied';
    public const SECTION_PROTECTED = 'protected';
    public const SECTION_UNSAFE = 'unsafe';

    public const PARENT_PERMISSION_GROUP_ID= 'app';

    public const ASSIGN_TYPE_SYSTEM = 1;
    public const ASSIGN_TYPE_ROOT = 2;
    public const ASSIGN_TYPE_USER = 3;

    public const SYSTEM_USER_ID = 1;
    public const SYSTEM_USER_NAME = 'system';
    public const CONSOLE_USER_ID = 2;
    public const CONSOLE_USER_NAME = 'console';

    public function id(): ?int;

    public function check(array|string $permission, string $type = self::CHECK_TYPE_AND): bool;

    public function checkRole(array|string $role, string $type = self::CHECK_TYPE_AND, bool $strict = false): bool;

    public function checkRoute(string $routeName): bool;

    public function checkForUser(User $user, array|string $permission, string $type = self::CHECK_TYPE_AND): bool;

    public function checkRoleForUser(User $user, array|string $role, string $type = self::CHECK_TYPE_AND, bool $strict = false): bool;

    public function checkRouteForUser(User $user, string $routeName): bool;

    public function getRoutesData(): array;

    public function isAdmin(): bool;

    public function isRoot(): bool;

    public function isUser(): bool;

    public function isDeepMode(): bool;

    public function isLoggedIn(): bool;

    public function setUser(User $user): void;

    public function permissions(bool $onlyIds = false): array;

    public function roles(bool $onlyIds = false): array;

    public function user(): User;

    public function attachRole(array|string $role, User $user = null): void;

    public function detachRole(array|string $role, User $user = null): void;
}