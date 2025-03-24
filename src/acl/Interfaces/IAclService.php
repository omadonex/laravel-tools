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
    public const SECTION_PROTECTED_ROLE = 'protected_role';
    public const SECTION_PROTECTED_PERMISSION = 'protected_permission';
    public const SECTION_UNSAFE = 'unsafe';

    public const PARENT_PERMISSION_GROUP_ID= 'app';

    public const ASSIGN_TYPE_SYSTEM = 1;
    public const ASSIGN_TYPE_ROOT = 2;
    public const ASSIGN_TYPE_USER = 3;

    public const CONSOLE_USER_ID = 1;
    public const CONSOLE_USER_NAME = 'console';
    public const SYSTEM_USER_ID = 2;
    public const SYSTEM_USER_NAME = 'system';
    public const ROOT_USER_ID = 3;
    public const ROOT_USER_NAME = 'root';
    public const ROOT_USER_DEFAULT_PASSWORD = '123123123';

    public const RESERVED_USER_IDS = [
        self::SYSTEM_USER_ID,
        self::CONSOLE_USER_ID,
        self::ROOT_USER_ID,
    ];

    public function id(): ?int;

    public function check(array|string $permission, string $type = self::CHECK_TYPE_AND, ?User $user = null): bool;

    public function checkRole(array|string $role, string $type = self::CHECK_TYPE_AND, bool $strict = false, ?User $user = null): bool;

    public function checkRoute(string $routeName, ?User $user = null): bool;

    public function getRoutesData(): array;

    public function hasAdminAccess(): bool;

    public function isAdmin(): bool;

    public function isRoot(): bool;

    public function isUser(): bool;

    public function isDeepMode(): bool;

    public function isLoggedIn(): bool;

    public function setUser(?User $user): void;

    public function permissions(bool $onlyIds = false): array;

    public function roles(bool $onlyIds = false): array;

    public function user(): ?User;

    public function attachRole(array|string $role, ?User $user = null): void;

    public function detachRole(array|string $role, ?User $user = null): void;

    public function repository(): IAclRepository;
}