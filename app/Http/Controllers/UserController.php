<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    public function index()
    {
        $users = User::all();
        return view('users.index', compact('users'));
    }

    public function create()
    {
        $comptesFinanciers = \App\Models\CompteFinancier::all();
        return view('users.create', compact('comptesFinanciers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,gestionnaire',
            'actif' => 'boolean',
            'compte_financier_id' => 'nullable|exists:comptes_financiers,id',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['actif'] = $request->has('actif');

        User::create($validated);

        return redirect()->route('users.index')
                        ->with('success', 'Utilisateur créé avec succès.');
    }

    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $comptesFinanciers = \App\Models\CompteFinancier::all();
        return view('users.edit', compact('user', 'comptesFinanciers'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:admin,gestionnaire',
            'actif' => 'boolean',
            'compte_financier_id' => 'nullable|exists:comptes_financiers,id',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $validated['actif'] = $request->has('actif');

        $user->update($validated);

        return redirect()->route('users.index')
                        ->with('success', 'Utilisateur mis à jour avec succès.');
    }

    public function destroy(User $user)
    {
        // Empêcher l'auto-suppression
        if ($user->id === auth()->id()) {
            return redirect()->route('users.index')
                            ->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        }

        // Vérifier qu'il reste au moins un admin
        if ($user->role === 'admin' && User::where('role', 'admin')->where('actif', true)->count() <= 1) {
            return redirect()->route('users.index')
                            ->with('error', 'Impossible de supprimer le dernier administrateur actif.');
        }

        $user->delete();

        return redirect()->route('users.index')
                        ->with('success', 'Utilisateur supprimé avec succès.');
    }
}