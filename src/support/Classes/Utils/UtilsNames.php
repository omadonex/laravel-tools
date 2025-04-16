<?php

namespace Omadonex\LaravelTools\Support\Classes\Utils;

class UtilsNames
{
    public static function camelName($dotName)
    {
        $dotParts = explode('.', $dotName);
        $countParts = count($dotParts);
        $name = $dotParts[0];

        for ($i = 1; $i < $countParts; $i++) {
            $name .= ucfirst($dotParts[$i]);
        }

        return $name;
    }

    public static function camelToDashed(string $str): string
    {
        return strtolower(preg_replace('/([a-zA-Z])(?=[A-Z])/', '$1-', $str));
    }

    public static function camelToUnderscore(string $str): string
    {
        return strtolower(preg_replace('/([a-zA-Z])(?=[A-Z])/', '$1_', $str));
    }
}
