@extends('layouts.app')
@section('title', 'Créer un Compte Financier (Caisse)')
@section('content')
<div class="container">
    <h1 class="mb-4">Créer un Compte Financier</h1>
    <form method="POST" action="{{ route('comptes-financiers.store') }}">
        @csrf
        <div class="mb-3">
            <label for="nom_compte" class="form-label">Nom compte <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="nom_compte" name="nom_compte" required placeholder="Nom du compte">
        </div>
        <div class="mb-3">
            <label for="type" class="form-label">Type <span class="text-danger">*</span></label>
            <select class="form-select" id="type" name="type" required>
                <option value="">Sélectionner...</option>
                <option value="caisse">Caisse</option>
                <option value="banque">Banque</option>
                <option value="epargne">Épargne</option>
                <option value="charge">Charge</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="solde_initial" class="form-label">Solde initial <span class="text-danger">*</span></label>
            <input type="number" class="form-control" id="solde_initial" name="solde_initial" min="0" step="0.01" required>
        </div>
        <div class="mb-3">
            <label for="gestionnaire_id" class="form-label">Gestionnaire</label>
            <select class="form-select" id="gestionnaire_id" name="gestionnaire_id">
                <option value="">Aucun</option>
                @foreach($utilisateurs as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" id="description" name="description" rows="2"></textarea>
        </div>
        <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" id="actif" name="actif" checked>
            <label class="form-check-label" for="actif">Compte actif</label>
        </div>
        <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" id="autoriser_decouvert" name="autoriser_decouvert">
            <label class="form-check-label" for="autoriser_decouvert">Autoriser le découvert</label>
        </div>
        <button type="submit" class="btn btn-primary">Créer le compte</button>
        <a href="{{ route('caisse.index') }}" class="btn btn-secondary">Annuler</a>
    </form>
</div>
@endsection