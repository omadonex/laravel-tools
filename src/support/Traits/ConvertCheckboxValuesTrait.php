<?php

namespace Omadonex\LaravelTools\Support\Traits;

trait ConvertCheckboxValuesTrait
{
    public function convertFromCheckboxValues(array $data, array $fields): array
    {
        foreach ($data as $key => $value) {
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
}
