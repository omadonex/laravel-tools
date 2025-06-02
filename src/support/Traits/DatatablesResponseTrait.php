<?php

namespace Omadonex\LaravelTools\Support\Traits;

use Omadonex\LaravelTools\Support\Classes\Tools\PaginatorHelper;
use Omadonex\LaravelTools\Support\Classes\Utils\UtilsCustom;

trait DatatablesResponseTrait
{
    use GlobalFilterTrait;

    public function toDatatablesResponseData(
        $request,
        $collection,
        $transformerClass = null,
        $transformerParams = [],
        $ignoredSearchColumns = ['actions']
    ) {
        $requestData = $request->all();
        $options['filter'] = $this->updateFilter($requestData, $requestData['pageId'], $requestData['tableId']);

        $paginate = $request->length ?? false;
        $start = $request->start ?? 0;
        $length = $request->length ?? 0;

        $columns = array_map(function ($item) {
            return $item['name'];
        }, array_filter(
            $request->all()['columns'],
            function ($item) use ($ignoredSearchColumns) {
                return UtilsCustom::strictStrToBool($item['searchable']) && !in_array($item['name'], $ignoredSearchColumns);
            }
        ));

        $params = array_map(function ($item) {
            return str_replace('params_', '', $item);
        }, array_filter(array_keys($request->all()), function ($item) {
            return strpos($item, 'params_') === 0;
        }));

        foreach ($params as $param) {
            $p = "params_{$param}";
            $value = $request->$p;
            $options['closures'][] = function ($query) use ($param, $value)  {
                return $query->where($param, $value);
            };
        }

        if ($request->search['value'] ?? false) {
            $options['search'] = ['columns' => $columns, 'value' => $request->search['value']];
        }

        $options['paginate'] = $paginate;
        $options['page'] = $paginate ? $start == 0 ? 1 : round($start / $length) + 1 : null;

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

        $data = new \stdClass;
        if ($options['paginate']) {
            $paginatedData = PaginatorHelper::paginate($collection, $options['page'], $options['paginate'])->toArray();
            $data->data = json_decode(json_encode($paginatedData['data']), false);
            $links = new \stdClass;
            $links->first = $paginatedData['first_page_url'];
            $links->last = $paginatedData['last_page_url'];
            $links->prev = $paginatedData['prev_page_url'];
            $links->next = $paginatedData['next_page_url'];
            $data->links = $links;
            $meta = new \stdClass;
            $meta->current_page = $paginatedData['current_page'];
            $meta->from = $paginatedData['from'];
            $meta->last_page = $paginatedData['last_page'];
            $meta->links = $paginatedData['links'];
            $meta->path = $paginatedData['path'];
            $meta->per_page = $paginatedData['per_page'];
            $meta->to = $paginatedData['to'];
            $meta->total = $paginatedData['total'];
            $data->meta = $meta;
            $data->recordsTotal = $meta->total;
            $data->recordsFiltered = $meta->total;
        }
        $data->appends = $requestData;
        $data->draw = $request->draw;

        return $transformerClass ? (new $transformerClass($data, $transformerParams))->getTransformedResponse() : $data;
    }

    public function toDatatablesResponse(
        $request,
        $repository,
        $options,
        $listMethod = 'grid',
        $transformerClass = null,
        $transformerParams = [],
        $ignoredSearchColumns = ['actions']
    ) {
        $requestData = $request->all();

        $options['filter'] = $this->updateFilter($requestData, $requestData['pageId'], $requestData['tableId']);

        $length = $request->length ?? 0;
        $start = $request->start ?? 0;
        $paginate = $length > 0;

        $columns = array_map(function ($item) {
            return $item['name'];
        }, array_filter(
            $request->all()['columns'],
            function ($item) use ($ignoredSearchColumns) {
                return UtilsCustom::strictStrToBool($item['searchable']) && !in_array($item['name'], $ignoredSearchColumns);
            }
        ));

        $params = array_map(function ($item) {
            return str_replace('params_', '', $item);
        }, array_filter(array_keys($request->all()), function ($item) {
            return strpos($item, 'params_') === 0;
        }));

        foreach ($params as $param) {
            $p = "params_{$param}";
            $value = $request->$p;
            $options['closures'][] = function ($query) use ($param, $value)  {
                return $query->where($param, $value);
            };
        }

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
        if ($data->meta ?? false) {
            $data->recordsTotal = $data->meta->total;
            $data->recordsFiltered = $data->meta->total;
        } else {
            $count = count($data->data);
            $data->recordsTotal = $count;
            $data->recordsFiltered = $count;
        }
        $data->draw = $request->draw;

        return $transformerClass ? (new $transformerClass($data, $transformerParams))->getTransformedResponse() : $data;
    }
}
