<?php

namespace Omadonex\LaravelTools\Support\ModelView;

use Omadonex\LaravelTools\Support\Tools\Lists;

class ConfigView extends TypedKeyValueDictionaryModelView
{
    public function filterCallbackList(string $column): \Closure
    {
        return [
            'value_type_id_label' => Lists::get('configValueType', closure: true),
        ][$column];
    }
}
