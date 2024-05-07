<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'v1', 'namespace' => '\App\Http\Controllers\Api\V1'], function () {

    Route::post('/login', 'AuthController@login')->name('login');

    Route::group(['prefix' => 'tasks'], function () {

        Route::get('/', 'TaskController@index')
            ->middleware('auth:sanctum')
            ->name('tasks.index');
        Route::post('/', 'TaskController@store')
            ->middleware('auth:sanctum')
            ->name('tasks.store');
        Route::get('/{id}', 'TaskController@show')
            ->middleware('auth:sanctum')
            ->name('tasks.show');
        Route::put('/{id}', 'TaskController@update')
            ->middleware('auth:sanctum')
            ->name('tasks.update');
        Route::delete('/{id}', 'TaskController@destroy')
            ->middleware('auth:sanctum')
            ->name('tasks.destroy');
        Route::patch('/{id}/done', 'TaskController@setDone')
            ->middleware('auth:sanctum')
            ->name('tasks.setDone');
    }); // I tried to propect group by middleware - dead end!

    Route::get('/user', function (Request $request) {
        return $request->user();
    })->middleware('auth:sanctum');

    Route::get('/hash', 'AuthController@hash');
});
