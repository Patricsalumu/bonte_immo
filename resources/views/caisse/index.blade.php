@extends('layouts.app')

@section('title', 'Gestion de la Caisse')

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
                    <a class="nav-link active" href="{{ route('caisse.index') }}">
                        <i class="bi bi-speedometer2"></i> Tableau de Bord
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

            <!-- Résumé des comptes -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="card text-white bg-primary">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h5 class="card-title">Solde Total</h5>
                                    <h2 class="mb-0">{{ number_format($soldeTotal, 0, ',', ' ') }} $</h2>
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
                            <a href="{{ route('comptes-financiers.create') }}" class="btn btn-primary btn-sm float-end">
                                <i class="bi bi-plus-circle"></i> Nouveau Compte
                            </a>
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
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($comptes as $compte)
                                                <tr>
                                                    <td><strong>{{ $compte->nom_compte }}</strong></td>
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
                                                        <strong class="{{ $compte->solde_actuel >= 0 ? 'text-success' : 'text-danger' }}">
                                                            {{ number_format($compte->solde_actuel, 0, ',', ' ') }} $
                                                        </strong>
                                                    </td>
                                                    <td>{{ $compte->description }}</td>
                                                </tr>
                                            @endforeach
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