<?php

require_once 'vendor/autoload.php';

use Illuminate\Foundation\Application;
use Illuminate\Contracts\Console\Kernel;

$app = require_once 'bootstrap/app.php';
$app->make(Kernel::class)->bootstrap();

echo "=== Test de l'export PDF ===\n";

// Vérifier la configuration
echo "1. Vérification de la configuration DomPDF...\n";
if (class_exists('Barryvdh\DomPDF\Facade\Pdf')) {
    echo "✅ DomPDF installé et configuré\n";
} else {
    echo "❌ DomPDF non trouvé\n";
    exit(1);
}

// Vérifier les factures
echo "\n2. Vérification des factures...\n";
$facturesCount = \App\Models\Facture::count();
echo "Nombre total de factures: {$facturesCount}\n";

if ($facturesCount > 0) {
    $facture = \App\Models\Facture::with(['locataire', 'loyer.appartement.immeuble'])->first();
    echo "✅ Facture de test trouvée:\n";
    echo "   - ID: {$facture->id}\n";
    echo "   - Mois/Année: {$facture->mois}/{$facture->annee}\n";
    echo "   - Montant: " . number_format($facture->montant, 0, ',', ' ') . " FCFA\n";
    echo "   - Locataire: " . ($facture->locataire ? $facture->locataire->nom . ' ' . $facture->locataire->prenom : 'Non défini') . "\n";
    echo "   - Appartement: " . ($facture->loyer && $facture->loyer->appartement ? $facture->loyer->appartement->numero . ' (' . $facture->loyer->appartement->immeuble->nom . ')' : 'Non défini') . "\n";
    
    // Test de la méthode getMoisNom
    echo "   - Méthode getMoisNom(): " . $facture->getMoisNom() . "\n";
    
    // Tester la route PDF
    echo "\n3. URL de test pour l'export PDF:\n";
    echo "   http://localhost:8000/factures/{$facture->id}/pdf\n";
    
    echo "\n✅ Test terminé avec succès. Vous pouvez maintenant tester l'URL ci-dessus dans votre navigateur.\n";
} else {
    echo "❌ Aucune facture trouvée dans la base de données\n";
}