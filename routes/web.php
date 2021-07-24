<?php

use App\Http\Controllers\BlogController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\HomePageController;
use App\Http\Controllers\Post\CommentController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
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

Route::get('/', [HomePageController::class, 'index'])->name('pages.home');

Route::prefix('blogs')->group(function (){
    Route::get('/', [BlogController::class, 'index'])->name('pages.blog');
    Route::get('/{postSlug}', [BlogController::class, 'index'])->name('pages.blog.post');
    Route::get('/categories/{categorySlug}', [BlogController::class, 'index'])->name('pages.blog.category');

    Route::post('/posts', [PostController::class, 'store'])->name('blog.post');
    Route::put('/posts/{post}', [PostController::class, 'update'])->name('blog.post.update');
    Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('blog.post.destroy');

    Route::get('/posts/me', [BlogController::class, 'index'])->name('blog.posts.me');
});

Route::name('post.')
    ->prefix('posts/{post}')
    ->group(function () {
        Route::resource('comments', CommentController::class, ['except' => 'index']);
    });

Route::prefix('manages')->group(function () {
    Route::resource('categories', CategoryController::class);
    Route::resource('posts', PostController::class);
    Route::resource('users', UserController::class);
});

Auth::routes();

