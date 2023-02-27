<?php

namespace Omadonex\LaravelTools\Acl\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Omadonex\LaravelTools\Support\Models\History;

class RoleHistory extends History
{
    protected $table = 'acl_role_history';

    // RELATIONS

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'id', 'model_id');
    }
}
