<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/',[App\Http\Controllers\PostsController::class,'index']);

Route::post('/addPost',[App\Http\Controllers\PostsController::class,'store']);

Route::post('edit/{id}',[App\Http\Controllers\PostsController::class,'edit']);

Route::get('post/{id}/delete',[App\Http\Controllers\PostsController::class,'destroy']);

Route::get('/posts/{id}/editPost',[App\Http\Controllers\PostsController::class,'getPostId']);

Route::post('/likes', [App\Http\Controllers\LikesController::class,'LikePost']);

Route::post('/getLikes',[App\Http\Controllers\PostsController::class,'getLikes']);

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// automatically maps the routes to the controller
Route::resource('posts','App\Http\Controllers\PostsController');
