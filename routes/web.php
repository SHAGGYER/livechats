<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('app');
});


Route::get('init', 'AppController@init');
Route::post('login', 'AppController@login');
Route::post('register', 'AppController@register');
Route::post('logout', 'AppController@logout');

Route::prefix('message')->group(function() {
    Route::post('send', 'MessageController@send');
    Route::get('/{roomId}', 'MessageController@getMessages');
});

Route::prefix('room')->group(function() {
    Route::get('all', 'RoomController@getRooms');
    Route::get('/{id}', 'RoomController@getRoomById');
    Route::post('increase-active', 'RoomController@increaseActive');
    Route::post('decrease-active', 'RoomController@decreaseActive');
    Route::post('update-active', 'RoomController@updateActive');
});


Route::prefix('admin')->group(function (){
    Route::get('init', 'AdminController@init');
    Route::post('login', 'AdminController@login');
    Route::post('logout', 'AdminController@logout');
    Route::prefix('resource')->group(function (){
        Route::post('/', 'AdminController@resourceIndex');
        Route::post('create', 'AdminController@createResource');
        Route::post('delete', 'AdminController@deleteResource');
        Route::post('update', 'AdminController@updateResource');
        Route::post('find-by-id', 'AdminController@findResourceById');
        Route::post('attach-relationship', 'AdminController@attachRelationship');
        Route::post('detach-relationship', 'AdminController@detachRelationship');
        Route::post('get-relationship', 'AdminController@getRelationshipResources');
    });

    Route::get('/', function () {
        return view('admin');
    });
});