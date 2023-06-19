<?php

use App\Http\Controllers\TodoController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix("v1")->group(function () {

    // todos
    Route::get('/todos', [TodoController::class, 'findTodos']);
    Route::get('/todos/{id}', [TodoController::class, 'getTodo']);
    Route::post('/todos', [TodoController::class, 'createTodo']);
    Route::patch('/todos/{id}', [TodoController::class, 'updateTodo']);
    Route::delete('/todos/{id}', [TodoController::class, 'deleteTodo']);


    // users
    Route::get('/users', [UserController::class, 'getAllUsers']);
    Route::get('/users/{id}', [UserController::class, 'getUserById']);
    Route::post('/users', [UserController::class, 'createUser']);
    Route::patch('/users/{id}', [UserController::class, 'updateUserById']);
});
