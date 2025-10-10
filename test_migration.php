<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->boot();

// Test si la colonne existe
try {
    $appartements = \App\Models\Appartement::whereNull('locataire_id')->count();
    echo "SUCCESS: La colonne 'locataire_id' existe! Nombre d'appartements libres: " . $appartements . "\n";
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}

// Test si on peut récupérer tous les appartements
try {
    $totalAppartements = \App\Models\Appartement::count();
    echo "SUCCESS: Total appartements: " . $totalAppartements . "\n";
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}