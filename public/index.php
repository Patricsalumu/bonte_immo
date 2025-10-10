<?php

// Point d'entrée principal de l'application Laravel
define('LARAVEL_START', microtime(true));

// Enregistrer l'autoloader de Composer
require __DIR__.'/../vendor/autoload.php';

// Bootstrap de l'application Laravel
$app = require_once __DIR__.'/../bootstrap/app.php';

// Gérer la requête HTTP
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

$response->send();

$kernel->terminate($request, $response);