<?php

namespace Omadonex\LaravelTools\Support\Models;

use Illuminate\Database\Eloquent\Model;

class OmxModel extends Model
{
    public function getJstHiddenFieldsAttribute()
    {
        return $this->attributes['jstHiddenFields'] ?? $this->jstHiddenFields ?? null;
    }

    public function hideFormFields(array $fields)
    {
        $this->jstHiddenFields = $fields;
    }
}
