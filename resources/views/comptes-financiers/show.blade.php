@extends('layouts.app')
@section('title', 'Détail du Compte Financier')
@section('content')
<div class="container">
    <h1 class="mb-4">Détail du Compte Financier</h1>
    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title">{{ $compte->nom }}</h5>
            <p><strong>Type :</strong> {{ ucfirst($compte->type) }}</p>
            <p><strong>Solde :</strong> {{ number_format($compte->solde, 2, ',', ' ') }} $</p>
            <p><strong>Gestionnaire :</strong> {{ $compte->gestionnaire ? $compte->gestionnaire->name : 'Aucun' }}</p>
            <p><strong>Description :</strong> {{ $compte->description }}</p>
            <p><strong>Actif :</strong> {{ $compte->actif ? 'Oui' : 'Non' }}</p>
            <p><strong>Autoriser le découvert :</strong> {{ $compte->autoriser_decouvert ? 'Oui' : 'Non' }}</p>
        </div>
    </div>
    <a href="{{ route('comptes-financiers.edit', $compte) }}" class="btn btn-warning">Modifier</a>
    <form method="POST" action="{{ route('comptes-financiers.destroy', $compte) }}" class="d-inline">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger" onclick="return confirm('Voulez-vous vraiment supprimer ce compte ?')">Supprimer</button>
    </form>
    <a href="{{ route('comptes-financiers.index') }}" class="btn btn-secondary">Retour</a>
</div>
@endsection