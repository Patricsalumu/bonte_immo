<?php
// Test de débug pour le système de paiement

require_once 'vendor/autoload.php';

// Configuration de base Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Facture;
use App\Models\Paiement;
use App\Models\CompteFinancier;
use App\Models\MouvementCaisse;
use App\Models\Loyer;

try {
    echo "=== TEST DU SYSTÈME DE PAIEMENT ===\n\n";
    
    // Test 1: Vérifier les tables
    echo "1. Vérification des tables de base de données...\n";
    
    $factures = Facture::count();
    echo "   - Factures: $factures\n";
    
    $paiements = Paiement::count();
    echo "   - Paiements: $paiements\n";
    
    $comptes = CompteFinancier::count();
    echo "   - Comptes financiers: $comptes\n";
    
    $mouvements = MouvementCaisse::count();
    echo "   - Mouvements de caisse: $mouvements\n";
    
    $loyers = Loyer::count();
    echo "   - Loyers: $loyers\n\n";
    
    // Test 2: Récupérer une facture impayée
    echo "2. Recherche d'une facture impayée...\n";
    $facture = Facture::where('statut', 'impayee')->first();
    
    if ($facture) {
        echo "   - Facture trouvée: ID = {$facture->id}, Montant = {$facture->montant}\n";
        echo "   - Statut: {$facture->statut}\n";
        echo "   - Type: {$facture->type}\n\n";
        
        // Test 3: Vérifier les méthodes de la facture
        echo "3. Test des méthodes de la facture...\n";
        
        $montantPaye = $facture->montantPaye();
        echo "   - Montant déjà payé: $montantPaye\n";
        
        $peutRecevoir = $facture->peutRecevoirPaiement();
        echo "   - Peut recevoir paiement: " . ($peutRecevoir ? "OUI" : "NON") . "\n";
        
        $montantRestant = $facture->montantRestant();
        echo "   - Montant restant: $montantRestant\n\n";
        
        // Test 4: Vérifier les comptes financiers
        echo "4. Vérification des comptes financiers...\n";
        $comptesCaisse = CompteFinancier::where('type', 'caisse')->get();
        foreach ($comptesCaisse as $compte) {
            echo "   - Compte {$compte->nom}: {$compte->solde}\n";
        }
        echo "\n";
        
        // Test 5: Vérifier la garantie locative si applicable
        if ($facture->type === 'loyer') {
            echo "5. Vérification de la garantie locative...\n";
            $loyer = Loyer::where('facture_id', $facture->id)->first();
            if ($loyer) {
                echo "   - Loyer trouvé: ID = {$loyer->id}\n";
                echo "   - Garantie locative: {$loyer->garantie_locative}\n\n";
            }
        }
        
        // Test 6: Simuler les données d'un paiement
        echo "6. Test de validation des données de paiement...\n";
        
        $donneesPaiement = [
            'facture_id' => $facture->id,
            'montant' => min($montantRestant, 5000),
            'mode_paiement' => 'cash',
            'reference_paiement' => 'TEST-' . date('Ymd-His'),
            'notes' => 'Test de paiement automatique'
        ];
        
        echo "   - Données du test:\n";
        foreach ($donneesPaiement as $key => $value) {
            echo "     {$key}: {$value}\n";
        }
        echo "\n";
        
        // Test 7: Vérifier les valeurs ENUM
        echo "7. Test des valeurs ENUM autorisées...\n";
        $enumValues = ['cash', 'virement', 'mobile_money', 'garantie_locative'];
        echo "   - Valeurs ENUM mode_paiement: " . implode(', ', $enumValues) . "\n";
        echo "   - Mode choisi: {$donneesPaiement['mode_paiement']}\n";
        $isValidEnum = in_array($donneesPaiement['mode_paiement'], $enumValues);
        echo "   - Valide: " . ($isValidEnum ? "OUI" : "NON") . "\n\n";
        
        echo "=== TEST TERMINÉ AVEC SUCCÈS ===\n";
        
    } else {
        echo "   - Aucune facture impayée trouvée\n";
        
        // Créer une facture test
        echo "\n2b. Création d'une facture test...\n";
        // Code pour créer une facture test si nécessaire
    }
    
} catch (Exception $e) {
    echo "ERREUR: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}