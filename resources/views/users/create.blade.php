@extends('layouts.app')

@section('title', 'Créer un Utilisateur')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Créer un Utilisateur</h1>
    <a href="{{ route('users.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Retour
    </a>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('users.store') }}">
                    @csrf

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nom" class="form-label">Nom complet <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('nom') is-invalid @enderror" 
                                   id="nom" 
                                   name="nom" 
                                   value="{{ old('nom') }}" 
                                   required 
                                   autofocus>
                            @error('nom')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Adresse email <span class="text-danger">*</span></label>
                            <input type="email" 
                                   class="form-control @error('email') is-invalid @enderror" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email') }}" 
                                   required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="password" class="form-label">Mot de passe <span class="text-danger">*</span></label>
                            <input type="password" 
                                   class="form-control @error('password') is-invalid @enderror" 
                                   id="password" 
                                   name="password" 
                                   required 
                                   minlength="8">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Minimum 8 caractères</div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="password_confirmation" class="form-label">Confirmer le mot de passe <span class="text-danger">*</span></label>
                            <input type="password" 
                                   class="form-control @error('password_confirmation') is-invalid @enderror" 
                                   id="password_confirmation" 
                                   name="password_confirmation" 
                                   required 
                                   minlength="8">
                            @error('password_confirmation')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="role" class="form-label">Rôle <span class="text-danger">*</span></label>
                        <select class="form-select @error('role') is-invalid @enderror" 
                                id="role" 
                                name="role" 
                                required>
                            <option value="">Sélectionner un rôle</option>
                            <option value="gestionnaire" {{ old('role') == 'gestionnaire' ? 'selected' : '' }}>
                                Gestionnaire
                            </option>
                            <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>
                                Administrateur
                            </option>
                        </select>
                        @error('role')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" 
                                   type="checkbox" 
                                   id="actif" 
                                   name="actif" 
                                   value="1" 
                                   {{ old('actif', true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="actif">
                                Compte actif
                            </label>
                        </div>
                        <div class="form-text">
                            Un compte inactif ne peut pas se connecter à l'application
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="compte_financier_id" class="form-label">Compte à débiter</label>
                        <select class="form-select @error('compte_financier_id') is-invalid @enderror" id="compte_financier_id" name="compte_financier_id">
                            <option value="">Sélectionner un compte</option>
                            @foreach($comptesFinanciers ?? [] as $compte)
                                <option value="{{ $compte->id }}" {{ old('compte_financier_id') == $compte->id ? 'selected' : '' }}>{{ $compte->nom_compte }}</option>
                            @endforeach
                        </select>
                        @error('compte_financier_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('users.index') }}" class="btn btn-secondary">Annuler</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Créer l'utilisateur
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card bg-light">
            <div class="card-body">
                <h6 class="card-title">
                    <i class="fas fa-info-circle"></i> Informations sur les rôles
                </h6>
                
                <div class="mb-3">
                    <h6 class="text-primary">
                        <i class="fas fa-user-shield"></i> Administrateur
                    </h6>
                    <ul class="small text-muted mb-0">
                        <li>Accès complet à toutes les fonctionnalités</li>
                        <li>Gestion des utilisateurs</li>
                        <li>Suppressions et modifications</li>
                        <li>Rapports et exports</li>
                        <li>Configuration du système</li>
                    </ul>
                </div>

                <div class="mb-3">
                    <h6 class="text-info">
                        <i class="fas fa-user-tie"></i> Gestionnaire
                    </h6>
                    <ul class="small text-muted mb-0">
                        <li>Gestion des immeubles et appartements</li>
                        <li>Gestion des locataires et contrats</li>
                        <li>Suivi des paiements</li>
                        <li>Consultation de la caisse</li>
                        <li>Pas d'accès à la gestion des utilisateurs</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="card bg-warning text-dark mt-3">
            <div class="card-body">
                <h6 class="card-title">
                    <i class="fas fa-exclamation-triangle"></i> Sécurité
                </h6>
                <ul class="small mb-0">
                    <li>Utilisez un mot de passe fort</li>
                    <li>L'email doit être unique</li>
                    <li>Informez l'utilisateur de ses identifiants</li>
                    <li>Vérifiez le rôle attribué</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
// Validation en temps réel du mot de passe
document.getElementById('password_confirmation').addEventListener('input', function() {
    const password = document.getElementById('password').value;
    const confirmation = this.value;
    
    if (password !== confirmation) {
        this.setCustomValidity('Les mots de passe ne correspondent pas');
    } else {
        this.setCustomValidity('');
    }
});
</script>
@endsection