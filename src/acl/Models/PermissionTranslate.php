<?php

namespace Omadonex\LaravelTools\Acl\Models;

use Omadonex\LaravelTools\Support\Models\OmxModel;

class PermissionTranslate extends OmxModel
{
    protected $table = 'acl_permission_translate';
    protected $fillable = ['model_id', 'lang', 'name', 'description'];
    public $timestamps = false;
}
