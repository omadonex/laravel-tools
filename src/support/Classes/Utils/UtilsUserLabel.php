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

    public static function selectSql(string $columnName): string
    {
        return "
            u.first_name as {$columnName}_u_fname,
            u.last_name as {$columnName}_u_lname,
            u.opt_name as {$columnName}_u_oname,
            u.display_name as {$columnName}_u_display,
            u.username as {$columnName}_u_username,
            u.id as {$columnName}_label
        ";
    }
}
