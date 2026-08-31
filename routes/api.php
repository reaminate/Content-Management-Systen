<?php

use App\Http\Controllers\ItemController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\SettingController;
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
use App\Http\Controllers\NotificationController;

//public api endpoint
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [UserController::class, 'store']);
Route::get('/page', [PageController::class, 'publicViewIndex']);
Route::get('/menu/{menu:identifier}', [MenuController::class, 'view']);
Route::get('/page/{page:slug}', [PageController::class, 'viewBySlug']);
Route::get('/blog', [BlogController::class, 'publicViewIndex']);
Route::get('/blog/{blog:slug}', [BlogController::class, 'viewBySlug']);
Route::get('/categories', [CategoryController::class,'viewPublicActiveCategories']);
Route::get('/categories/{category}', [CategoryController::class,'viewBlogsForCategory']);

//protected end point
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class,'logout']);
    Route::get('/logged', [AuthController::class, 'loggedInUsers']);
    Route::post('/restore/{id}',[RecoverController::class,'restore']);
    Route::delete('/delete/{id}', [RecoverController::class,'destroy']);
    Route::get('/page/{page:slug}', [PageController::class, 'show']);
    Route::get('/author/{author:name}', [AuthorController::class, 'show']);

    Route::apiResource('category', CategoryController::class);
    Route::apiResource('image', ImageController::class);
    Route::apiResource('tag', TagController::class);
    Route::apiResource('blog', BlogController::class);
    Route::apiResource('author', AuthorController::class);
    Route::apiResource('page', PageController::class);
    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::get('/user/self', [UserController::class, 'showSelf']);
    Route::put('/user/{user}/admin', [UserController::class, 'makeUserAdmin']);
    Route::apiResource('user', UserController::class)->except(['store']);
    Route::apiResource('menu', MenuController::class);
    Route::apiResource('item', ItemController::class);
    Route::apiResource('setting', SettingController::class);
});
