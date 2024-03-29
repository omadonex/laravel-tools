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
    const CREATE_T = 2;
    const UPDATE = 3;
    const UPDATE_T = 4;
    const DELETE = 5;
    const DELETE_T = 6;
    const DELETE_T_ALL = 7;
}
