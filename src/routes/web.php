<?php

use Illuminate\Support\Facades\Route;
use App\Events\NewTaskDidCreateEvent;
use App\Group;

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

Route::middleware('auth')->get('/groups/{group}', 'GroupController@show');

Route::get('/new-task', function () {
    NewTaskDidCreateEvent::dispatch(App\User::first(), 'task.'.random_int(1, 100));

    return 'Done!';
});

Route::get('/tasks', 'TaskController@index');
Route::post('/tasks', 'TaskController@store');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
