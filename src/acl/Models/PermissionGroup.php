<?php

namespace Omadonex\LaravelTools\Acl\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Omadonex\LaravelTools\Locale\Traits\TranslateTrait;

class PermissionGroup extends Model
{
    use TranslateTrait;

    protected $table = 'acl_permission_group';
    protected $fillable = ['sort_index'];
    public $incrementing = false;
    public $timestamps = false;

    public $availableRelations = ['translates', 'permissions'];

    public function permissions()
    {
        return $this->hasMany(Permission::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(PermissionGroup::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this
            ->hasMany(PermissionGroup::class, 'parent_id');
            //->with(['children', 'permissions']);
    }
}
