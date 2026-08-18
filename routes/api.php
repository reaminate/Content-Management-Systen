<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RecoverController;
use App\Http\Controllers\Api\AuthController;


Route::post('/login', [AuthController::class, 'login']);
Route::apiResource('user', UserController::class);
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class,'logout']);
    Route::get('/logged', [AuthController::class, 'loggedInUsers']);
    Route::post('/restore',[RecoverController::class,'restore']);
    Route::delete('/delete', [RecoverController::class,'destroy']);
    Route::apiResource('category', CategoryController::class);
    Route::apiResource('image', ImageController::class);
    Route::apiResource('tag', TagController::class);
    Route::apiResource('blog', BlogController::class);
    Route::apiResource('author', AuthorController::class);
    Route::apiResource('page', PageController::class);

});
