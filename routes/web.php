<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\GameDataController;
use App\Http\Controllers\UnlockController;
use App\Http\Controllers\CommunityController;
use App\Http\Controllers\InfoController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes - MEGABONK GUIDE
|--------------------------------------------------------------------------
*/

// Página de Inicio
Route::get('/', [HomeController::class, 'index'])->name('home');

// Tierlist y Meta
Route::controller(GameDataController::class)->group(function () {
    Route::get('/tierlist', 'tierlist')->name('tierlist');
    Route::get('/meta', 'meta')->name('meta');
    Route::get('/buscador-builds', 'builds')->name('builds.search');
    Route::get('/leaderboard', 'leaderboard')->name('leaderboard');
});

// Sección UNLOCKS
Route::controller(UnlockController::class)->group(function () {
    Route::get('/unlocks', 'index')->name('unlocks.index');
    Route::get('/armas', 'weapons')->name('unlocks.weapons');
    Route::get('/tomos', 'tomes')->name('unlocks.tomes');
    Route::get('/items', 'items')->name('unlocks.items');
    Route::get('/personajes', 'characters')->name('unlocks.characters');
});

// Comunidad
Route::controller(CommunityController::class)->group(function () {
    Route::get('/comunity', 'index')->name('comunity.index');
    Route::get('/sugerencias', 'suggestions')->name('comunity.suggestions');
});

// Información y Novedades
Route::controller(InfoController::class)->group(function () {
    Route::get('/info-general', 'general')->name('info.general');
    Route::get('/novedades', 'news')->name('info.news');
});

// Autenticación y Perfil
Route::controller(UserController::class)->group(function () {
    Route::get('/login', 'login')->name('login');
    Route::post('/login', 'authenticate')->name('login.post');
    Route::get('/registro', 'register')->name('register');
    Route::post('/registro', 'store')->name('register.post');
    Route::post('/logout', 'logout')->name('logout');
    Route::get('/perfil', 'profile')->name('profile')->middleware('auth');
    Route::get('/cambiar-datos', 'settings')->name('profile.settings')->middleware('auth');
});