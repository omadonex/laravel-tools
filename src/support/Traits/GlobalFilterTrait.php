<?php

namespace Omadonex\LaravelTools\Support\Traits;

trait GlobalFilterTrait
{
    public function getFilterGlobal(): array
    {
        return session('filter', []);
    }

    public function getFilterPage(string $pageId): array
    {
        $filterGlobal = $this->getFilterGlobal();

        return $filterGlobal[$pageId] ?? [];
    }

    public function getFilterTable(string $pageId, string $tableId): array
    {
        $filterPage = $this->getFilterPage($pageId);

        return $filterPage[$tableId] ?? [];
    }

    public function updateFilter(array $requestData, string $pageId, string $tableId, bool $keepOld = false): array
    {
        $filterGlobal = $this->getFilterGlobal();

        $filterRequest = $this->evalFilter($tableId, $requestData);
        if (!$keepOld) {
            $filterGlobal[$pageId][$tableId] = $filterRequest;
        } else {
            $filterTable = $this->getFilterTable($pageId, $tableId);
            foreach ($filterRequest as $key => $value) {
                $filterTable[$key] = $value;
            }
            $filterGlobal[$pageId][$tableId] = $filterTable;
        }

        session(['filter' => $filterGlobal]);

        return $this->getFilterTable($pageId, $tableId);
    }

    public function evalFilter(string $tableId, array $requestData): array
    {
        $str = "{$tableId}__filter_";
        $filter = [];
        foreach ($requestData as $key => $value) {
            if (str_contains($key, $str)) {
                $filter[str_replace($str, '', $key)] = $value;
            }
        }

        return $filter;
    }

    public function clearFilter(string $pageId = '', string $tableId = ''): void
    {
        $filterGlobal = $this->getFilterGlobal();
        if (!$pageId) {
            $filterGlobal = [];
        } elseif (!$tableId) {
            unset($filterGlobal[$pageId]);
        } else {
            unset($filterGlobal[$pageId][$tableId]);
        }

        session(['filter' => $filterGlobal]);
    }
}
