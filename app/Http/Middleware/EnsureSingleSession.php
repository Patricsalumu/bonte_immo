<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class EnsureSingleSession
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            try {
                $userId = Auth::id();
                $cacheKey = 'user_session_' . $userId;
                $stored = Cache::get($cacheKey);
                $current = session()->getId();

                if ($stored && $stored !== $current) {
                    // Destroy current auth and show session expired
                    Auth::logout();
                    Log::info('Session conflict detected, logging out user', ['user' => $userId, 'stored_session' => $stored, 'current_session' => $current]);

                    if ($request->expectsJson()) {
                        return response()->json(['message' => 'Votre session a été fermée ailleurs. Veuillez vous reconnecter.'], 419);
                    }

                    return response()->view('errors.session_expired', [], 419);
                }
            } catch (\Throwable $e) {
                Log::warning('Error checking single session middleware', ['error' => $e->getMessage()]);
            }
        }

        return $next($request);
    }
}
