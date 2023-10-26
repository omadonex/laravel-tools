<?php

namespace Omadonex\LaravelTools\Support\Classes\Tools;

use Illuminate\Container\Container;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;

class PaginatorHelper
{
    public static function paginate(Collection $collection, $currentPage, $showPerPage)
    {
        $totalPageNumber = $collection->count();

        return self::paginator($collection->forPage($currentPage, $showPerPage), $totalPageNumber, $showPerPage, $currentPage, [
            'path' => Paginator::resolveCurrentPath(),
        ]);

    }

    /**
     * Create a new length-aware paginator instance.
     *
     * @param  \Illuminate\Support\Collection  $items
     * @param  int  $total
     * @param  int  $perPage
     * @param  int  $currentPage
     * @param  array  $options
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    protected static function paginator($items, $total, $perPage, $currentPage, $options)
    {
        return Container::getInstance()->makeWith(LengthAwarePaginator::class, compact(
            'items', 'total', 'perPage', 'currentPage', 'options'
        ));
    }
}
