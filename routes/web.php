<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\GameDataController;
use App\Http\Controllers\UnlockController;
use App\Http\Controllers\CommunityController;
use App\Http\Controllers\InfoController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ItemController;

/*
|--------------------------------------------------------------------------
| Web Routes - MEGABONK GUIDE
|--------------------------------------------------------------------------
|
| Aquí es donde puedes registrar las rutas web para tu aplicación.
|
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
    Route::post('/unlocks/toggle', 'toggleUnlock')->name('unlocks.toggle')->middleware('auth');
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
    Route::post('/login', 'authenticate')->name('login.post')->middleware('throttle:5,1'); // ¡Rate Limit: 5 logins x minuto!
    Route::get('/registro', 'register')->name('register');
    Route::post('/registro', 'store')->name('register.post')->middleware('throttle:10,1'); // ¡Rate Limit: 10 registros x minuto!
    Route::post('/logout', 'logout')->name('logout');
});

// Rutas protegidas (Requieren sesión)
Route::middleware('auth')->group(function () {
    Route::controller(UserController::class)->group(function () {
        Route::get('/perfil', 'profile')->name('profile');
        Route::get('/cambiar-datos', 'settings')->name('profile.settings');
    });

    // Inventario Unificado
    Route::get('/inventario', [UnlockController::class, 'inventory'])->name('inventory');

    // Builds
    Route::post('/builds', [App\Http\Controllers\BuildController::class, 'store'])->name('builds.store');
});

// Rutas de Administrador (Requieren sesión y ser admin)
Route::middleware(['auth', 'admin'])->name('admin.')->prefix('admin')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('dashboard');
    Route::get('/items/create', [ItemController::class, 'create'])->name('items.create');
    Route::post('/items', [ItemController::class, 'store'])->name('items.store');
});