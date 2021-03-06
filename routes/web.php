<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ImagesController;
use App\Http\Controllers\CardsController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\LearnController;
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

// Auth

Route::get('login', [AuthenticatedSessionController::class, 'create'])
    ->name('login')
    ->middleware('guest');

Route::post('login', [AuthenticatedSessionController::class, 'store'])
    ->name('login.store')
    ->middleware('guest');

Route::delete('logout', [AuthenticatedSessionController::class, 'destroy'])
    ->name('logout');

// Dashboard


Route::get('/dashboard', [DashboardController::class, 'index'])
    ->name('dashboard')
    ->middleware('auth');


// Learn

Route::get('/', [LearnController::class, 'indexguest'])
    ->name('learnguest')
    ->middleware('guest');

Route::get('learn', [LearnController::class, 'index'])
    ->name('learn')
    ->middleware('auth');

// Users

Route::get('users', [UsersController::class, 'index'])
    ->name('users')
    ->middleware('auth');

Route::get('users/create', [UsersController::class, 'create'])
    ->name('users.create')
    ->middleware('auth');

Route::post('users', [UsersController::class, 'store'])
    ->name('users.store')
    ->middleware('auth');

Route::get('users/{user}/edit', [UsersController::class, 'edit'])
    ->name('users.edit')
    ->middleware('auth');

Route::put('users/{user}', [UsersController::class, 'update'])
    ->name('users.update')
    ->middleware('auth');

Route::delete('users/{user}', [UsersController::class, 'destroy'])
    ->name('users.destroy')
    ->middleware('auth');

Route::put('users/{user}/restore', [UsersController::class, 'restore'])
    ->name('users.restore')
    ->middleware('auth');

// Cards

Route::get('cards', [CardsController::class, 'index'])
    ->name('cards')
    ->middleware('auth');

Route::get('cards/create', [CardsController::class, 'create'])
    ->name('cards.create')
    ->middleware('auth');

Route::post('cards', [CardsController::class, 'store'])
    ->name('cards.store')
    ->middleware('auth');

Route::get('cards/{card}/edit', [CardsController::class, 'edit'])
    ->name('cards.edit')
    ->middleware('auth');

Route::post('cards/{card}', [CardsController::class, 'update'])
    ->name('cards.update')
    ->middleware('auth');

Route::delete('cards/{card}', [CardsController::class, 'destroy'])
    ->name('cards.destroy')
    ->middleware('auth');

Route::put('cards/{card}/restore', [CardsController::class, 'restore'])
    ->name('cards.restore')
    ->middleware('auth');

Route::delete('cards/{card}/delete/{front}', [CardsController::class, 'deleteimg'])
    ->name('cards.deleteimg');

// Images

Route::get('/img/{path}', [ImagesController::class, 'show'])
    ->where('path', '.*')
    ->name('image');
