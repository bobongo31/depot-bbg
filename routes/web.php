<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WelcomeController;
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
use App\Http\Controllers\Auth\FpcLoginController;
use App\Http\Controllers\Auth\EntrepriseXLoginController;
use App\Http\Controllers\CodeAccesController;
use App\Http\Controllers\DossierPersonnelController;
use App\Http\Controllers\DemandeCongeController;
use App\Http\Controllers\FondsDemandeController;
use App\Http\Controllers\DepenseCaisseController;
use App\Http\Controllers\RapportCaisseController;
use App\Http\Controllers\UtilisateurController;


// ✅ Auth routes (connexion / inscription)
Auth::routes();

// Afficher la page d'accueil sur '/'
Route::get('/', [WelcomeController::class, 'index'])->name('welcome');


// Après connexion, rediriger vers 'home'
Route::get('/home', [HomeController::class, 'index'])->name('home');


// ✅ Routes accessibles sans authentification ni code d’accès
Route::get('/code-acces', [CodeAccesController::class, 'afficherFormulaire'])->name('code.form');
Route::post('/code-acces', [CodeAccesController::class, 'verifierCode'])->name('code.verifier');

Route::view('/politique-utilisation', 'politique_utilisation');
Route::view('/politique-confidentialite', 'politique_confidentialite');
Route::view('/conditions-generales', 'conditions_generales');
Route::view('/mentions-legales', 'mentions_legales');
Route::view('/foire-questions', 'foire_questions');
Route::get('/inscription', [UtilisateurController::class, 'create'])->name('utilisateur.create');
Route::post('/inscription', [UtilisateurController::class, 'store'])->name('utilisateur.store');

// ✅ Toutes les routes protégées → middleware auth + code.acces
Route::middleware(['auth'
])->group(function () {
    // ✅ Route d'accueil
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    // ✅ Profil utilisateur
    Route::get('/profile', [UserController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [UserController::class, 'update'])->name('profile.update');

    // ✅ Accusés de réception
    Route::get('/accuse-de-reception', [AccuseDeReceptionController::class, 'showForm'])->name('accuse.form');
    Route::post('/accuse-de-reception', [AccuseDeReceptionController::class, 'store'])->name('accuse.store');

    // ✅ Courriers reçus
    Route::get('/courriers/traites', [CourrierRecuController::class, 'indexTraite'])->name('courriers.traites');
    Route::put('/courriers/update/ajax', [CourrierRecuController::class, 'updateAjax'])->name('courriers.update.ajax');
    Route::post('/courriers/{id}/commentaire', [CourrierRecuController::class, 'addCommentaire'])->name('courriers.update.commentaire');
    Route::post('/courriers/{id}/statut', [CourrierRecuController::class, 'updateStatut'])->name('courriers.update.statut');
    Route::get('/courriers/create', [CourrierRecuController::class, 'create'])->name('courriers.create');
    Route::post('/courriers/store', [CourrierRecuController::class, 'store'])->name('courriers.store');

    // ✅ Réponses
    Route::get('reponse/ajouter/{reponseId}', [ReponseController::class, 'ajouterReponseFinale'])->name('reponse.ajouter');
    Route::get('/reponses/create', [ReponseController::class, 'create'])->name('reponses.create');
    Route::get('/reponses', [ReponseController::class, 'index'])->name('reponses.index');
    Route::get('/reponse/{id}', [ReponseController::class, 'show'])->name('reponse.show');
    Route::post('/reponses', [ReponseController::class, 'store'])->name('reponses.store');
    Route::delete('/reponses/{id}', [ReponseController::class, 'destroy'])->name('reponses.destroy');

    // ✅ Télégrammes
    Route::get('/telegrammes/create', [ReponseController::class, 'createTelegramme'])->name('telegramme.create');
    Route::post('/telegrammes/store', [ReponseController::class, 'storeTelegramme'])->name('telegramme.store');
    Route::get('/telegrammes', [ReponseController::class, 'index'])->name('telegrammes.index');
    Route::get('/telegramme/{id}', [ReponseController::class, 'showWithTelegramme'])->name('telegramme.show');
    Route::delete('/telegrammes/{id}', [ReponseController::class, 'destroyTelegramme'])->name('telegrammes.destroy');

    // ✅ Archives
    Route::get('/archives/index', [ArchiveController::class, 'index'])->name('archives.index');
    Route::post('/archives/archiver/{numero_enregistrement}', [ArchiveController::class, 'archiverDossier'])->name('archives.archiver');
    Route::post('/archives/declarer-clos/{numero_enregistrement}', [ArchiveController::class, 'declarerClos'])->name('archives.declarer_clos');

    // ✅ Annexes et impressions
    Route::get('/annexes/download/{id}', [AnnexeController::class, 'download'])->name('annexes.download');
    Route::get('/print-annexes', [PrintController::class, 'printAnnexes'])->name('annexes.print');

    // ✅ Gestion des courriers
    Route::get('/courriers/{courrier}/edit', [AccuseDeReceptionController::class, 'edit'])->name('courriers.edit');
    Route::delete('/courriers/{courrier}', [AccuseDeReceptionController::class, 'destroy'])->name('courriers.destroy');
    Route::put('/courriers/{id}', [AccuseDeReceptionController::class, 'update'])->name('courriers.update');
    Route::get('/courriers/{id}', [AccuseDeReceptionController::class, 'show'])->name('courriers.show');
    Route::get('/courriers', [CourrierRecuController::class, 'indexCourriers'])->name('courriers.index');
    Route::get('/accuses', [AccuseDeReceptionController::class, 'indexAccuses'])->name('accuses.index');

    // ✅ Recherche
    Route::get('/search', [SearchController::class, 'index'])->name('search');

    // ✅ Messagerie
    Route::get('messages/unreadCount', [MessageController::class, 'unreadCount'])->name('messages.unreadCount');
    Route::delete('messages/{message}/delete', [MessageController::class, 'destroy'])->name('messages.delete');
    Route::post('messages/transfer', [MessageController::class, 'transfer'])->name('messages.transfer');
    Route::get('messages/{message}/annexes', [MessageController::class, 'getAnnexes'])->name('messages.getAnnexes');
    Route::get('messages/{userId}', [MessageController::class, 'show'])->name('messages.show');
    Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');
    Route::post('/messages', [MessageController::class, 'store'])->name('messages.store');
    Route::post('/messages/start', [MessageController::class, 'startConversation'])->name('messages.start');

    // ✅ Test multi-tenants
    Route::get('/test-tenant', [\App\Http\Controllers\TestTenantController::class, 'index']);

   // Routes pour les demandes de caisse
Route::prefix('caisse')->name('caisse.')->group(function () {
    // Routes liées aux demandes de fonds
    Route::prefix('demandes')->name('demandes.')->group(function () {
        Route::get('/', [FondsDemandeController::class, 'index'])->name('index');
        Route::get('/create', [FondsDemandeController::class, 'create'])->name('create');
        Route::post('/', [FondsDemandeController::class, 'store'])->name('store');
        Route::get('/{id}', [FondsDemandeController::class, 'show'])->name('show');
        Route::put('/{id}', [FondsDemandeController::class, 'update'])->name('update');
        Route::delete('/{id}', [FondsDemandeController::class, 'destroy'])->name('destroy');
        // Approver ou rejeter les demandes de fonds
        Route::patch('/{id}/approuver', [FondsDemandeController::class, 'approuver'])->name('approuver');
        Route::patch('/{id}/rejeter', [FondsDemandeController::class, 'rejeter'])->name('rejeter');
    });

    // Routes liées aux dépenses de caisse
    Route::resource('depenses', DepenseCaisseController::class);
});


    // Route pour afficher le rapport de caisse
Route::get('/rapport/caisse', [RapportCaisseController::class, 'index'])->name('caisse.rapport.index');


Route::prefix('conges')->name('demandes_conges.')->group(function () {
    Route::get('/', [DemandeCongeController::class, 'index'])->name('index');
    Route::get('/create', [DemandeCongeController::class, 'create'])->name('create');
    Route::post('/', [DemandeCongeController::class, 'store'])->name('store');
    Route::get('/{id}', [DemandeCongeController::class, 'show'])->name('show');
    Route::put('/{id}', [DemandeCongeController::class, 'update'])->name('update');
    Route::delete('/{id}', [DemandeCongeController::class, 'destroy'])->name('destroy');
    Route::patch('/{id}/approuver', [DemandeCongeController::class, 'approuver'])->name('approuver');
    Route::patch('/{id}/rejeter', [DemandeCongeController::class, 'rejeter'])->name('rejeter');  // Cette ligne doit être présente
});


    // Afficher la liste des dossiers personnels
Route::get('dossiers_personnels', [DossierPersonnelController::class, 'index'])->name('dossiers_personnels.index');

// Créer un nouveau dossier personnel
Route::get('dossiers_personnels/create', [DossierPersonnelController::class, 'create'])->name('dossiers_personnels.create');
Route::post('dossiers_personnels', [DossierPersonnelController::class, 'store'])->name('dossiers_personnels.store');

// Modifier un dossier personnel
Route::get('dossiers_personnels/{dossierPersonnel}/edit', [DossierPersonnelController::class, 'edit'])->name('dossiers_personnels.edit');
Route::put('dossiers_personnels/{dossierPersonnel}', [DossierPersonnelController::class, 'update'])->name('dossiers_personnels.update');

// Supprimer un dossier personnel
Route::delete('dossiers_personnels/{dossierPersonnel}', [DossierPersonnelController::class, 'destroy'])->name('dossiers_personnels.destroy');





});
