<?php

namespace Omadonex\LaravelTools\Support\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

abstract class History extends Model
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

    public function historyEvent(): BelongsTo
    {
        return $this->belongsTo(HistoryEvent::class);
    }
}
