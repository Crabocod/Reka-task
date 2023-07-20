<?php

use App\Http\Controllers\ListController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

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

Route::get('/', [ListController::class, 'index'])->name('index');

Route::post('/sign-up', [UserController::class, 'signUp']);
Route::post('/login', [UserController::class, 'login']);


Route::middleware(['auth'])->group(function () {
    Route::get('/logout', [UserController::class, 'logout'])->name('logout');
    Route::get('/lists/{id}', [ListController::class, 'detail'])->name('lists');
    Route::get('/tasksSearch', [TaskController::class, 'search']);

    Route::post('/addList', [ListController::class, 'create']);
    Route::post('/editList', [ListController::class, 'edit']);
    Route::post('/deleteList', [ListController::class, 'delete']);

    Route::post('/addTask', [TaskController::class, 'create']);
    Route::post('/editTask', [TaskController::class, 'edit']);
    Route::post('/changeTaskStatus', [TaskController::class, 'changeStatus']);
    Route::post('/deleteTask', [TaskController::class, 'delete']);
});



