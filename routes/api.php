<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TaskController;
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('tasks')->group(function () {
    Route::get('/', [TaskController::class, 'index']);              // List all tasks
    Route::post('/', [TaskController::class, 'store']);             // Create a new task

    Route::get('/{id}', [TaskController::class, 'show']);           // Get a specific task
    
    // [to do] to access secured URLs for security
    Route::put('/{id}', [TaskController::class, 'update']);         // Update a specific task 
    Route::delete('/{id}', [TaskController::class, 'destroy']);     // Delete a specific task
});