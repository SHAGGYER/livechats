<?php

Route::prefix('admin')->group(function (){
    Route::get('init', 'AdminController@init');
    Route::post('login', 'AdminController@login');
    Route::prefix('resource')->group(function (){
        Route::post('/', 'AdminController@resourceIndex');
        Route::post('create', 'AdminController@createResource');
        Route::post('delete', 'AdminController@deleteResource');
        Route::post('update', 'AdminController@updateResource');
        Route::post('find-by-id', 'AdminController@findResourceById');
    });

    Route::get('/', function () {
        return view('admin');
    });
});