<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CourrierController;
use App\Http\Controllers\AccuseDeReceptionController;
use App\Http\Controllers\CourrierRecuController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\ReponseController;


// Route par défaut
Route::get('/', function () {
    return view('home');
});

// Routes d'authentification
Auth::routes();

// Route pour la page d'accueil après la connexion
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


// Routes pour le module des courriers (avec le middleware auth)
Route::middleware('auth')->group(function () {
    Route::get('/accuse-de-reception', [AccuseDeReceptionController::class, 'showForm'])->name('accuse.form');
    Route::post('/accuse-de-reception', [AccuseDeReceptionController::class, 'store'])->name('accuse.store');
    Route::get('/courriers/traites', [CourrierRecuController::class, 'indexTraite'])->name('courriers.traites');
    Route::put('/courriers/update/ajax', [CourrierRecuController::class, 'updateAjax'])->name('courriers.update.ajax');
    Route::post('/courriers/{id}/commentaire', [CourrierRecuController::class, 'addCommentaire'])->name('courriers.update.commentaire');
    Route::post('/courriers/{id}/statut', [CourrierRecuController::class, 'updateStatut'])->name('courriers.update.statut');
    Route::get('/reponses/create', [ReponseController::class, 'create'])->name('reponses.create');
    Route::get('/reponses', [ReponseController::class, 'index'])->name('reponses.index');
    Route::get('/reponses/{id}', [ReponseController::class, 'show'])->name('reponses.show');
    Route::post('/reponses', [ReponseController::class, 'store'])->name('reponses.store');
    Route::delete('/reponses/{id}', [ReponseController::class, 'destroy'])->name('reponses.destroy');
    Route::get('/telegrammes/create', [ReponseController::class, 'createTelegramme'])->name('telegramme.create');
    Route::post('/telegrammes/store', [ReponseController::class, 'storeTelegramme'])->name('telegramme.store');
    Route::get('telegrammes/{id}', [ReponseController::class, 'show'])->name('telegrammes.show');
    Route::delete('/telegrammes/{id}', [ReponseController::class, 'destroy'])->name('telegrammes.destroy');


    
    //Route::get('/courrier/create', [CourrierController::class, 'create'])->name('courrier.create');
    //Route::post('/courrier/store', [CourrierController::class, 'store'])->name('courrier.store');
    //Route::get('/courriers', [CourrierController::class, 'index'])->name('courrier.index');
    //Route::get('/courrier/{id}', [CourrierController::class, 'show'])->name('courrier.show');
    //Route::post('/courrier/validate/{id}', [CourrierController::class, 'validateCourrier'])->name('courrier.validate');
    //Route::post('/courrier/transmit/{id}', [CourrierController::class, 'transmitToDirector'])->name('courrier.transmit');
    //Route::get('/validation-history', [CourrierController::class, 'validationHistory'])->name('courrier.validationHistory');
    Route::get('/courriers/create', [CourrierRecuController::class, 'create'])->name('courriers.create');
    Route::post('/courriers/store', [CourrierRecuController::class, 'store'])->name('courriers.store');
        // Route pour la modification
    Route::get('/courriers/{courrier}/edit', [AccuseDeReceptionController::class, 'edit'])->name('courriers.edit');

    // Route pour la suppression
    Route::delete('/courriers/{courrier}', [AccuseDeReceptionController::class, 'destroy'])->name('courriers.destroy');
    Route::put('courriers/{id}', [AccuseDeReceptionController::class, 'update'])->name('courriers.update');
    Route::get('/courriers/{id}', [AccuseDeReceptionController::class, 'show'])->name('courriers.show');

        // Routes pour afficher les courriers reçus et les accusés de réception
    Route::get('/courriers', [CourrierRecuController::class, 'indexCourriers'])->name('courriers.index');
    Route::get('/accuses', [AccuseDeReceptionController::class, 'indexAccuses'])->name('accuses.index');
    Route::get('/search', [SearchController::class, 'index'])->name('search');
});
