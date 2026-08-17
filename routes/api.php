<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\BlogController;

Route::apiResource('category', CategoryController::class);
Route::apiResource('image', ImageController::class);
Route::apiResource('tag', TagController::class);
Route::apiResource('blog', BlogController::class);
Route::apiResource('Author', AuthorController::class);
Route::apiResource('Page', PageController::class);