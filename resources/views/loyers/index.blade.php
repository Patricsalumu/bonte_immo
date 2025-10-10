@extends('layouts.app')

@section('title', 'Gestion des Loyers')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">Gestion des Loyers</h1>
                <a href="{{ route('loyers.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Nouveau Loyer
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
                    <h5 class="card-title mb-0">Liste des Loyers</h5>
                </div>
                <div class="card-body">
                    @if($loyers->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Période</th>
                                        <th>Locataire</th>
                                        <th>Appartement</th>
                                        <th>Montant</th>
                                        <th>Échéance</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($loyers as $loyer)
                                        <tr>
                                            <td>
                                                <strong>{{ str_pad($loyer->mois, 2, '0', STR_PAD_LEFT) }}/{{ $loyer->annee }}</strong>
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
                                            <td>{{ number_format($loyer->montant, 0, ',', ' ') }} FC</td>
                                            <td>{{ \Carbon\Carbon::parse($loyer->date_echeance)->format('d/m/Y') }}</td>
                                            <td>
                                                @switch($loyer->statut)
                                                    @case('paye')
                                                        <span class="badge bg-success">Payé</span>
                                                        @break
                                                    @case('partiel')
                                                        <span class="badge bg-warning">Partiel</span>
                                                        @break
                                                    @default
                                                        <span class="badge bg-danger">Impayé</span>
                                                @endswitch
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('loyers.show', $loyer) }}" class="btn btn-sm btn-outline-info">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    @if($loyer->statut !== 'paye')
                                                        <form action="{{ route('loyers.marquer-paye', $loyer) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            <button type="submit" class="btn btn-sm btn-outline-success" 
                                                                    onclick="return confirm('Marquer ce loyer comme payé ?')">
                                                                <i class="bi bi-check-circle"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                    <a href="{{ route('loyers.edit', $loyer) }}" class="btn btn-sm btn-outline-warning">
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
                            <i class="bi bi-calendar-check display-1 text-muted"></i>
                            <p class="text-muted mt-3">Aucun loyer enregistré</p>
                            <a href="{{ route('loyers.create') }}" class="btn btn-primary">
                                Créer le premier loyer
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection