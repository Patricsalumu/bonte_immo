@extends('layouts.app')

@section('title', 'Gestion des Immeubles')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">Gestion des Immeubles</h1>
                <a href="{{ route('immeubles.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Nouvel Immeuble
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
                    <h5 class="card-title mb-0">Liste des Immeubles</h5>
                </div>
                <div class="card-body">
                    @if($immeubles->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Nom</th>
                                        <th>Adresse</th>
                                        <th>Nombre d'appartements</th>
                                        <th>Gestionnaire</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($immeubles as $immeuble)
                                        <tr>
                                            <td>
                                                <strong>{{ $immeuble->nom }}</strong>
                                            </td>
                                            <td>{{ $immeuble->adresse }}</td>
                                            <td>
                                                <span class="badge bg-info">
                                                    {{ $immeuble->appartements_count ?? 0 }} appartements
                                                </span>
                                            </td>
                                            <td>{{ $immeuble->gestionnaire ?? 'Non assigné' }}</td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('immeubles.show', $immeuble) }}" class="btn btn-sm btn-outline-info">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    <a href="{{ route('immeubles.edit', $immeuble) }}" class="btn btn-sm btn-outline-warning">
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
                            <i class="bi bi-building display-1 text-muted"></i>
                            <p class="text-muted mt-3">Aucun immeuble enregistré</p>
                            <a href="{{ route('immeubles.create') }}" class="btn btn-primary">
                                Créer le premier immeuble
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection