<?php

namespace Omadonex\LaravelTools\Acl\Models;

use Illuminate\Database\Eloquent\Model;

class PermissionGroupTranslate extends Model
{
    protected $table = 'acl_permission_group_translate';
    protected $fillable = ['model_id', 'lang', 'name', 'description'];
    public $timestamps = false;
}
