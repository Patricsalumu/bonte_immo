@extends('layouts.app')
@section('title', 'Modifier le Compte Financier')
@section('content')
<div class="container">
    <h1 class="mb-4">Modifier le Compte Financier</h1>
    <form method="POST" action="{{ route('comptes-financiers.update', $compte) }}">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="nom" class="form-label">Nom du compte <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="nom" name="nom" value="{{ $compte->nom }}" required>
        </div>
        <div class="mb-3">
            <label for="type" class="form-label">Type <span class="text-danger">*</span></label>
            <select class="form-select" id="type" name="type" required>
                <option value="caisse" {{ $compte->type == 'caisse' ? 'selected' : '' }}>Caisse</option>
                <option value="banque" {{ $compte->type == 'banque' ? 'selected' : '' }}>Banque</option>
                <option value="epargne" {{ $compte->type == 'epargne' ? 'selected' : '' }}>Épargne</option>
                <option value="charge" {{ $compte->type == 'charge' ? 'selected' : '' }}>Charge</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="solde" class="form-label">Solde</label>
            <input type="number" class="form-control" id="solde" name="solde" value="{{ $compte->solde }}" step="0.01">
        </div>
        <div class="mb-3">
            <label for="gestionnaire_id" class="form-label">Gestionnaire</label>
            <select class="form-select" id="gestionnaire_id" name="gestionnaire_id">
                <option value="">Aucun</option>
                @foreach($utilisateurs as $user)
                    <option value="{{ $user->id }}" {{ $compte->gestionnaire_id == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" id="description" name="description" rows="2">{{ $compte->description }}</textarea>
        </div>
        <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" id="actif" name="actif" {{ $compte->actif ? 'checked' : '' }}>
            <label class="form-check-label" for="actif">Compte actif</label>
        </div>
        <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" id="autoriser_decouvert" name="autoriser_decouvert" {{ $compte->autoriser_decouvert ? 'checked' : '' }}>
            <label class="form-check-label" for="autoriser_decouvert">Autoriser le découvert</label>
        </div>
        <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
        <a href="{{ route('comptes-financiers.index') }}" class="btn btn-secondary">Annuler</a>
    </form>
</div>
@endsection