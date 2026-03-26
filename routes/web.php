<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TodoController;

Route::get('/', function () {
    $todos = [];
    if (auth()->check()) {
        $todos = auth()->user()->todos()->latest()->get();
    }
    return view('home', ['todos' => $todos]);
});

Route::get('/login', function () {
    return view('login');
})->name('login');

Route::post('/register', [UserController::class, 'register']);
Route::post('/logout', [UserController::class, 'logout']);
Route::post('/login', [UserController::class, 'login']);

// Todo Routes
Route::post('/todos', [TodoController::class, 'store']);
Route::put('/todos/{todo}', [TodoController::class, 'update']);
Route::delete('/todos/{todo}', [TodoController::class, 'destroy']);