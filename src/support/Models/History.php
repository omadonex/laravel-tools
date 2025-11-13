<?php

namespace Omadonex\LaravelTools\Support\Models;


use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Omadonex\LaravelTools\Acl\Models\User;

abstract class History extends OmxModel
{
    protected $guarded = [ 'id' ];
    protected $fillable = ['model_id', 'user_id', 'history_event_id', 'data', 'occur_at'];
    protected $casts = [
        'data' => 'object',
        'occur_at' => 'datetime',
    ];
    public $timestamps = false;

    public static function historyCasts(): array {
        return [];
    }

    // RELATIONS

    public function event(): BelongsTo
    {
        return $this->belongsTo(HistoryEvent::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
