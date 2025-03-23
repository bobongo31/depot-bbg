<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CourrierController;
use App\Http\Controllers\AccuseDeReceptionController;
use App\Http\Controllers\CourrierRecuController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\ReponseController;
use App\Http\Controllers\ArchiveController;
use App\Http\Controllers\AnnexeController;
use App\Http\Controllers\PrintController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\UserController;


// Route par défaut
Route::get('/', function () {
    return view('home');
});

// Routes d'authentification
Auth::routes();


Route::view('/politique-utilisation', 'politique_utilisation');
Route::view('/politique-confidentialite', 'politique_confidentialite');
Route::view('/conditions-generales', 'conditions_generales');
Route::view('/mentions-legales', 'mentions_legales');
Route::view('/foire-questions', 'foire_questions');


// Route pour la page d'accueil après la connexion
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Routes pour le module des courriers (avec le middleware auth)
Route::middleware('auth')->group(function () {



    Route::get('/profile', [UserController::class, 'edit'])->name('profile.edit');
    
    // Route pour mettre à jour les informations de l'utilisateur
    Route::put('/profile', [UserController::class, 'update'])->name('profile.update');
    // Routes pour les accusés de réception
    Route::get('/accuse-de-reception', [AccuseDeReceptionController::class, 'showForm'])->name('accuse.form');
    Route::post('/accuse-de-reception', [AccuseDeReceptionController::class, 'store'])->name('accuse.store');

    // Routes pour les courriers reçus
    Route::get('/courriers/traites', [CourrierRecuController::class, 'indexTraite'])->name('courriers.traites');
    Route::put('/courriers/update/ajax', [CourrierRecuController::class, 'updateAjax'])->name('courriers.update.ajax');
    Route::post('/courriers/{id}/commentaire', [CourrierRecuController::class, 'addCommentaire'])->name('courriers.update.commentaire');
    Route::post('/courriers/{id}/statut', [CourrierRecuController::class, 'updateStatut'])->name('courriers.update.statut');
    Route::get('/courriers/create', [CourrierRecuController::class, 'create'])->name('courriers.create');
    Route::post('/courriers/store', [CourrierRecuController::class, 'store'])->name('courriers.store');

    // Routes pour les réponses
    Route::get('reponse/ajouter/{reponseId}', [ReponseController::class, 'ajouterReponseFinale'])->name('reponse.ajouter');
    Route::get('/reponses/create', [ReponseController::class, 'create'])->name('reponses.create');
    Route::get('/reponses', [ReponseController::class, 'index'])->name('reponses.index');
    Route::get('/reponse/{id}', [ReponseController::class, 'show'])->name('reponse.show');
    Route::post('/reponses', [ReponseController::class, 'store'])->name('reponses.store');
    Route::delete('/reponses/{id}', [ReponseController::class, 'destroy'])->name('reponses.destroy');

    // Routes pour les télégrammes
    Route::get('/telegrammes/create', [ReponseController::class, 'createTelegramme'])->name('telegramme.create');
    Route::post('/telegrammes/store', [ReponseController::class, 'storeTelegramme'])->name('telegramme.store');
    Route::get('/telegrammes', [ReponseController::class, 'index'])->name('telegrammes.index');
    Route::get('/telegramme/{id}', [ReponseController::class, 'showWithTelegramme'])->name('telegramme.show');
    Route::delete('/telegrammes/{id}', [ReponseController::class, 'destroyTelegramme'])->name('telegrammes.destroy');

    // Routes pour les archives
    Route::get('/archives/index', [ArchiveController::class, 'index'])->name('archives.index');
    Route::post('/archives/archiver/{numero_enregistrement}', [ArchiveController::class, 'archiverDossier'])->name('archives.archiver');
    Route::post('/archives/declarer-clos/{numero_enregistrement}', [ArchiveController::class, 'declarerClos'])->name('archives.declarer_clos');

    // Routes pour les annexes
    Route::get('/annexes/download/{id}', [AnnexeController::class, 'download'])->name('annexes.download');
    Route::get('/print-annexes', [PrintController::class, 'printAnnexes'])->name('annexes.print');

    // Routes pour la gestion des courriers (Accusé de réception)
    Route::get('/courriers/{courrier}/edit', [AccuseDeReceptionController::class, 'edit'])->name('courriers.edit');
    Route::delete('/courriers/{courrier}', [AccuseDeReceptionController::class, 'destroy'])->name('courriers.destroy');
    Route::put('/courriers/{id}', [AccuseDeReceptionController::class, 'update'])->name('courriers.update');
    Route::get('/courriers/{id}', [AccuseDeReceptionController::class, 'show'])->name('courriers.show');

    // Routes pour afficher les courriers reçus et les accusés de réception
    Route::get('/courriers', [CourrierRecuController::class, 'indexCourriers'])->name('courriers.index');
    Route::get('/accuses', [AccuseDeReceptionController::class, 'indexAccuses'])->name('accuses.index');
    
    // Route de recherche
    Route::get('/search', [SearchController::class, 'index'])->name('search');

    Route::get('messages/unreadCount', [MessageController::class, 'unreadCount'])->name('messages.unreadCount');
    Route::delete('messages/{message}/delete', [MessageController::class, 'destroy'])->name('messages.delete');
    Route::post('messages/transfer', [MessageController::class, 'transfer'])->name('messages.transfer');  // Changement ici
    Route::get('messages/{message}/annexes', [MessageController::class, 'getAnnexes'])->name('messages.getAnnexes');
    Route::get('messages/{userId}', [MessageController::class, 'show'])->name('messages.show');
    Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');
    Route::post('/messages', [MessageController::class, 'store'])->name('messages.store');
    Route::post('/messages/start', [MessageController::class, 'startConversation'])->name('messages.start');

});
