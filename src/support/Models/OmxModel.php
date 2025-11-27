<?php

namespace Omadonex\LaravelTools\Support\Models;

use Illuminate\Database\Eloquent\Model;

class OmxModel extends Model
{
    public function getJstDisabledFieldsAttribute()
    {
        return $this->attributes['jstDisabledFields'] ?? $this->jstDisabledFields ?? null;
    }

    public function disabledFormFields(array $fields)
    {
        $this->jstDisabledFields = $fields;
    }

    public function getJstHiddenFieldsAttribute()
    {
        return $this->attributes['jstHiddenFields'] ?? $this->jstHiddenFields ?? null;
    }

    public function hideFormFields(array $fields)
    {
        $this->jstHiddenFields = $fields;
    }

    public function getJstReadonlyFieldsAttribute()
    {
        return $this->attributes['jstReadonlyFields'] ?? $this->jstReadonlyFields ?? null;
    }

    public function readonlyFormFields(array $fields)
    {
        $this->jstReadonlyFields = $fields;
    }
}
