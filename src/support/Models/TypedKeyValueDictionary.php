<?php

namespace Omadonex\LaravelTools\Support\Models;

use Illuminate\Database\Eloquent\Model;

abstract class TypedKeyValueDictionary extends Model
{
    protected $guarded = [ 'id' ];
    protected $fillable = ['key', 'name', 'description', 'value_type_id', 'value'];
}
