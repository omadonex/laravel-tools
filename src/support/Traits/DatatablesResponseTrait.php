<?php

namespace Omadonex\LaravelTools\Support\Traits;

trait DatatablesResponseTrait
{
    public function toDatatablesResponse($request, $repository, $options, $listMethod = 'list', $transformerClass = null, $ignoredSearchColumns = ['actions']) {
        $paginate = $request->length ?? false;
        $start = $request->start ?? 0;
        $length = $request->length ?? 0;

        $columns = array_filter(array_map(function ($item) {
            return $item['name'];
        }, $request->all()['columns']), function ($item) use ($ignoredSearchColumns) {
            return !in_array($item, $ignoredSearchColumns);
        });

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

        return $transformerClass ? (new $transformerClass($data))->getTransformedResponse() : $data;
    }

    public function getFilter(string $pageId): array
    {
        return session('filter', [])[$pageId] ?? [];
    }

    public function clearFilter(string $pageId): void
    {
        $globalFilter = session('filter', []);
        $globalFilter[$pageId] = [];
        session(['filter' => $globalFilter]);
    }

    public function updateFilter($request, string $pageId): array
    {
        $str = 'filter_';
        $globalFilter = session('filter', []);

        $filter = [];
        foreach ($request->all() as $key => $value) {
            if (substr($key, 0, strlen($str)) === $str) {
                $filter[substr($key, strlen($str))] = $value;
            }
        }

        $globalFilter[$pageId] = $filter;
        session(['filter' => $globalFilter]);

        return $filter;
    }
}
