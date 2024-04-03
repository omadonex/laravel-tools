<?php

use Illuminate\Support\Facades\Route;
use Omadonex\LaravelTools\Acl\Http\Controllers\AclController;
use Omadonex\LaravelTools\Acl\Http\Middleware\Acl;

Route::middleware(['auth', Acl::class])->group(function () {
    Route::group(['prefix' => 'omx/acl', 'as' => 'omx.acl.'], function (): void {
        //           Route::get('/table', ['as' => 'table', 'uses' => 'Acl\AclController@table']);
        Route::get('/route', [AclController::class, 'route'])->name('route');
    });
});
