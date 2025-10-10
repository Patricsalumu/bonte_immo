<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            // Vérifier si l'utilisateur est actif
            if (!Auth::user()->actif) {
                Auth::logout();
                throw ValidationException::withMessages([
                    'email' => 'Votre compte a été désactivé.',
                ]);
            }

            return redirect()->intended('/dashboard');
        }

        throw ValidationException::withMessages([
            'email' => 'Les informations d\'identification ne correspondent pas.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/');
    }

    public function dashboard()
    {
        return view('dashboard');
    }

    public function showRegisterForm()
    {
        $this->middleware('admin');
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $this->middleware('admin');
        
        $validated = $request->validate([
            'nom' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'in:admin,gestionnaire'],
        ]);

        $user = User::create([
            'nom' => $validated['nom'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'actif' => true,
        ]);

        return redirect()->route('users.index')
            ->with('success', 'Utilisateur créé avec succès.');
    }
}