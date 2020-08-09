<?php

use App\Events\NewTask;
use App\Task;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*  -------------------------
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
    Auth::loginUsingId(request('id'));
    return view('welcome');
    // return User::create([
    // "name" => "user" . rand(1, 10000), 
    // "email" => "e" . rand(1, 10000) . "@t.com", 
    // "password" => "123"
    // ]);
});

Route::get("task", function () {
    return Task::get()->toArray();
});

Route::post("task", function () {
    $task =  Task::create(['name' => request('name')]);
    event(new NewTask($task));
    return $task;
});

Route::delete("task/{id}", function ($id) {
    return Task::whereId($id)->delete();
});

Auth::routes();
Route::get('/home', 'HomeController@index')->name('home');
