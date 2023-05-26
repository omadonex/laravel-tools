<?php

namespace Omadonex\LaravelTools\Acl\Interfaces;


interface IAclRepository
{
    public function addRole($user, array|string $role): void;

    public function addPermissionToRole($role, array|string $permission): void;

    public function addPermissionToUser($user, array|string $permission): void;

    public function removeRole($user, array|string $role): void;

    public function removePermissionFromRole($role, array|string $permission): void;

    public function removePermissionFromUser($user, array|string $permission): void;

    public function getAllPermissionList(): array;

    public function getAllRoleList(bool $permissions = true): array;
}