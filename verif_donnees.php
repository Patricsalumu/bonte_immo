<?php

require_once 'vendor/autoload.php';

use Illuminate\Foundation\Application;
use Illuminate\Contracts\Console\Kernel;

$app = require_once 'bootstrap/app.php';
$app->make(Kernel::class)->bootstrap();

echo "=== Vérification des données ===\n";

// Vérifier les locataires avec téléphones
echo "\n1. Locataires avec numéros de téléphone:\n";
$locataires = \App\Models\Locataire::whereNotNull('telephone')->get();
echo "Nombre de locataires avec téléphone: " . $locataires->count() . "\n";

foreach($locataires as $locataire) {
    echo "- {$locataire->nom} {$locataire->prenom}: {$locataire->telephone}\n";
}

// Vérifier les factures et leurs statuts
echo "\n2. Statuts des factures:\n";
$factures = \App\Models\Facture::with(['locataire'])->get();
foreach($factures as $facture) {
    $statut = $facture->estPayee() ? 'Payée' : ($facture->estEnRetard() ? 'En retard' : 'Non payée');
    echo "- Facture {$facture->numero_facture}: {$statut} - Locataire: {$facture->locataire->nom}\n";
}

echo "\n✅ Vérification terminée.\n";