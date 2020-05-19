<?php

Route::middleware('auth')->group(function () {
    Route::middleware('can:view,group')->group(function () {
        Route::get('/groups/{group}', 'Api\GroupController@show')->name('groups.show');
        Route::post('/groups/{group}/tasks', 'TaskController@store')->name('task.store');
    });
    Route::post('/groups', 'Api\GroupController@store')->name('groups.store');
});
