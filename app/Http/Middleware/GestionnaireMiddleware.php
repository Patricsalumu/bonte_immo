<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class GestionnaireMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();
        if (!$user->isAdmin() && !$user->isGestionnaire()) {
            abort(403, 'Accès non autorisé.');
        }

        return $next($request);
    }
}