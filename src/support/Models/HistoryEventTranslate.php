<?php

namespace Omadonex\LaravelTools\Support\Models;

use Illuminate\Database\Eloquent\Model;

class HistoryEventTranslate extends Model
{
    protected $table = 'support_history_event_translate';
    protected $fillable = ['model_id', 'lang', 'name'];
    public $timestamps = false;
}
