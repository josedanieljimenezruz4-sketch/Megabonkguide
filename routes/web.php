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

// Rutas exclusivas a Builds
Route::get('/builds', [\App\Http\Controllers\BuildController::class, 'index'])->name('builds.index');
Route::get('/builds/{build}', [\App\Http\Controllers\BuildController::class, 'show'])->name('builds.show')->where('build', '[0-9]+');

// Tierlist y Meta
Route::controller(GameDataController::class)->group(function () {
    Route::get('/tierlist', 'tierlist')->name('tierlist');
    Route::get('/meta', 'meta')->name('meta');
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
    Route::get('/builds/create', [App\Http\Controllers\BuildController::class, 'create'])->name('builds.create');
    Route::post('/builds', [App\Http\Controllers\BuildController::class, 'store'])->name('builds.store');
    Route::post('/builds/{build}/vote', [App\Http\Controllers\BuildController::class, 'vote'])->name('builds.vote');

    // Votes
    Route::post('/items/{id}/vote', [App\Http\Controllers\GameDataController::class, 'voteItem'])->name('items.vote');
    Route::post('/items/{id}/vote-rank', [App\Http\Controllers\GameDataController::class, 'voteRankItem'])->name('items.voteRank');
});

// Rutas de Administrador (Requieren sesión y ser admin)
Route::middleware(['auth', 'admin'])->name('admin.')->prefix('admin')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('dashboard');
    Route::get('/tierlist-manager', [AdminController::class, 'tierlistManager'])->name('tierlist-manager');
    Route::get('/items/create', [ItemController::class, 'create'])->name('items.create');
    Route::post('/items', [ItemController::class, 'store'])->name('items.store');
    Route::post('/items/bulk-approve', [ItemController::class, 'bulkApprove'])->name('items.bulkApprove');
    Route::post('/items/{id}/approve-rank', [ItemController::class, 'approveRank'])->name('items.approveRank');
    
    // Gestión de votos
    Route::get('/votes', [AdminController::class, 'votes'])->name('votes.index');
    Route::post('/votes/reset-all', [AdminController::class, 'resetAllVotes'])->name('votes.resetAll');
    Route::post('/votes/{id}/reset', [AdminController::class, 'resetItemVotes'])->name('votes.resetItem');
});