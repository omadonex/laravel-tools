<?php

namespace Omadonex\LaravelTools\Support\Models;


abstract class TypedKeyValueDictionary extends OmxModel
{
    protected $guarded = [ 'id' ];
    protected $fillable = ['key', 'name', 'description', 'value_type_id', 'value'];
}
