<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ReadFolderController; 
use App\Http\Controllers\FileController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function() {
    Route::post('/logout', [AuthController::class, 'logout']); 
    Route::apiResource('/file', FileController::class);
    Route::apiResource('/category', CategoryController::class);
    Route::get('/read/{parent?}', [ReadFolderController::class, 'index'] )->name('read-folder');
});


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']); 

