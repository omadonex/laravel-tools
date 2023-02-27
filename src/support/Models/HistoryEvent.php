<?php

namespace Omadonex\LaravelTools\Support\Models;


use Illuminate\Database\Eloquent\Model;

class HistoryEvent extends Model
{
    protected $guarded = [ 'id' ];
    protected $table   = 'support_history_event';
    protected $fillable = ['name'];
    public $timestamps = false;

    const CREATE = 1;
    const UPDATE = 2;
    const DELETE = 3;
    const CREATE_WITH_T = 4;
    const CREATE_T = 5;
}
