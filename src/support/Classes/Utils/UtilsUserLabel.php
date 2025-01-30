<?php

namespace Omadonex\LaravelTools\Support\Classes\Utils;

use Omadonex\LaravelTools\Support\Classes\Dto\DtoUserNames;
use Omadonex\LaravelTools\Support\Classes\Tools\ToolsPersonNames;

class UtilsUserLabel
{
    public static function getFromResource($resource, string $column): string
    {
        if ($resource->$column === null) {
            return '';
        }

        $userIdPersonNames = new ToolsPersonNames(DtoUserNames::parseFromResource($resource, $column));

        return $userIdPersonNames->getFullName();
    }

    public static function getFromModel($user): string
    {
        $userIdPersonNames = new ToolsPersonNames(DtoUserNames::parseFromUser($user));

        return $userIdPersonNames->getFullName();
    }

    public static function selectSql(string $columnName, string $userTableAlias = 'u'): string
    {
        return "
            {$userTableAlias}.first_name as {$columnName}_u_fname,
            {$userTableAlias}.last_name as {$columnName}_u_lname,
            {$userTableAlias}.opt_name as {$columnName}_u_oname,
            {$userTableAlias}.display_name as {$columnName}_u_display,
            {$userTableAlias}.username as {$columnName}_u_username,
            {$userTableAlias}.id as {$columnName}_label
        ";
    }
}
