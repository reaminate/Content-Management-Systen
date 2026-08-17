<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\UserController;

Route::apiResource('category', CategoryController::class);
Route::apiResource('image', ImageController::class);
Route::apiResource('tag', TagController::class);
Route::apiResource('blog', BlogController::class);
Route::apiResource('author', AuthorController::class);
Route::apiResource('page', PageController::class);
Route::apiResource('user', UserController::class);