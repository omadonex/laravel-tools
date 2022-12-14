<?php

namespace Omadonex\LaravelTools\Acl\Models;

use Illuminate\Database\Eloquent\Model;
use Omadonex\LaravelTools\Support\Classes\ConstCustom;
use Omadonex\LaravelTools\Support\Traits\ProtectedGenerateTrait;

class RoleTranslate extends Model
{
    use ProtectedGenerateTrait;

    protected $table = 'acl_role_translate';
    protected $fillable = ['model_id', 'lang', 'name', 'description'];
    public $timestamps = false;

    protected $casts = [
        ConstCustom::DB_FIELD_PROTECTED_GENERATE => 'boolean',
    ];
}
