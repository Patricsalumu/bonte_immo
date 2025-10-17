@extends('layouts.app')

@section('title', 'Modifier un Utilisateur')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Modifier un Utilisateur</h1>
    <a href="{{ route('users.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Retour
    </a>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('users.update', $user) }}">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nom" class="form-label">Nom complet <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nom') is-invalid @enderror" id="nom" name="nom" value="{{ old('nom', $user->nom) }}" required autofocus>
                            @error('nom')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Adresse email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="role" class="form-label">Rôle <span class="text-danger">*</span></label>
                        <select class="form-select @error('role') is-invalid @enderror" id="role" name="role" required>
                            <option value="gestionnaire" {{ old('role', $user->role) == 'gestionnaire' ? 'selected' : '' }}>Gestionnaire</option>
                            <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Administrateur</option>
                        </select>
                        @error('role')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="actif" name="actif" value="1" {{ old('actif', $user->actif) ? 'checked' : '' }}>
                            <label class="form-check-label" for="actif">Compte actif</label>
                        </div>
                        <div class="form-text">Un compte inactif ne peut pas se connecter à l'application</div>
                    </div>

                    <div class="mb-3">
                        <label for="compte_financier_id" class="form-label">Compte à débiter</label>
                        <select class="form-select @error('compte_financier_id') is-invalid @enderror" id="compte_financier_id" name="compte_financier_id">
                            <option value="">Sélectionner un compte</option>
                            @foreach($comptesFinanciers as $compte)
                                <option value="{{ $compte->id }}" {{ old('compte_financier_id', $user->compte_financier_id) == $compte->id ? 'selected' : '' }}>{{ $compte->nom_compte }}</option>
                            @endforeach
                        </select>
                        @error('compte_financier_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="password" class="form-label">Mot de passe (laisser vide pour ne pas changer)</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" autocomplete="new-password">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="password_confirmation" class="form-label">Confirmer le mot de passe</label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" autocomplete="new-password">
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('users.index') }}" class="btn btn-secondary">Annuler</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Enregistrer les modifications
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
