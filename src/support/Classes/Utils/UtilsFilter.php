<?php

namespace Omadonex\LaravelTools\Support\Classes\Utils;

class UtilsFilter
{
    const STRING_LIKE = 'stringLike';
    const EQUALS      = 'equals';

    public static function apply($qb, $filterValues, $filterTypes)
    {
        foreach ($filterValues as $key => $value) {
            if (!(($filterTypes[$key] ?? false)) || empty($value)) {
                continue;
            }

            $filterData = $filterTypes[$key];
            $methodName = 'filter' . ucfirst($filterData['type']);

            call_user_func_array([self::class, $methodName], [$qb, $key, $value]);
        }

        return $qb;
    }

    private static function filterStringLike($qb, $key, $value)
    {
        $qb->where($key, 'like', "%{$value}%");

        return $qb;
    }

    private static function filterEquals($qb, $key, $value)
    {
        $qb->where($key, $value);

        return $qb;
    }
}