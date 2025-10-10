<?php
require 'vendor/autoload.php';
require 'bootstrap/app.php';

echo "=== Vérification des données ===\n";
echo "Locataires total: " . App\Models\Locataire::count() . "\n";
echo "Locataires actifs: " . App\Models\Locataire::where('actif', 1)->count() . "\n";
echo "Appartements total: " . App\Models\Appartement::count() . "\n";

echo "\n=== Détails des locataires ===\n";
$locataires = App\Models\Locataire::all();
foreach($locataires as $l) {
    echo "- {$l->nom} {$l->prenom} (actif: {$l->actif})\n";
}

echo "\n=== Détails des appartements ===\n";
$appartements = App\Models\Appartement::with('immeuble')->get();
foreach($appartements as $a) {
    echo "- {$a->immeuble->nom} Apt {$a->numero} ({$a->type}) - Loyer: {$a->loyer_mensuel} CDF\n";
}