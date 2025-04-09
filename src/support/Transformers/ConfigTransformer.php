<?php

namespace Omadonex\LaravelTools\Support\Transformers;

class ConfigTransformer extends BaseTransformer
{
    public function __construct($response, $params = [])
    {
        parent::__construct($response, $params, false);
    }

    protected function transformers(): array
    {
        return [
            'actions' => function ($value, $row, $rowOriginal) {
                return rowEditIcon($row->id);
            },
        ];
    }
}
