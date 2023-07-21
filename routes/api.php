<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MovieActionController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/login', [LoginController::class, 'login'])->name('login');
Route::post('/register', [RegisterController::class, 'register'])->name('register');


Route::middleware('auth:api')->group(function () {
    Route::get('/user', [ProfileController::class, 'show'])->name('user.show');
    Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');

    Route::post('/movies', [MovieController::class, 'store'])->name('movies.store');
    Route::put('/movies/{movie}', [MovieController::class, 'update'])->name('movies.update');
    Route::delete('/movies/{movie}', [MovieController::class, 'destroy'])->name('movies.destroy');

    Route::get('/movies/favorites', [MovieController::class, 'showFavorites'])->name('movies.favorites');
    Route::post('/movies/{movie}/favorite', [MovieActionController::class, 'favorite'])->name('movies.favorite');
    Route::post('/movies/{movie}/unfavorite', [MovieActionController::class, 'unfavorite'])->name('movies.unfavorite');

    Route::get('/movies/followed', [MovieController::class, 'showFollowedMovies'])->name('movies.followed');
    Route::post('/movies/{movie}/follow', [MovieActionController::class, 'follow'])->name('movies.follow');
    Route::post('/movies/{movie}/unfollow', [MovieActionController::class, 'unfollow'])->name('movies.unfollow');
});


Route::get('/movies', [MovieController::class, 'index'])->name('movies.index');
Route::get('/movies/{movie}', [MovieController::class, 'show'])->name('movies.show');