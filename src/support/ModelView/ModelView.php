<?php

namespace Omadonex\LaravelTools\Support\ModelView;


use Omadonex\LaravelTools\Acl\Repositories\UserRepository;
use Omadonex\LaravelTools\Support\Tools\Color;

abstract class ModelView
{
    const FILTER_INPUT  = 'input';
    const FILTER_SELECT = 'select';
    const FILTER_NONE = 'none';

    protected array $columns = [];
    protected bool $hasActions = true;

    public function getLabel(string $column, bool $includeIcon = false): string
    {
        if (!$includeIcon) {
            $icon = '';
        } else {
            $icon = $this->isSearchable($column) ? getIconHtml('streamline.regular.search-1', 12, Color::SECONDARY) : '';
        }

        return $icon . __("validation.attributes.{$column}");
    }

    public function getStyle(string $column): string
    {
        return $this->columns[$column]['style'] ?? '';
    }

    public function getType(string $column): string
    {
        return $this->columns[$column]['type'] ?? 'string';
    }

    public function getLabels(array $columns = []): array
    {
        $columns = array_keys($this->getColumnsData($columns));

        $labelsData = [];
        foreach ($columns as $column) {
            $labelsData[$column] = $this->getLabel($column);
        }

        return $labelsData;
    }

    public function filterCallbackList(string $column): \Closure
    {
        return function (array $params = []) {
            return [];
        };
    }

    public function getColumnsData(array $columns = []): array
    {
        if (!$columns) {
            return $this->columns;
        }

        $columnsData = [];
        foreach ($columns as $column) {
            $columnsData[$column] = $this->columns[$column];
        }

        return $columnsData;
    }

    public function hasActions(): bool
    {
        return $this->hasActions;
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
        if ($column === 'actions') {
            return false;
        }

        return $this->columns[$column]['searchable'] ?? true;
    }

    protected function userListFilterCallback(): \Closure
    {
        return function (array $params = []) {
            $userRepository = app(UserRepository::class);

            return $userRepository->pluck(trans('placeholders.filter_user_id'), 'username');
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

    public function removeColumns(array $columns): void
    {
        foreach ($columns as $column) {
            unset($this->columns[$column]);
        }
    }
}
