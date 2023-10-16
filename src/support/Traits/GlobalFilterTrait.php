<?php

namespace Omadonex\LaravelTools\Support\Traits;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Omadonex\LaravelTools\Support\Classes\Utils\UtilsCustom;

trait GlobalFilterTrait
{
    public function evalFilter($request): array
    {
        $str = 'filter_';
        $filter = [];
        foreach ($request->all() as $key => $value) {
            if (str_contains($key, $str)) {
                $filter[Arr::last(explode($str, $key))] = $value;
            }
        }

        return $filter;
    }

    public function getFilter($request, string $pageIndex, string $resourceSubPage = ''): array
    {
        $finalPageIndex = $pageIndex . ($resourceSubPage ? "_{$resourceSubPage}" : '');
        $sessionFilter = session('filter', [])[Str::of($finalPageIndex)->snake()->toString()] ?? [];
        $requestFilter = $this->evalFilter($request);

        return array_merge($sessionFilter, $requestFilter);
    }

    public function clearFilter(string $pageIndex, string $tableId, string $resourceSubPage = ''): void
    {
        $finalPageIndex = $pageIndex . ($resourceSubPage ? "_{$resourceSubPage}" : '');
        $globalFilter = session('filter', []);
        $globalFilter[Str::of($finalPageIndex)->snake()->toString()][$tableId] = [];
        session(['filter' => $globalFilter]);
    }

    public function updateFilter($request, string $pageIndex, string $resourceSubPage = ''): array
    {
        $finalPageIndex = $pageIndex . ($resourceSubPage ? "_{$resourceSubPage}" : '');
        $globalFilter = session('filter', []);
        $filter = $this->evalFilter($request);
        $globalFilter[Str::of($finalPageIndex)->snake()->toString()][$request->tableId] = $filter;
        session(['filter' => $globalFilter]);

        return $filter;
    }
}
