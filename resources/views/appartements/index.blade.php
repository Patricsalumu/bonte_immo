@extends('layouts.app')

@section('title', 'Gestion des Appartements')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">Gestion des Appartements</h1>
                <a href="{{ route('appartements.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Nouvel Appartement
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
                    <h5 class="card-title mb-0">Liste des Appartements</h5>
                </div>
                <div class="card-body">
                    @if($appartements->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Immeuble</th>
                                        <th>Numéro</th>
                                        <th>Type</th>
                                        <th>Surface</th>
                                        <th>Loyer</th>
                                        <th>Locataire</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($appartements as $appartement)
                                        <tr>
                                            <td>{{ $appartement->immeuble->nom ?? 'N/A' }}</td>
                                            <td><strong>{{ $appartement->numero }}</strong></td>
                                            <td>{{ ucfirst($appartement->type) }}</td>
                                            <td>{{ $appartement->surface }} m²</td>
                                            <td>{{ number_format($appartement->loyer_mensuel, 0, ',', ' ') }} FC</td>
                                            <td>
                                                @if($appartement->locataire)
                                                    <span class="badge bg-success">
                                                        {{ $appartement->locataire->nom }} {{ $appartement->locataire->prenom }}
                                                    </span>
                                                @else
                                                    <span class="badge bg-warning">Libre</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($appartement->locataire)
                                                    <span class="badge bg-success">Occupé</span>
                                                @else
                                                    <span class="badge bg-danger">Libre</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('appartements.show', $appartement) }}" class="btn btn-sm btn-outline-info">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    <a href="{{ route('appartements.edit', $appartement) }}" class="btn btn-sm btn-outline-warning">
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
                            <i class="bi bi-house display-1 text-muted"></i>
                            <p class="text-muted mt-3">Aucun appartement enregistré</p>
                            <a href="{{ route('appartements.create') }}" class="btn btn-primary">
                                Créer le premier appartement
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection