<?php

namespace Omadonex\LaravelTools\Acl\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserMeta extends Model
{
    protected $table = 'user_meta';
    protected $fillable = ['user_id', 'display_name', 'first_name', 'last_name', 'opt_name', 'avatar'];
    protected $primaryKey = 'user_id';
    public $timestamps = false;
    public $incrementing = false;

    //Relations

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
