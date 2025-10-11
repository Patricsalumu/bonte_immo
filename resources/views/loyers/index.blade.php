@extends('layouts.app')

@section('title', 'Gestion des Loyers')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">Gestion des Loyers</h1>
                <a href="{{ route('loyers.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Nouveau Contrat
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
                    <h5 class="card-title mb-0">Liste des Contrats de Loyer</h5>
                </div>
                <div class="card-body">
                    @if($loyers->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>ID</th>
                                        <th>Locataire</th>
                                        <th>Appartement</th>
                                        <th>Montant</th>
                                        <th>Période</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($loyers as $loyer)
                                        <tr>
                                            <td>
                                                <strong>#{{ $loyer->id }}</strong>
                                            </td>
                                            <td>
                                                @if($loyer->locataire)
                                                    {{ $loyer->locataire->nom }} {{ $loyer->locataire->prenom }}
                                                @else
                                                    <span class="text-muted">N/A</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($loyer->appartement)
                                                    {{ $loyer->appartement->immeuble->nom ?? 'N/A' }} - 
                                                    App. {{ $loyer->appartement->numero }}
                                                @else
                                                    <span class="text-muted">N/A</span>
                                                @endif
                                            </td>
                                            <td>{{ number_format($loyer->montant, 0, ',', ' ') }} CDF</td>
                                            <td>
                                                <div>
                                                    <small class="text-muted">Du:</small> {{ $loyer->date_debut->format('d/m/Y') }}<br>
                                                    @if($loyer->date_fin)
                                                        <small class="text-muted">Au:</small> {{ $loyer->date_fin->format('d/m/Y') }}
                                                    @else
                                                        <small class="text-muted">Durée indéterminée</small>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                @if($loyer->statut === 'actif')
                                                    <span class="badge bg-success">Actif</span>
                                                @else
                                                    <span class="badge bg-secondary">Inactif</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('loyers.show', $loyer) }}" class="btn btn-sm btn-outline-info">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('loyers.edit', $loyer) }}" class="btn btn-sm btn-outline-warning">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    @if($loyer->statut === 'actif')
                                                        <form action="{{ route('loyers.desactiver', $loyer) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                                    onclick="return confirm('Désactiver ce contrat ?')">
                                                                <i class="fas fa-times-circle"></i>
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
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-file-contract display-1 text-muted"></i>
                            <p class="text-muted mt-3">Aucun contrat de loyer enregistré</p>
                            <a href="{{ route('loyers.create') }}" class="btn btn-primary">
                                Créer le premier contrat
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection