<?php

namespace Omadonex\LaravelTools\Acl\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Omadonex\LaravelTools\Acl\Repositories\RoleRepository;
use Omadonex\LaravelTools\Support\Models\History;

class UserHistory extends History
{
    protected $table = 'user_history';

    public const HIDDEN_FIELDS = ['password'];

    public static function historyCasts(): array
    {
        return [
            'role_id' => [
                'callback' => function ($value) {
                    $role = app(RoleRepository::class)->find($value);
                    $roleTranslate = $role->getTranslate();

                    return $roleTranslate->name;
            }],
        ];
    }

    public function model(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id', 'model_id');
    }
}
