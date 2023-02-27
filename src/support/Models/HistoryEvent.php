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
    const CREATE_WITH_T = 2;
    const CREATE_T = 3;
    const UPDATE = 4;
    const UPDATE_WITH_T = 5;
    const UPDATE_T = 6;
    const DELETE = 7;
}
