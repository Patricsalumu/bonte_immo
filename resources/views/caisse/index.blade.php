@extends('layouts.app')

@section('title', 'Gestion de la Caisse')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">Gestion de la Caisse</h1>
                @can('admin')
                <div class="btn-group">
                    <a href="{{ route('mouvements-caisse.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> Nouveau Mouvement
                    </a>
                    <a href="{{ route('mouvements-caisse.transfert') }}" class="btn btn-outline-primary">
                        <i class="bi bi-arrow-left-right"></i> Transfert
                    </a>
                </div>
                @endcan
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Résumé des comptes -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="card text-white bg-primary">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h5 class="card-title">Solde Total</h5>
                                    <h2 class="mb-0">{{ number_format($soldeTotal, 0, ',', ' ') }} FC</h2>
                                </div>
                                <div class="align-self-center">
                                    <i class="bi bi-wallet2 display-4"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-white bg-success">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h5 class="card-title">Nombre de Comptes</h5>
                                    <h2 class="mb-0">{{ $comptes->count() }}</h2>
                                </div>
                                <div class="align-self-center">
                                    <i class="bi bi-bank display-4"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-white bg-info">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h5 class="card-title">Mouvements Récents</h5>
                                    <h2 class="mb-0">{{ $mouvementsRecents->count() }}</h2>
                                </div>
                                <div class="align-self-center">
                                    <i class="bi bi-activity display-4"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Liste des comptes -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Comptes Financiers</h5>
                        </div>
                        <div class="card-body">
                            @if($comptes->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead class="table-dark">
                                            <tr>
                                                <th>Nom du Compte</th>
                                                <th>Type</th>
                                                <th>Solde</th>
                                                <th>Description</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($comptes as $compte)
                                                <tr>
                                                    <td><strong>{{ $compte->nom }}</strong></td>
                                                    <td>
                                                        @switch($compte->type)
                                                            @case('caisse')
                                                                <span class="badge bg-success">Caisse</span>
                                                                @break
                                                            @case('banque')
                                                                <span class="badge bg-primary">Banque</span>
                                                                @break
                                                            @case('epargne')
                                                                <span class="badge bg-info">Épargne</span>
                                                                @break
                                                            @default
                                                                <span class="badge bg-secondary">{{ ucfirst($compte->type) }}</span>
                                                        @endswitch
                                                    </td>
                                                    <td>
                                                        <strong class="{{ $compte->solde >= 0 ? 'text-success' : 'text-danger' }}">
                                                            {{ number_format($compte->solde, 0, ',', ' ') }} FC
                                                        </strong>
                                                    </td>
                                                    <td>{{ $compte->description }}</td>
                                                    <td>
                                                        <div class="btn-group" role="group">
                                                            <a href="{{ route('comptes-financiers.show', $compte) }}" class="btn btn-sm btn-outline-info">
                                                                <i class="bi bi-eye"></i>
                                                            </a>
                                                            @can('admin')
                                                            <a href="{{ route('comptes-financiers.edit', $compte) }}" class="btn btn-sm btn-outline-warning">
                                                                <i class="bi bi-pencil"></i>
                                                            </a>
                                                            @endcan
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <i class="bi bi-bank display-1 text-muted"></i>
                                    <p class="text-muted mt-3">Aucun compte financier configuré</p>
                                    @can('admin')
                                    <a href="{{ route('comptes-financiers.create') }}" class="btn btn-primary">
                                        Créer le premier compte
                                    </a>
                                    @endcan
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mouvements récents -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Mouvements Récents</h5>
                        </div>
                        <div class="card-body">
                            @if($mouvementsRecents->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>Type</th>
                                                <th>Compte Source</th>
                                                <th>Compte Destination</th>
                                                <th>Montant</th>
                                                <th>Description</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($mouvementsRecents as $mouvement)
                                                <tr>
                                                    <td>{{ \Carbon\Carbon::parse($mouvement->date_operation)->format('d/m/Y H:i') }}</td>
                                                    <td>
                                                        @switch($mouvement->type_mouvement)
                                                            @case('entree')
                                                                <span class="badge bg-success">Entrée</span>
                                                                @break
                                                            @case('sortie')
                                                                <span class="badge bg-danger">Sortie</span>
                                                                @break
                                                            @case('transfert')
                                                                <span class="badge bg-info">Transfert</span>
                                                                @break
                                                            @default
                                                                <span class="badge bg-secondary">{{ ucfirst($mouvement->type_mouvement) }}</span>
                                                        @endswitch
                                                    </td>
                                                    <td>{{ $mouvement->compteSource->nom ?? '-' }}</td>
                                                    <td>{{ $mouvement->compteDestination->nom ?? '-' }}</td>
                                                    <td>
                                                        <strong class="{{ $mouvement->type_mouvement === 'entree' ? 'text-success' : ($mouvement->type_mouvement === 'sortie' ? 'text-danger' : 'text-info') }}">
                                                            {{ number_format($mouvement->montant, 0, ',', ' ') }} FC
                                                        </strong>
                                                    </td>
                                                    <td>{{ $mouvement->description }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="text-center mt-3">
                                    <a href="{{ route('mouvements-caisse.index') }}" class="btn btn-outline-primary">
                                        Voir tous les mouvements
                                    </a>
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <i class="bi bi-activity display-1 text-muted"></i>
                                    <p class="text-muted mt-3">Aucun mouvement enregistré</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection