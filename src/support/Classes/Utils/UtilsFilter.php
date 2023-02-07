<?php

namespace Omadonex\LaravelTools\Support\Classes\Utils;

class UtilsFilter
{
    const STRING_LIKE = 'stringLike';

    public static function apply($qb, $filterValues, $filterTypes)
    {
        foreach ($filterValues as $key => $value) {
            if (!(($filterTypes[$key] ?? false))) {
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
}