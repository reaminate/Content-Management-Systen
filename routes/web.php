<?php

use App\Http\Controllers\BlogController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;

Route::get('/page', [PageController::class, 'publicViewIndex']);
Route::get('/menu', [MenuController::class, 'view']);
Route::get('/page/{page:slug}', [PageController::class, 'viewBySlug']);
Route::get('/blog', [BlogController::class, 'publicViewIndex']);
Route::get('/blog/{blog:slug}', [BlogController::class, 'viewBySlug']);
Route::get('/categories', [CategoryController::class,'viewPublicActiveCategories']);
Route::get('/categories/{category}', [CategoryController::class,'viewBlogsForCategory']);
