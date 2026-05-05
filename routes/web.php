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
use App\Http\Controllers\WikiController;
use App\Http\Controllers\Admin\WikiAdminController;

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
});

// Leaderboard
Route::controller(\App\Http\Controllers\LeaderboardController::class)->group(function () {
    Route::get('/leaderboard', 'mostrarTablaDeClasificacion')->name('leaderboard');
    Route::post('/leaderboard', 'guardarNuevaPuntuacion')->name('leaderboard.store')->middleware('auth');
});

// Sección UNLOCKS
Route::controller(UnlockController::class)->group(function () {
    Route::get('/unlocks', 'mostrarIndiceUnlocks')->name('unlocks.index');
    Route::get('/armas', 'mostrarArmas')->name('unlocks.weapons');
    Route::get('/tomos', 'mostrarTomos')->name('unlocks.tomes');
    Route::get('/items', 'mostrarObjetos')->name('unlocks.items');
    Route::get('/personajes', 'mostrarPersonajes')->name('unlocks.characters');
    Route::post('/unlocks/toggle', 'alternarEstadoDesbloqueo')->name('unlocks.toggle')->middleware('auth');
});

// Comunidad
Route::controller(CommunityController::class)->group(function () {
    Route::get('/community', 'mostrarListaDePublicaciones')->name('comunity.index');
    Route::post('/community', 'guardarNuevaPublicacion')->name('comunity.store')->middleware('auth');
    Route::get('/community/{id}', 'mostrarPublicacionDetallada')->name('comunity.show')->where('id', '[0-9]+');
    Route::post('/community/{id}/like', 'alternarMeGusta')->name('comunity.like')->middleware('auth');
    Route::post('/community/{id}/comment', 'guardarNuevoComentario')->name('comunity.comment')->middleware('auth');
    Route::get('/sugerencias', 'mostrarFormularioSugerencias')->name('comunity.suggestions');
    Route::post('/sugerencias', 'guardarNuevaSugerencia')->name('comunity.suggestions.store');
});

// Información y Novedades
Route::get('/info-general', [WikiController::class, 'index'])->name('wiki.index');
Route::controller(InfoController::class)->group(function () {
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

// OAuth (Social Login)
Route::controller(\App\Http\Controllers\SocialController::class)->group(function () {
    Route::get('/auth/{provider}/redirect', 'redirectToProvider')->name('social.redirect');
    Route::get('/auth/{provider}/callback', 'handleProviderCallback')->name('social.callback');
});

// Rutas protegidas (Requieren sesión)
Route::middleware('auth')->group(function () {
    Route::controller(UserController::class)->group(function () {
        Route::get('/perfil', 'profile')->name('profile.old');
        Route::get('/cambiar-datos', 'settings')->name('profile.settings');
    });

    // Perfil Personal
    Route::controller(\App\Http\Controllers\ProfileController::class)->group(function () {
        Route::get('/mi-perfil', 'index')->name('profile');
        Route::post('/mi-perfil/avatar', 'updateAvatar')->name('profile.avatar.update');
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
    Route::post('/meta-strategies/{id}/vote', [App\Http\Controllers\GameDataController::class, 'voteMetaStrategy'])->name('meta-strategies.vote');

    // Community Tier Lists
    Route::get('/community-tierlists/create', [App\Http\Controllers\UserTierListController::class, 'create'])->name('community-tierlists.create');
    Route::post('/community-tierlists', [App\Http\Controllers\UserTierListController::class, 'store'])->name('community-tierlists.store');
    Route::post('/community-tierlists/{id}/comment', [App\Http\Controllers\CommentController::class, 'store'])->name('community-tierlists.comment');
});

// Rutas de visualización pública de listadas de la comunidad (no requieren auth)
Route::get('/community-tierlists', [App\Http\Controllers\UserTierListController::class, 'index'])->name('community-tierlists.index');
Route::get('/community-tierlists/{id}', [App\Http\Controllers\UserTierListController::class, 'show'])->name('community-tierlists.show')->where('id', '[0-9]+');

// Perfil Público
Route::get('/perfil/{id}', [App\Http\Controllers\ProfileController::class, 'showPublic'])->name('profile.public')->where('id', '[0-9]+');

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

    // Moderación Antigua
    Route::get('/community-tierlists', [AdminController::class, 'communityTierLists'])->name('community-tierlists.index');
    Route::delete('/community-tierlists/{id}', [App\Http\Controllers\UserTierListController::class, 'destroyAdmin'])->name('community-tierlists.destroy');
    Route::delete('/comments/{id}', [App\Http\Controllers\CommentController::class, 'destroyAdmin'])->name('comments.destroy');

    // Nueva Moderación (Builds y Tierlists)
    Route::get('/moderation', [\App\Http\Controllers\Admin\ModerationController::class, 'index'])->name('moderation.index');
    Route::delete('/moderation/builds/{id}', [\App\Http\Controllers\Admin\ModerationController::class, 'destroyBuild'])->name('moderation.builds.destroy');
    Route::get('/moderation/builds/{build}/edit', [\App\Http\Controllers\Admin\ModerationController::class, 'editBuild'])->name('moderation.builds.edit');
    Route::put('/moderation/builds/{build}', [\App\Http\Controllers\Admin\ModerationController::class, 'updateBuild'])->name('moderation.builds.update');
    Route::delete('/moderation/tierlists/{id}', [\App\Http\Controllers\Admin\ModerationController::class, 'destroyTierList'])->name('moderation.tierlists.destroy');

    // Gestión del Meta
    Route::get('/meta', [\App\Http\Controllers\Admin\MetaAdminController::class, 'index'])->name('meta.index');
    Route::post('/meta/strategies', [\App\Http\Controllers\Admin\MetaAdminController::class, 'storeStrategy'])->name('meta.strategies.store');
    Route::put('/meta/strategies/{id}', [\App\Http\Controllers\Admin\MetaAdminController::class, 'updateStrategy'])->name('meta.strategies.update');
    Route::delete('/meta/strategies/{id}', [\App\Http\Controllers\Admin\MetaAdminController::class, 'destroyStrategy'])->name('meta.strategies.destroy');
    Route::post('/meta/patch-notes', [\App\Http\Controllers\Admin\MetaAdminController::class, 'storePatchNote'])->name('meta.patch_notes.store');
    Route::put('/meta/patch-notes/{id}', [\App\Http\Controllers\Admin\MetaAdminController::class, 'updatePatchNote'])->name('meta.patch_notes.update');
    Route::delete('/meta/patch-notes/{id}', [\App\Http\Controllers\Admin\MetaAdminController::class, 'destroyPatchNote'])->name('meta.patch_notes.destroy');

    // Leaderboard Admin
    Route::get('/leaderboard', [AdminController::class, 'leaderboard'])->name('leaderboard.index');
    Route::post('/leaderboard/{id}/approve', [AdminController::class, 'approveScore'])->name('leaderboard.approve');
    Route::post('/leaderboard/{id}/reject', [AdminController::class, 'rejectScore'])->name('leaderboard.reject');
    Route::post('/leaderboard/reset-global', [AdminController::class, 'resetGlobalLeaderboard'])->name('leaderboard.resetGlobal');
    Route::delete('/leaderboard/{id}/reset', [AdminController::class, 'resetUserScore'])->name('leaderboard.resetUser');

    // Wiki Admin
    Route::get('/wiki', [WikiAdminController::class, 'index'])->name('wiki.index');
    Route::post('/wiki/game-infos', [WikiAdminController::class, 'storeGameInfo'])->name('wiki.game_infos.store');
    Route::put('/wiki/game-infos/{id}', [WikiAdminController::class, 'updateGameInfo'])->name('wiki.game_infos.update');
    Route::delete('/wiki/game-infos/{id}', [WikiAdminController::class, 'destroyGameInfo'])->name('wiki.game_infos.destroy');
    
    Route::post('/wiki/faqs', [WikiAdminController::class, 'storeFaq'])->name('wiki.faqs.store');
    Route::put('/wiki/faqs/{id}', [WikiAdminController::class, 'updateFaq'])->name('wiki.faqs.update');
    Route::delete('/wiki/faqs/{id}', [WikiAdminController::class, 'destroyFaq'])->name('wiki.faqs.destroy');

    // Sugerencias
    Route::get('/suggestions', [\App\Http\Controllers\Admin\SuggestionController::class, 'index'])->name('suggestions.index');
    Route::post('/suggestions/{id}/mark-read', [\App\Http\Controllers\Admin\SuggestionController::class, 'markRead'])->name('suggestions.markRead');
    Route::post('/suggestions/{id}/status', [\App\Http\Controllers\Admin\SuggestionController::class, 'updateStatus'])->name('suggestions.updateStatus');
    Route::delete('/suggestions/{id}', [\App\Http\Controllers\Admin\SuggestionController::class, 'destroy'])->name('suggestions.destroy');

    // Gestión de Usuarios
    Route::get('/users', [\App\Http\Controllers\Admin\UserAdminController::class, 'index'])->name('users.index');
    Route::post('/users/{id}/ban', [\App\Http\Controllers\Admin\UserAdminController::class, 'ban'])->name('users.ban');
    Route::delete('/users/{id}', [\App\Http\Controllers\Admin\UserAdminController::class, 'destroy'])->name('users.destroy');
});