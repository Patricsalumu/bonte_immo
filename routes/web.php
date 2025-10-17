<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ImmeubleeController;
use App\Http\Controllers\AppartementController;
use App\Http\Controllers\LocataireController;
use App\Http\Controllers\LoyerController;
use App\Http\Controllers\PaiementController;
use App\Http\Controllers\FactureController;
use App\Http\Controllers\CaisseController;
use App\Http\Controllers\CompteFinancierController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RapportController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/


// Routes publiques
Route::get('/', function () {
    return redirect('/login');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('register', [AuthController::class, 'register']);
});
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Route publique pour téléchargement PDF facture
Route::get('public/factures/{facture}/pdf', [FactureController::class, 'exportPdfPublic'])->name('factures.export-pdf-public');

// Routes protégées par authentification
Route::middleware(['auth'])->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Routes accessibles à tous les utilisateurs authentifiés : rapports mensuel, téléchargement et actions factures
    Route::get('rapports/mensuel', [RapportController::class, 'mensuel'])->name('rapports.mensuel');
    // Export des rapports accessible aux utilisateurs authentifiés (permet téléchargement PDF/XLS)
    Route::get('rapports/export', [RapportController::class, 'export'])->name('rapports.export');
    // Télécharger PDF d'une facture (auth)
    Route::get('factures/{facture}/pdf', [FactureController::class, 'exportPdf'])->name('factures.export-pdf');
    // Voir une facture (accessible aux utilisateurs authentifiés)
    Route::get('factures/{facture}', [FactureController::class, 'show'])->name('factures.show');
    // Actions factures pour utilisateurs authentifiés
    Route::post('factures/{facture}/marquer-payee', [FactureController::class, 'marquerPayee'])->name('factures.marquer-payee');
    Route::post('factures/generer-mois', [FactureController::class, 'genererFacturesMois'])->name('factures.generer-mois');
    Route::post('factures/verifier-doublons', [FactureController::class, 'verifierDoublons'])->name('factures.verifier-doublons');
    Route::post('factures/verifier-doublons-loyer', [FactureController::class, 'verifierDoublonsPourLoyer'])->name('factures.verifier-doublons-loyer');
    Route::post('factures/generer-pour-loyer', [FactureController::class, 'genererPourLoyer'])->name('factures.generer-pour-loyer');
    
    // Routes accessibles aux gestionnaires : dashboard, appartements, locataires, paiements, rapports
    Route::middleware(['gestionnaire'])->group(function () {
        Route::resource('appartements', AppartementController::class)->except(['destroy']);
        Route::resource('locataires', LocataireController::class)->except(['destroy']);
        Route::get('ajax/locataires', [LocataireController::class, 'ajaxSearch'])->name('locataires.ajax-search');
        Route::resource('paiements', PaiementController::class)->except(['destroy']);

    // Rapports (lecture pour gestionnaire)
    Route::get('rapports', [RapportController::class, 'index'])->name('rapports.index');
        // Dashboard des factures accessible aux gestionnaires
        Route::get('factures-dashboard', [FactureController::class, 'dashboard'])->name('factures.dashboard');
        // Factures actions accessibles aux gestionnaires : marquer payée, générer et vérifications
        
    });
    
    // Routes admin uniquement
    Route::middleware(['admin'])->group(function () {
        // Suppressions (soft delete)
        Route::delete('immeubles/{immeuble}', [ImmeubleeController::class, 'destroy'])->name('immeubles.destroy');
        Route::delete('appartements/{appartement}', [AppartementController::class, 'destroy'])->name('appartements.destroy');
        Route::delete('locataires/{locataire}', [LocataireController::class, 'destroy'])->name('locataires.destroy');

    // Immeubles et loyers (gestion complète pour admin)
    Route::resource('immeubles', ImmeubleeController::class);
        Route::resource('loyers', LoyerController::class);

        // Paiements (modification et suppression)
        Route::get('paiements/{paiement}/edit', [PaiementController::class, 'edit'])->name('paiements.edit');
        Route::delete('paiements/{paiement}', [PaiementController::class, 'destroy'])->name('paiements.destroy');

        // Caisse (gestion complète)
        Route::get('caisse', [CaisseController::class, 'index'])->name('caisse.index');
        Route::get('caisse/journal', [CaisseController::class, 'journal'])->name('caisse.journal');
        Route::get('caisse/create', [CaisseController::class, 'create'])->name('caisse.create');
        Route::post('caisse', [CaisseController::class, 'store'])->name('caisse.store');
        Route::get('caisse/transfert', [CaisseController::class, 'transfert'])->name('caisse.transfert');
        Route::post('caisse/transfert', [CaisseController::class, 'executeTransfert'])->name('caisse.transfert.execute');
        Route::post('caisse/{mouvement}/annuler', [CaisseController::class, 'annuler'])->name('caisse.annuler');

        // Comptes financiers
        Route::resource('comptes-financiers', CompteFinancierController::class);

        // Notifications
        Route::get('notifications', [NotificationController::class, 'index'])->name('notifications.index');
        Route::post('notifications/envoyer', [NotificationController::class, 'envoyerRappel'])->name('notifications.envoyer');
        Route::get('notifications/locataires-immeuble', [NotificationController::class, 'getLocatairesByImmeuble'])->name('notifications.locataires-immeuble');
        Route::get('notifications/locataires-retard', [NotificationController::class, 'getLocatairesEnRetard'])->name('notifications.locataires-retard');

        // Gestion des utilisateurs
        Route::resource('users', UserController::class);
        Route::get('register', [AuthController::class, 'showRegisterForm'])->name('register');
        Route::post('register', [AuthController::class, 'register']);

            // Factures et fonctions avancées (admin)
            Route::get('factures/ajax', [FactureController::class, 'ajaxList'])->name('factures.ajax');
            // Ressource factures pour admin, sans la vue 'show' qui est accessible aux utilisateurs authentifiés
            Route::resource('factures', FactureController::class)->except(['show']);
            Route::delete('factures/{facture}', [FactureController::class, 'destroy'])->name('factures.destroy');


    });
});