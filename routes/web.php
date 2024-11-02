<?php  

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\AccueilController;
use App\Http\Controllers\PaiementController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\RedevanceController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\AdminController;

// Route pour définir la route home
Route::get('/home', [HomeController::class, 'index'])->name('home');

// Route pour afficher la page d'accueil et le tableau de bord
Route::get('/', [AccueilController::class, 'index'])->name('accueil')->middleware(['auth', 'verified']);

// Route pour afficher la page de connexion
Route::get('/login', [AuthenticatedSessionController::class, 'showLoginForm'])->name('login');

Route::resource('roles', RoleController::class);

// Route pour traiter la soumission du formulaire de connexion
Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login.submit');


// Middleware pour les utilisateurs authentifiés
Route::middleware('auth')->group(function () {
    // Routes pour les clients
    Route::prefix('clients')->name('web.clients.')->group(function () {
        Route::get('/', [ClientController::class, 'index'])->name('index');
        Route::get('/create', [ClientController::class, 'create'])->name('create')->middleware('role:read_write'); // Utilisateur avec permissions d'écriture
        Route::post('/', [ClientController::class, 'store'])->name('store')->middleware('role:read_write'); // Utilisateur avec permissions d'écriture
        Route::get('/{client}', [ClientController::class, 'show'])->name('show');
        Route::get('/{client}/edit', [ClientController::class, 'edit'])->name('edit')->middleware('role:read_write'); // Utilisateur avec permissions d'écriture
        Route::put('/{client}', [ClientController::class, 'update'])->name('update')->middleware('role:read_write'); // Utilisateur avec permissions d'écriture
        Route::delete('/{client}', [ClientController::class, 'destroy'])->name('destroy')->middleware('role:read_write'); // Utilisateur avec permissions d'écriture

        // Route pour récupérer les matières d'un client spécifique via AJAX
        Route::get('/clients/{clientId}/data', [ClientController::class, 'getClientData']);        
    });

            // Routes pour les paiements
            Route::middleware('auth')->group(function () {
            Route::prefix('paiements')->name('web.paiements.')->group(function () {
            Route::get('/', [PaiementController::class, 'index'])->name('index'); // Liste des paiements
            Route::get('/create', [PaiementController::class, 'create'])->name('create');
            Route::get('/{paiement}', [PaiementController::class, 'show'])->name('show'); // Détails d'un paiement
            Route::get('/{paiement}/edit', [PaiementController::class, 'edit'])->name('edit');           
            Route::post('/', [PaiementController::class, 'store'])->name('store'); // Enregistrement d'un paiement
            });
        
        // Les paiements ne peuvent être validés ou modifiés que par les validateurs
            Route::middleware('role:payment_validator')->group(function () {
            Route::put('/{paiement}/confirm', [PaiementController::class, 'confirm'])->name('confirm');
            Route::put('/{paiement}', [PaiementController::class, 'update'])->name('update'); // Mise à jour d'un paiement
            Route::delete('/{paiement}', [PaiementController::class, 'destroy'])->name('destroy');
        });
    });

    // Route pour le panneau d'administration
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.index')->middleware('role:admin'); // Utilisateur avec le rôle admin
});

// Routes d'authentification Laravel par défaut
require __DIR__.'/auth.php';

// Middleware pour l'authentification du profil
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit'); // Éditer le profil
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update'); // Mise à jour du profil
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy'); // Suppression du profil
});
