<?php

use Illuminate\Support\Facades\Route;
use Omadonex\LaravelTools\Support\Http\Controllers\CommentController;
use Omadonex\LaravelTools\Acl\Http\Middleware\Acl;

Route::middleware(['web', 'auth', Acl::class])->group(function () {
    Route::group(['prefix' => 'omx/support', 'as' => 'omx.support.'], function (): void {
        Route::post('comment', [CommentController::class, 'store'])->name('comment.store');
        Route::put('comment/{id}', [CommentController::class, 'update'])->name('comment.update');
        Route::post('reply/{id}', [CommentController::class, 'reply'])->name('comment.reply');
        Route::delete('comment/{id}', [CommentController::class, 'destroy'])->name('comment.destroy');
    });
});
