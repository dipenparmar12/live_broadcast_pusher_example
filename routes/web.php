<?php

use App\Events\DeleteTaskEvent;
use App\Events\NewTaskEvent;
use App\Task;
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
    event(new NewTaskEvent($task));
    return $task;
});

Route::delete("task/{id}", function ($id) {
    $task = Task::whereId($id)->first();
    if ($task) {
        // event(new DeleteTaskEvent($task));
        $task->delete();
    }
    return $task;
});

Auth::routes();
Route::get('/home', 'HomeController@index')->name('home');
