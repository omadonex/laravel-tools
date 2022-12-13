<?php

namespace Omadonex\LaravelTools\Support\Traits;

use Omadonex\LaravelTools\Support\Classes\ConstCustom;

trait ProtectedGenerateTrait
{
    public function scopeProtectedGenerate($query)
    {
        return $query->where(ConstCustom::DB_FIELD_PROTECTED_GENERATE, true);
    }

    public function isProtectedGenerate()
    {
        $field = ConstCustom::DB_FIELD_PROTECTED_GENERATE;

        return $this->$field;
    }
}
