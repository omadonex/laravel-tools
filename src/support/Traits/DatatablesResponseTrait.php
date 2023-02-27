<?php

namespace Omadonex\LaravelTools\Support\Traits;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;

trait DatatablesResponseTrait
{
    public function toDatatablesResponse(
        $request,
        $repository,
        $options,
        $listMethod = 'list',
        $transformerClass = null,
        $transformerParams = [],
        $ignoredSearchColumns = ['actions']
    ) {
        $paginate = $request->length ?? false;
        $start = $request->start ?? 0;
        $length = $request->length ?? 0;

        $columns = array_filter(
            array_map(function ($item) {
                return $item['name'];
            }, $request->all()['columns']),
            function ($item) use ($ignoredSearchColumns) {
                return !in_array($item, $ignoredSearchColumns);
            }
        );

        if ($request->search['value']) {
            $options['search'] = ['columns' => $columns, 'value' => $request->search['value']];
        }

        $options = array_merge($options, [
            'paginate' => $paginate,
            'page' => $paginate ? $start == 0 ? 1 : round($start / $length) + 1 : null,
        ]);

        $order = $request->order ?? [];
        if ($order) {
            $columns = $request->columns;
            $options['closures'][] = function ($query) use ($order, $columns) {
                foreach ($order as $orderInfo) {
                    $query->orderBy($columns[(int)$orderInfo['column']]['name'], $orderInfo['dir']);
                }

                return $query;
            };
        }

        $data = $repository->$listMethod($options);
        $data = $data->toResponse($request)->getData();
        $data->recordsTotal = $data->meta->total;
        $data->recordsFiltered = $data->meta->total;
        $data->draw = $request->draw;

        return $transformerClass ? (new $transformerClass($data, $transformerParams))->getTransformedResponse() : $data;
    }

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

    public function getFilter($request, string $pageId): array
    {
        $sessionFilter = session('filter', [])[Str::of($pageId)->snake()->toString()] ?? [];
        $requestFilter = $this->evalFilter($request);

        return array_merge($sessionFilter, $requestFilter);
    }

    public function clearFilter(string $pageId, string $tableId): void
    {
        $globalFilter = session('filter', []);
        $globalFilter[Str::of($pageId)->snake()->toString()][$tableId] = [];
        session(['filter' => $globalFilter]);
    }

    public function updateFilter($request, string $pageId): array
    {
        $globalFilter = session('filter', []);
        $filter = $this->evalFilter($request);
        $globalFilter[Str::of($pageId)->snake()->toString()][$request->tableId] = $filter;
        session(['filter' => $globalFilter]);

        return $filter;
    }
}
