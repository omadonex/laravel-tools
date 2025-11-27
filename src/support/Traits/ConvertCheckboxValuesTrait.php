<?php

namespace Omadonex\LaravelTools\Support\Traits;

use Illuminate\Http\Resources\Json\JsonResource;

trait ConvertCheckboxValuesTrait
{
    public function convertFromCheckboxValues(array $data, array $fields): array
    {
        foreach ($data as $key => $value) {
            $key = str_replace('_checkbox', '', $key);
            if (in_array($key, $fields)) {
                $data[$key] = $value === 'on';
            }
        }

        return $data;
    }

    public function convertToCheckboxValues(array $data, array $fields): array
    {
        foreach ($data as $key => $value) {
            if (in_array($key, $fields)) {
                $data[$key] = $value ? 'on' : 'off';
            }
        }

        return $data;
    }

    public function convertToCheckboxValuesResource(JsonResource $resource, array $fields): JsonResource
    {
        foreach ($fields as $field) {
            $resource->$field = $resource->$field ? 'on' : 'off';
        }

        return $resource;
    }

    public function convertToCheckboxValue(bool $value): string
    {
        return $value ? 'on' : 'off';
    }

    public function convertFromCheckboxValue(string $value): bool
    {
        return $value === 'on';
    }
}
