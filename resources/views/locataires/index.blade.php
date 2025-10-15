@extends('layouts.app')

@section('title', 'Gestion des Locataires')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">Gestion des Locataires</h1>
                <a href="{{ route('locataires.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Nouveau Locataire
                </a>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Liste des Locataires</h5>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('locataires.index') }}" class="row g-3 mb-3">
                        <div class="col-md-4">
                            <input type="text" name="nom" class="form-control" placeholder="Nom ou prénom" value="{{ request('nom') }}">
                        </div>
                        <div class="col-md-4">
                            <input type="text" name="numero" class="form-control" placeholder="Numéro de téléphone ou carte" value="{{ request('numero') }}">
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-outline-primary w-100">
                                <i class="bi bi-search"></i> Rechercher
                            </button>
                        </div>
                    </form>
                    @if($locataires->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Nom & Prénom</th>
                                        <th>Téléphone</th>
                                        <th>Appartement</th>
                                        <th>Date d'entrée</th>
                                        <th>Garantie</th>
                                         <th>Reste</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($locataires as $locataire)
                                        <tr>
                                            <td>
                                                <strong>{{ $locataire->nom }} {{ $locataire->prenom }}</strong>
                                            </td>
                                            <td>{{ $locataire->telephone }}</td>
                                            <td>
                                                @php
                                                    $loyerActif = $locataire->loyers()->where('statut', 'actif')->first();
                                                @endphp
                                                @if($loyerActif && $loyerActif->appartement)
                                                    <span class="badge bg-info">
                                                        {{ $loyerActif->appartement->immeuble->nom ?? 'N/A' }} - App. {{ $loyerActif->appartement->numero }}
                                                    </span>
                                                @else
                                                    <span class="badge bg-warning">Non assigné</span>
                                                @endif
                                            </td>
                                            <td>{{ \Carbon\Carbon::parse($locataire->date_entree)->format('d/m/Y') }}</td>
                                            <td>{{ number_format($locataire->garantie_initiale, 0, ',', ' ') }} $</td>
                                            <td>
                                                @php
                                                    $loyerActif = $locataire->loyers()->where('statut', 'actif')->first();
                                                @endphp
                                                {{ $loyerActif ? number_format($loyerActif->garantie_locative, 0, ',', ' ') . ' $' : '-' }}
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('locataires.show', $locataire) }}" class="btn btn-sm btn-outline-info">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    <a href="{{ route('locataires.edit', $locataire) }}" class="btn btn-sm btn-outline-warning">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="bi bi-people display-1 text-muted"></i>
                            <p class="text-muted mt-3">Aucun locataire enregistré</p>
                            <a href="{{ route('locataires.create') }}" class="btn btn-primary">
                                Enregistrer le premier locataire
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection