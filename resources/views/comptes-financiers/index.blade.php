@extends('layouts.app')

@section('title', 'Comptes Financiers')

@section('content')
<div class="container-fluid">
    <!-- Navigation par onglets -->
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h1 class="h3 mb-0">Gestion de la Caisse</h1>
            </div>
            
            <!-- Onglets de navigation -->
            <ul class="nav nav-tabs mb-4" id="caisseTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link" href="{{ route('caisse.index') }}">
                        <i class="bi bi-speedometer2"></i> Tableau de Bord
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link active" href="{{ route('comptes-financiers.index') }}">
                        <i class="bi bi-bank"></i> Comptes Financiers
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" href="{{ route('caisse.journal') }}">
                        <i class="bi bi-journal-text"></i> Journal de Caisse
                    </a>
                </li>
            </ul>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Actions et résumé -->
    <div class="row mb-4">
        <div class="col-md-8">
            <!-- Résumé par type de compte -->
            <div class="row">
                <div class="col-md-3">
                    <div class="card text-white bg-primary">
                        <div class="card-body text-center">
                            <i class="bi bi-wallet2 display-6"></i>
                            <h6 class="card-title mt-2">Caisses</h6>
                            <h4 class="mb-0">{{ $statistiques['caisse']['nombre'] ?? 0 }}</h4>
                            <small>{{ number_format($statistiques['caisse']['solde'] ?? 0, 0, ',', ' ') }} FC</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-white bg-success">
                        <div class="card-body text-center">
                            <i class="bi bi-bank display-6"></i>
                            <h6 class="card-title mt-2">Banques</h6>
                            <h4 class="mb-0">{{ $statistiques['banque']['nombre'] ?? 0 }}</h4>
                            <small>{{ number_format($statistiques['banque']['solde'] ?? 0, 0, ',', ' ') }} FC</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-white bg-warning">
                        <div class="card-body text-center">
                            <i class="bi bi-piggy-bank display-6"></i>
                            <h6 class="card-title mt-2">Épargnes</h6>
                            <h4 class="mb-0">{{ $statistiques['epargne']['nombre'] ?? 0 }}</h4>
                            <small>{{ number_format($statistiques['epargne']['solde'] ?? 0, 0, ',', ' ') }} FC</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-white bg-danger">
                        <div class="card-body text-center">
                            <i class="bi bi-receipt display-6"></i>
                            <h6 class="card-title mt-2">Charges</h6>
                            <h4 class="mb-0">{{ $statistiques['charge']['nombre'] ?? 0 }}</h4>
                            <small>{{ number_format($statistiques['charge']['solde'] ?? 0, 0, ',', ' ') }} FC</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Actions</h5>
                    @can('admin')
                    <div class="d-grid gap-2">
                        <a href="{{ route('comptes-financiers.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle"></i> Nouveau Compte
                        </a>
                        <a href="{{ route('comptes-financiers.index', ['export' => 'pdf']) }}" class="btn btn-outline-danger">
                            <i class="bi bi-file-pdf"></i> Exporter la Liste
                        </a>
                    </div>
                    @endcan
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des comptes financiers -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Liste des Comptes Financiers</h5>
        </div>
        <div class="card-body">
            @if($comptes->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>Nom du Compte</th>
                                <th>Type</th>
                                <th>Gestionnaire</th>
                                <th class="text-end">Solde</th>
                                <th>Statut</th>
                                <th>Découvert</th>
                                <th>Créé le</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($comptes as $compte)
                                <tr>
                                    <td>
                                        <strong>{{ $compte->nom }}</strong>
                                        @if($compte->description)
                                            <br><small class="text-muted">{{ $compte->description }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        @switch($compte->type)
                                            @case('caisse')
                                                <span class="badge bg-primary">
                                                    <i class="bi bi-wallet2"></i> Caisse
                                                </span>
                                                @break
                                            @case('banque')
                                                <span class="badge bg-success">
                                                    <i class="bi bi-bank"></i> Banque
                                                </span>
                                                @break
                                            @case('epargne')
                                                <span class="badge bg-warning">
                                                    <i class="bi bi-piggy-bank"></i> Épargne
                                                </span>
                                                @break
                                            @case('charge')
                                                <span class="badge bg-danger">
                                                    <i class="bi bi-receipt"></i> Charge
                                                </span>
                                                @break
                                        @endswitch
                                    </td>
                                    <td>
                                        @if($compte->gestionnaire)
                                            <span class="badge bg-secondary">{{ $compte->gestionnaire->name }}</span>
                                        @else
                                            <span class="text-muted">Non assigné</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <strong class="{{ $compte->solde_actuel < 0 ? 'text-danger' : 'text-success' }}">
                                            {{ number_format($compte->solde_actuel, 0, ',', ' ') }} FC
                                        </strong>
                                    </td>
                                    <td>
                                        @if($compte->actif)
                                            <span class="badge bg-success">Actif</span>
                                        @else
                                            <span class="badge bg-secondary">Inactif</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($compte->autoriser_decouvert)
                                            <span class="badge bg-warning">
                                                <i class="bi bi-check-circle"></i> Autorisé
                                            </span>
                                        @else
                                            <span class="badge bg-light text-dark">
                                                <i class="bi bi-x-circle"></i> Non autorisé
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $compte->created_at->format('d/m/Y') }}
                                        <br><small class="text-muted">{{ $compte->created_at->diffForHumans() }}</small>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('comptes-financiers.show', $compte->id) }}" 
                                               class="btn btn-outline-info" data-bs-toggle="tooltip" title="Voir">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            @can('admin')
                                            <a href="{{ route('comptes-financiers.edit', $compte->id) }}" 
                                               class="btn btn-outline-warning" data-bs-toggle="tooltip" title="Modifier">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            @if(!$compte->mouvements()->exists())
                                                <form method="POST" action="{{ route('comptes-financiers.destroy', $compte->id) }}" 
                                                      class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-danger" 
                                                            onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce compte ?')"
                                                            data-bs-toggle="tooltip" title="Supprimer">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            @else
                                                <button type="button" class="btn btn-outline-danger" disabled
                                                        data-bs-toggle="tooltip" title="Impossible de supprimer (mouvements existants)">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            @endif
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($comptes->hasPages())
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div>
                            <small class="text-muted">
                                Affichage de {{ $comptes->firstItem() }} à {{ $comptes->lastItem() }} 
                                sur {{ $comptes->total() }} comptes
                            </small>
                        </div>
                        {{ $comptes->links('vendor.pagination.custom') }}
                    </div>
                @endif
            @else
                <div class="text-center py-5">
                    <i class="bi bi-bank display-1 text-muted"></i>
                    <h5 class="mt-3">Aucun compte financier</h5>
                    <p class="text-muted">Commencez par créer votre premier compte financier.</p>
                    @can('admin')
                    <a href="{{ route('comptes-financiers.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> Créer le premier compte
                    </a>
                    @endcan
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Activation des tooltips
document.addEventListener('DOMContentLoaded', function() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>
@endpush