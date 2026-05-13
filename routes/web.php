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
Route::get('/', [HomeController::class, 'mostrarInicio'])->name('home');

// Rutas exclusivas a Builds
Route::get('/builds', [\App\Http\Controllers\BuildController::class, 'mostrarListaDeBuilds'])->name('builds.index');
Route::get('/builds/{build}', [\App\Http\Controllers\BuildController::class, 'mostrarBuild'])->name('builds.show')->where('build', '[0-9]+');

// Tierlist y Meta
Route::controller(GameDataController::class)->group(function () {
    Route::get('/tierlist', 'mostrarTierlist')->name('tierlist');
    Route::get('/meta', 'mostrarMeta')->name('meta');
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
    // Route::get('/sugerencias', 'mostrarFormularioSugerencias')->name('comunity.suggestions'); // Formulario antiguo eliminado, ahora es un modal
    Route::post('/sugerencias', 'guardarNuevaSugerencia')->name('comunity.suggestions.store');
});

// Información y Novedades
Route::get('/info-general', [WikiController::class, 'mostrarWiki'])->name('wiki.index');
Route::controller(InfoController::class)->group(function () {
    Route::get('/novedades', 'mostrarNovedades')->name('info.news');
});

// Autenticación y Perfil
Route::controller(UserController::class)->group(function () {
    Route::get('/login', 'mostrarFormularioLogin')->name('login');
    Route::post('/login', 'autenticarUsuario')->name('login.post')->middleware('throttle:5,1'); // ¡Rate Limit: 5 logins x minuto!
    Route::get('/registro', 'mostrarFormularioRegistro')->name('register');
    Route::post('/registro', 'registrarUsuario')->name('register.post')->middleware('throttle:10,1'); // ¡Rate Limit: 10 registros x minuto!
    Route::post('/logout', 'cerrarSesion')->name('logout');
});

// OAuth (Social Login)
Route::controller(\App\Http\Controllers\SocialController::class)->group(function () {
    Route::get('/auth/{provider}/redirect', 'redirigirAlProveedor')->name('social.redirect');
    Route::get('/auth/{provider}/callback', 'manejarCallbackDelProveedor')->name('social.callback');
});

// Rutas protegidas (Requieren sesión)
Route::middleware('auth')->group(function () {
    Route::controller(UserController::class)->group(function () {
        Route::get('/perfil', 'mostrarPerfil')->name('profile.old');
        Route::get('/cambiar-datos', 'mostrarAjustes')->name('profile.settings');
        Route::post('/cambiar-datos', 'actualizarAjustes')->name('profile.settings.update');
    });

    // Perfil Personal
    Route::controller(\App\Http\Controllers\ProfileController::class)->group(function () {
        Route::get('/mi-perfil', 'mostrarPerfil')->name('profile');
        Route::post('/mi-perfil/avatar', 'actualizarAvatar')->name('profile.avatar.update');
    });

    // Inventario Unificado
    Route::get('/inventario', [UnlockController::class, 'mostrarInventarioUnificado'])->name('inventory');

    // Builds
    Route::get('/builds/create', [App\Http\Controllers\BuildController::class, 'crearBuild'])->name('builds.create');
    Route::post('/builds', [App\Http\Controllers\BuildController::class, 'guardarBuild'])->name('builds.store');
    Route::get('/builds/{build}/edit', [App\Http\Controllers\BuildController::class, 'editarBuild'])->name('builds.edit');
    Route::put('/builds/{build}', [App\Http\Controllers\BuildController::class, 'actualizarBuild'])->name('builds.update');
    Route::post('/builds/{build}/vote', [App\Http\Controllers\BuildController::class, 'votarBuild'])->name('builds.vote');

    // Votes
    Route::post('/items/{id}/vote-rank', [App\Http\Controllers\GameDataController::class, 'votarRangoElemento'])->name('items.voteRank');
    Route::post('/meta-strategies/{id}/vote', [App\Http\Controllers\GameDataController::class, 'votarEstrategiaMeta'])->name('meta-strategies.vote');

    // Community Tier Lists
    Route::get('/community-tierlists/create', [App\Http\Controllers\UserTierListController::class, 'crearTierList'])->name('community-tierlists.create');
    Route::post('/community-tierlists', [App\Http\Controllers\UserTierListController::class, 'guardarTierList'])->name('community-tierlists.store');
    Route::get('/community-tierlists/{id}/edit', [App\Http\Controllers\UserTierListController::class, 'editarTierList'])->name('community-tierlists.edit');
    Route::put('/community-tierlists/{id}', [App\Http\Controllers\UserTierListController::class, 'actualizarTierList'])->name('community-tierlists.update');
    Route::post('/community-tierlists/{id}/comment', [App\Http\Controllers\CommentController::class, 'guardarComentario'])->name('community-tierlists.comment');
});

// Rutas de visualización pública de listadas de la comunidad (no requieren auth)
Route::get('/community-tierlists', [App\Http\Controllers\UserTierListController::class, 'mostrarIndiceDeTierLists'])->name('community-tierlists.index');
Route::get('/community-tierlists/{id}', [App\Http\Controllers\UserTierListController::class, 'mostrarTierListDetallada'])->name('community-tierlists.show')->where('id', '[0-9]+');

// Perfil Público
Route::get('/perfil/{id}', [App\Http\Controllers\ProfileController::class, 'mostrarPerfilPublico'])->name('profile.public')->where('id', '[0-9]+');

// Rutas de Administrador (Requieren sesión y ser admin)
Route::middleware(['auth', 'admin'])->name('admin.')->prefix('admin')->group(function () {
    Route::get('/', [AdminController::class, 'mostrarPanelAdministracion'])->name('dashboard');
    Route::get('/tierlist-manager', [AdminController::class, 'gestionarTierlist'])->name('tierlist-manager');
    Route::get('/items/create', [ItemController::class, 'mostrarFormularioCreacion'])->name('items.create');
    Route::post('/items', [ItemController::class, 'guardarItem'])->name('items.store');
    Route::post('/items/bulk-approve', [ItemController::class, 'aprobacionMasiva'])->name('items.bulkApprove');
    Route::post('/items/{id}/approve-rank', [ItemController::class, 'aprobarRango'])->name('items.approveRank');
    
    // Sugerencias de Tier
    Route::get('/tier-suggestions', [AdminController::class, 'gestionarTierSuggestions'])->name('tier-suggestions.index');
    Route::post('/tier-suggestions/{id}/approve', [AdminController::class, 'aprobarTierSuggestion'])->name('tier-suggestions.approve');
    Route::post('/tier-suggestions/{id}/reject', [AdminController::class, 'rechazarTierSuggestion'])->name('tier-suggestions.reject');
    Route::post('/tier-suggestions/{id}/ban', [AdminController::class, 'banearTierSuggestion'])->name('tier-suggestions.ban');
    Route::post('/tier-suggestions/item/{itemId}/approve-majority', [AdminController::class, 'aprobarMayoria'])->name('tier-suggestions.approveMajority');
    Route::post('/meta/reset', [AdminController::class, 'resetMeta'])->name('meta.reset');

    // Moderación Antigua
    Route::get('/community-tierlists', [AdminController::class, 'gestionarTierListsComunidad'])->name('community-tierlists.index');
    Route::delete('/community-tierlists/{id}', [App\Http\Controllers\UserTierListController::class, 'eliminarTierListAdmin'])->name('community-tierlists.destroy');
    Route::delete('/comments/{id}', [App\Http\Controllers\CommentController::class, 'eliminarComentarioAdmin'])->name('comments.destroy');

    // Nueva Moderación (Builds y Tierlists)
    Route::get('/moderation', [\App\Http\Controllers\Admin\ModerationController::class, 'mostrarPanelModeracion'])->name('moderation.index');
    Route::delete('/moderation/builds/{id}', [\App\Http\Controllers\Admin\ModerationController::class, 'eliminarBuild'])->name('moderation.builds.destroy');
    Route::get('/moderation/builds/{build}/edit', [\App\Http\Controllers\Admin\ModerationController::class, 'editarBuild'])->name('moderation.builds.edit');
    Route::put('/moderation/builds/{build}', [\App\Http\Controllers\Admin\ModerationController::class, 'actualizarBuild'])->name('moderation.builds.update');
    Route::delete('/moderation/tierlists/{id}', [\App\Http\Controllers\Admin\ModerationController::class, 'eliminarTierList'])->name('moderation.tierlists.destroy');

    // Gestión del Meta
    Route::get('/meta', [\App\Http\Controllers\Admin\MetaAdminController::class, 'mostrarMetaAdmin'])->name('meta.index');
    Route::post('/meta/strategies', [\App\Http\Controllers\Admin\MetaAdminController::class, 'guardarEstrategia'])->name('meta.strategies.store');
    Route::put('/meta/strategies/{id}', [\App\Http\Controllers\Admin\MetaAdminController::class, 'actualizarEstrategia'])->name('meta.strategies.update');
    Route::delete('/meta/strategies/{id}', [\App\Http\Controllers\Admin\MetaAdminController::class, 'eliminarEstrategia'])->name('meta.strategies.destroy');
    Route::post('/meta/patch-notes', [\App\Http\Controllers\Admin\MetaAdminController::class, 'guardarNotaParche'])->name('meta.patch_notes.store');
    Route::put('/meta/patch-notes/{id}', [\App\Http\Controllers\Admin\MetaAdminController::class, 'actualizarNotaParche'])->name('meta.patch_notes.update');
    Route::delete('/meta/patch-notes/{id}', [\App\Http\Controllers\Admin\MetaAdminController::class, 'eliminarNotaParche'])->name('meta.patch_notes.destroy');

    // Leaderboard Admin
    Route::get('/leaderboard', [AdminController::class, 'gestionarLeaderboard'])->name('leaderboard.index');
    Route::post('/leaderboard/{id}/approve', [AdminController::class, 'aprobarPuntuacion'])->name('leaderboard.approve');
    Route::post('/leaderboard/{id}/reject', [AdminController::class, 'rechazarPuntuacion'])->name('leaderboard.reject');
    Route::post('/leaderboard/reset-global', [AdminController::class, 'reiniciarLeaderboardGlobal'])->name('leaderboard.resetGlobal');
    Route::delete('/leaderboard/{id}/reset', [AdminController::class, 'reiniciarPuntuacionUsuario'])->name('leaderboard.resetUser');

    // Wiki Admin
    Route::get('/wiki', [WikiAdminController::class, 'mostrarPanelWiki'])->name('wiki.index');
    Route::post('/wiki/game-infos', [WikiAdminController::class, 'guardarInformacionJuego'])->name('wiki.game_infos.store');
    Route::put('/wiki/game-infos/{id}', [WikiAdminController::class, 'actualizarInformacionJuego'])->name('wiki.game_infos.update');
    Route::delete('/wiki/game-infos/{id}', [WikiAdminController::class, 'eliminarInformacionJuego'])->name('wiki.game_infos.destroy');
    
    Route::post('/wiki/faqs', [WikiAdminController::class, 'guardarPreguntaFrecuente'])->name('wiki.faqs.store');
    Route::put('/wiki/faqs/{id}', [WikiAdminController::class, 'actualizarPreguntaFrecuente'])->name('wiki.faqs.update');
    Route::delete('/wiki/faqs/{id}', [WikiAdminController::class, 'eliminarPreguntaFrecuente'])->name('wiki.faqs.destroy');

    // Sugerencias
    Route::get('/suggestions', [\App\Http\Controllers\Admin\SuggestionController::class, 'mostrarSugerencias'])->name('suggestions.index');
    Route::post('/suggestions/{id}/mark-read', [\App\Http\Controllers\Admin\SuggestionController::class, 'marcarComoLeida'])->name('suggestions.markRead');
    Route::post('/suggestions/{id}/status', [\App\Http\Controllers\Admin\SuggestionController::class, 'actualizarEstadoSugerencia'])->name('suggestions.updateStatus');
    Route::delete('/suggestions/{id}', [\App\Http\Controllers\Admin\SuggestionController::class, 'eliminarSugerencia'])->name('suggestions.destroy');

    // Gestión de Usuarios
    Route::get('/users', [\App\Http\Controllers\Admin\UserAdminController::class, 'mostrarUsuarios'])->name('users.index');
    Route::post('/users/{id}/ban', [\App\Http\Controllers\Admin\UserAdminController::class, 'gestionarBaneo'])->name('users.ban');
    Route::delete('/users/{id}', [\App\Http\Controllers\Admin\UserAdminController::class, 'eliminarUsuario'])->name('users.destroy');
});