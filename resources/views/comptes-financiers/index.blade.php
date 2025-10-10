@extends('layouts.app')

@section('title', 'Comptes Financiers')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Comptes Financiers</h1>
    <a href="{{ route('comptes-financiers.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Nouveau Compte
    </a>
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

<div class="card">
    <div class="card-body">
        @if($comptes->count() > 0)
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Nom du Compte</th>
                            <th>Type</th>
                            <th>Banque</th>
                            <th>Numéro de Compte</th>
                            <th>Solde Actuel</th>
                            <th>Statut</th>
                            <th width="200">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($comptes as $compte)
                        <tr>
                            <td>
                                <strong>{{ $compte->nom }}</strong>
                            </td>
                            <td>
                                <span class="badge bg-info">{{ ucfirst($compte->type) }}</span>
                            </td>
                            <td>{{ $compte->banque ?? 'Non spécifiée' }}</td>
                            <td>
                                <code>{{ $compte->numero_compte ?? 'N/A' }}</code>
                            </td>
                            <td>
                                <span class="fw-bold {{ $compte->solde_actuel >= 0 ? 'text-success' : 'text-danger' }}">
                                    {{ number_format($compte->solde_actuel, 0, ',', ' ') }} CDF
                                </span>
                            </td>
                            <td>
                                @if($compte->actif)
                                    <span class="badge bg-success">Actif</span>
                                @else
                                    <span class="badge bg-secondary">Inactif</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('comptes-financiers.show', $compte) }}" 
                                       class="btn btn-outline-info btn-sm">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('comptes-financiers.edit', $compte) }}" 
                                       class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @if(auth()->user()->role === 'admin')
                                    <form method="POST" 
                                          action="{{ route('comptes-financiers.destroy', $compte) }}" 
                                          class="d-inline"
                                          onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce compte ?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="row mt-4">
                <div class="col-md-6">
                    <div class="card bg-light">
                        <div class="card-body">
                            <h6 class="card-title">Résumé des Comptes</h6>
                            <p class="mb-1"><strong>Total des comptes actifs:</strong> {{ $comptes->where('actif', true)->count() }}</p>
                            <p class="mb-1"><strong>Solde total:</strong> 
                                <span class="fw-bold {{ $comptes->sum('solde_actuel') >= 0 ? 'text-success' : 'text-danger' }}">
                                    {{ number_format($comptes->sum('solde_actuel'), 0, ',', ' ') }} CDF
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-university fa-4x text-muted mb-3"></i>
                <h4 class="text-muted">Aucun compte financier</h4>
                <p class="text-muted">Commencez par créer votre premier compte financier.</p>
                <a href="{{ route('comptes-financiers.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Créer un compte
                </a>
            </div>
        @endif
    </div>
</div>
@endsection