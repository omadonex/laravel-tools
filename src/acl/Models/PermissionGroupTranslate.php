<?php

namespace Omadonex\LaravelTools\Acl\Models;

use Omadonex\LaravelTools\Support\Models\OmxModel;

class PermissionGroupTranslate extends OmxModel
{
    protected $table = 'acl_permission_group_translate';
    protected $fillable = ['model_id', 'lang', 'name', 'description'];
    public $timestamps = false;
}
