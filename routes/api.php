<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TodoController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\TriviaController;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

// Todo Routes - Using Resource Controller
Route::apiResource('todos', TodoController::class);
Route::patch('todos/{todo}/toggle-complete', [TodoController::class, 'toggleComplete']);

// News Routes
