@extends('layouts.app')

@section('title', 'Créer un Compte Financier')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="bi bi-plus-circle"></i> Créer un Nouveau Compte Financier
                    </h4>
                    <a href="{{ route('caisse.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left"></i> Retour
                    </a>
                </div>

                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <h6><i class="bi bi-exclamation-triangle"></i> Erreurs de validation :</h6>
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('comptes-financiers.store') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <!-- Informations de base -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="nom" class="form-label">
                                        <i class="bi bi-tag"></i> Nom du Compte <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" 
                                           class="form-control @error('nom') is-invalid @enderror" 
                                           id="nom" 
                                           name="nom" 
                                           value="{{ old('nom') }}"
                                           placeholder="Ex: Caisse Principale, Banque BCB, Charges Locatives"
                                           required>
                                    @error('nom')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="type" class="form-label">
                                        <i class="bi bi-bookmark"></i> Type de Compte <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select @error('type') is-invalid @enderror" 
                                            id="type" 
                                            name="type" 
                                            required>
                                        <option value="">Sélectionner le type...</option>
                                        <option value="caisse" {{ old('type') == 'caisse' ? 'selected' : '' }}>
                                            💰 Caisse (Liquidités)
                                        </option>
                                        <option value="banque" {{ old('type') == 'banque' ? 'selected' : '' }}>
                                            🏦 Banque (Compte bancaire)
                                        </option>
                                        <option value="charge" {{ old('type') == 'charge' ? 'selected' : '' }}>
                                            📋 Charge (Compte de dépenses)
                                        </option>
                                        <option value="epargne" {{ old('type') == 'epargne' ? 'selected' : '' }}>
                                            🔒 Épargne (Compte d'épargne)
                                        </option>
                                    </select>
                                    @error('type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="solde_initial" class="form-label">
                                        <i class="bi bi-cash"></i> Solde Initial (FC)
                                    </label>
                                    <input type="number" 
                                           class="form-control @error('solde_initial') is-invalid @enderror" 
                                           id="solde_initial" 
                                           name="solde_initial" 
                                           value="{{ old('solde_initial', 0) }}"
                                           min="0"
                                           step="0.01"
                                           placeholder="0.00">
                                    @error('solde_initial')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Le solde de départ du compte</div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="gestionnaire_id" class="form-label">
                                        <i class="bi bi-person"></i> Gestionnaire Associé
                                    </label>
                                    <select class="form-select @error('gestionnaire_id') is-invalid @enderror" 
                                            id="gestionnaire_id" 
                                            name="gestionnaire_id">
                                        <option value="">Aucun gestionnaire spécifique</option>
                                        @foreach($utilisateurs as $utilisateur)
                                            <option value="{{ $utilisateur->id }}" 
                                                    {{ old('gestionnaire_id') == $utilisateur->id ? 'selected' : '' }}>
                                                {{ $utilisateur->nom }} {{ $utilisateur->prenom }}
                                                <small>({{ $utilisateur->role }})</small>
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('gestionnaire_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Utilisateur responsable de ce compte</div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="description" class="form-label">
                                        <i class="bi bi-card-text"></i> Description
                                    </label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" 
                                              id="description" 
                                              name="description" 
                                              rows="3"
                                              placeholder="Description optionnelle du compte (objectif, utilisation, etc.)">{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Paramètres avancés -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" 
                                               type="checkbox" 
                                               id="actif" 
                                               name="actif" 
                                               value="1"
                                               {{ old('actif', true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="actif">
                                            <i class="bi bi-toggle-on"></i> Compte Actif
                                        </label>
                                        <div class="form-text">Les comptes inactifs ne peuvent pas effectuer de transactions</div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" 
                                               type="checkbox" 
                                               id="autoriser_decouvert" 
                                               name="autoriser_decouvert" 
                                               value="1"
                                               {{ old('autoriser_decouvert') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="autoriser_decouvert">
                                            <i class="bi bi-exclamation-triangle"></i> Autoriser Découvert
                                        </label>
                                        <div class="form-text">Permet d'avoir un solde négatif</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('caisse.index') }}" class="btn btn-secondary">
                                <i class="bi bi-x-circle"></i> Annuler
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i> Créer le Compte
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Aide contextuelle -->
            <div class="card mt-4">
                <div class="card-header">
                    <h6 class="mb-0"><i class="bi bi-info-circle"></i> Aide - Types de Comptes</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-primary">💰 Caisse</h6>
                            <p class="small mb-3">Pour gérer les liquidités (espèces, petite caisse)</p>
                            
                            <h6 class="text-success">🏦 Banque</h6>
                            <p class="small mb-3">Pour les comptes bancaires et virements</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-warning">📋 Charge</h6>
                            <p class="small mb-3">Pour enregistrer les dépenses et charges</p>
                            
                            <h6 class="text-info">🔒 Épargne</h6>
                            <p class="small mb-0">Pour les fonds de réserve et épargne</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const typeSelect = document.getElementById('type');
    const soldeInitialInput = document.getElementById('solde_initial');
    const decouvertCheckbox = document.getElementById('autoriser_decouvert');
    
    // Ajuster les options selon le type de compte
    typeSelect.addEventListener('change', function() {
        const type = this.value;
        
        if (type === 'charge') {
            soldeInitialInput.value = 0;
            soldeInitialInput.readOnly = true;
            decouvertCheckbox.checked = false;
            decouvertCheckbox.disabled = true;
        } else {
            soldeInitialInput.readOnly = false;
            decouvertCheckbox.disabled = false;
        }
    });
    
    // Trigger initial state
    if (typeSelect.value) {
        typeSelect.dispatchEvent(new Event('change'));
    }
});
</script>
@endsection