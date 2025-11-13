<?php

namespace Omadonex\LaravelTools\Support\Models;


class HistoryEventTranslate extends OmxModel
{
    protected $table = 'support_history_event_translate';
    protected $fillable = ['model_id', 'lang', 'name'];
    public $timestamps = false;
}
