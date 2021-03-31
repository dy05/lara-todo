<?php

use Illuminate\Support\Facades\Route;

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
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::post('todos/deleteALl', [\App\Http\Controllers\TodoController::class, 'deleteAll'])->name('todos.deleteAll');
Route::post('todos/done', [\App\Http\Controllers\TodoController::class, 'setManyStatus'])->name('todos.setManyStatus');
Route::post('todos/{todo}/done', [\App\Http\Controllers\TodoController::class, 'setOneStatus'])->name('todos.setOneStatus');
Route::resource('todos', \App\Http\Controllers\TodoController::class);

