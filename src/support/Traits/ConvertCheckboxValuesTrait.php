<?php

namespace Omadonex\LaravelTools\Support\Traits;

trait ConvertCheckboxValuesTrait
{
    public function convertCheckBoxValues(array $data): array {
        return array_map(function ($item) {
            if ($item === 'on') {
                return 1;
            }

            if ($item === 'off') {
                return 0;
            }

            return $item;
        }, $data);
    }
}
