<?php

namespace Omadonex\LaravelTools\Support\ModelView;


use Carbon\Carbon;
use Omadonex\LaravelTools\Acl\Repositories\UserRepository;
use Omadonex\LaravelTools\Support\Classes\Utils\UtilsCustom;
use Omadonex\LaravelTools\Support\Tools\Color;

abstract class ModelView
{
    const FILTER_INPUT  = 'input';
    const FILTER_SELECT = 'select';
    const FILTER_NONE = 'none';

    const TYPE_INT = 'int';
    const TYPE_STRING = 'string';
    const TYPE_CALLBACK = 'callback';
    const TYPE_DT = 'dt';

    protected int $viewColumnsCount = 1;
    
    protected array $columns = [];
    protected array $columnsAppend = [];
    protected array $columnsPrepend = [];
    protected array $columnsSpecific = [];
    protected array $columnsImport = [];

    protected bool $hasActions = true;
    protected bool $hasActionsPre = false;
    protected bool $hasPrependEmpty = false;
    protected array $ignoreList = [];

    public function setIgnoreList(array $ignoreList): void
    {
        $this->ignoreList = $ignoreList;
    }

    public function getIgnoreList(): array
    {
        return $this->ignoreList;
    }

    public function appendColumns(array $columnsData = []): void
    {
        $this->columnsAppend = $columnsData;
    }

    public function prependColumns(array $columnsData = []): void
    {
        $this->columnsPrepend = $columnsData;
    }

    public function specificColumns(array $data = []): void
    {
        $this->columnsSpecific = $data;
    }

    public function getColumns(array $columnsNames = [], bool $notIncluded = false, bool $showHidden = false): array
    {
        $columnsList = array_merge(
            $this->columnsPrepend,
            $this->columns,
            $this->columnsAppend,
        );

        $columnsFinalList = [];
        foreach ($columnsList as $key => $value) {
            if (!in_array($key, $this->ignoreList) && ($showHidden || !($value['hidden'] ?? false))) {
                $columnsFinalList[$key] = $value;
            }
        }

        if (!$columnsNames) {
            return $columnsFinalList;
        }

        $columnsFilteredList = [];
        if ($notIncluded) {
            foreach ($columnsFinalList as $key => $value) {
                if (!in_array($key, $columnsNames)) {
                    $columnsFilteredList[$key] = $columnsFinalList[$key];
                }
            }
        } else {
            foreach ($columnsNames as $columnName) {
                $columnsFilteredList[$columnName] = $columnsFinalList[$columnName];
            }
        }

        return $columnsFilteredList;
    }

    public function getLabels(array $columnsNames = [], bool $notIncluded = false, bool $showHidden = false): array
    {
        $columns = array_keys($this->getColumns($columnsNames, $notIncluded, $showHidden));

        $labelsData = [];
        foreach ($columns as $column) {
            $labelsData[$column] = $this->getLabel($column);
        }

        return $labelsData;
    }

    public function getLabel(string $column, bool $includeIcon = false): string
    {
        if (!$includeIcon) {
            $icon = '';
        } else {
            $icon = $this->isSearchable($column) ? getIconHtml('streamline.regular.search-1', 12, Color::SECONDARY) : '';
        }

        return $icon . ($column == 'empty' ? '' : __("validation.attributes.{$column}"));
    }

    public function getStyle(string $column): string
    {
        return $this->getColumns([], true, true)[$column]['style'] ?? '';
    }

    public function getType(string $column): string
    {
        return $this->columns[$column]['type'] ?? 'string';
    }

    public function getRelationData(string $column): array
    {
        return $this->columns[$column]['relation'];
    }

    public function getTranslateData(string $column): array
    {
        return $this->columns[$column]['translate'];
    }

    public function getKeyData(string $column): array
    {
        return $this->columns[$column]['key'];
    }

    public function getMoneyData(string $column): array
    {
        return $this->columns[$column]['money'];
    }

    public function getDateData(string $column): array
    {
        return $this->columns[$column]['dt'];
    }

    public function getCallbackData(string $column): array
    {
        return $this->columns[$column]['callback'];
    }

    public function filterCallbackList(string $column): \Closure
    {
        return function (array $params = []) {
            return [];
        };
    }

    public function importCallbackList(string $column): \Closure
    {
        return function (array $params = []) {
            return [];
        };
    }

    public function hasActions(): bool
    {
        return $this->hasActions;
    }

    public function hasActionsPre(): bool
    {
        return $this->hasActionsPre;
    }

    public function hasPrependEmpty(): bool
    {
        return $this->hasPrependEmpty;
    }

    public function isFilterInput(string $column): bool
    {
        return ($this->columns[$column]['filter'] ?? null) === self::FILTER_INPUT;
    }

    public function isFilterSelect(string $column): bool
    {
        return ($this->columns[$column]['filter'] ?? null) === self::FILTER_SELECT;
    }

    public function isFilterNone(string $column): bool
    {
        return ($this->columns[$column]['filter'] ?? null) === self::FILTER_NONE;
    }

    public function isSearchable(string $column): bool
    {
        if (in_array($column, ['actions', 'actions_pre'])) {
            return false;
        }

        return $this->columns[$column]['searchable'] ?? true;
    }

    protected function userListFilterCallback(UserRepository $userRepository): \Closure
    {
        return function (array $params = []) use ($userRepository) {
            return $userRepository->pluckExt(trans('placeholders.filter_user_id'));
        };
    }

    public function getKeyColumn(string $column): string
    {
        return $this->columns[$column]['keyColumn'] ?? $column;
    }

    public function setHasActions(bool $hasActions): void
    {
        $this->hasActions = $hasActions;
    }

    public function setHasActionsPre(bool $hasActionsPre): void
    {
        $this->hasActionsPre = $hasActionsPre;
    }

    public function setHasPrependEmpty(bool $hasPrependEmpty): void
    {
        $this->hasPrependEmpty = $hasPrependEmpty;
    }

    public function getSpecificColumns(): array
    {
        return $this->columnsSpecific;
    }

    public function columnsInfo(array $filter, string $tableId, array $columnsNames = [], bool $notIncluded = false, bool $showHidden = false): array
    {
        $filterColumns = data_get($filter, [$tableId, 'columns']);
        if (empty($filterColumns)) {
            $columnsData = $this->getColumns($columnsNames, $notIncluded, $showHidden);
        } else {
            $columnsData = $this->getColumns(json_decode($filterColumns));
        }
        $columnsNames = array_keys($columnsData);

        return [$columnsData, $columnsNames];
    }
    
    public function getViewColumnsCount(): int
    {
        return $this->viewColumnsCount;
    }

    public function getColumnsImport(): array
    {
        return $this->columnsImport;
    }

    public function getImportRandomValue(string $column): mixed
    {
        $importData = $this->getColumnsImport()[$column];
        $type = $importData['type'];
        $list = $importData['list'] ?? false;

        if ($list) {
            $values = array_keys($this->importCallbackList($column)());
            $index = random_int(0, count($values) - 1);

            return $values[$index];
        }

        switch ($type) {
            case 'dt':
                return Carbon::now();
            case 'int':
                return random_int(0, 10000);
            case 'money':
                return number_format((mt_rand() / mt_getrandmax()) * random_int(1, 10000000), 2, ',', '');
            case 'float':
                return (mt_rand() / mt_getrandmax()) * random_int(1, 1000);
            case 'string':
                return UtilsCustom::random_str(random_int(5, 30));
            case 'percent':
                return number_format((mt_rand() / mt_getrandmax()) * random_int(1, 100), 2, ',');
        }

        return null;
    }
}
