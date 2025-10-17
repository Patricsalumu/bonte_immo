@extends('layouts.app')

@section('title', 'Rapport - Factures')

@section('content')
<div class="container-fluid">
    <ul class="nav nav-tabs mb-3">
        <li class="nav-item">
            <a class="nav-link active" href="#">Dashboard Factures</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('rapports.mensuel') }}">Rapport Mensuel</a>
        </li>
    </ul>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Rapport mensuel des factures</h1>
        <a href="{{ route('factures.index') }}" class="btn btn-outline-secondary">Retour aux factures</a>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('factures.dashboard') }}" class="row g-2 align-items-end">
                <div class="col-auto">
                    <label for="mois" class="form-label">Mois</label>
                    <select id="mois" name="mois" class="form-select">
                        @for($m=1; $m<=12; $m++)
                            <option value="{{ $m }}" {{ (old('mois', $stats['periode']['mois'] ?? now()->month) == $m) ? 'selected' : '' }}>
                                {{ DateTime::createFromFormat('!m', $m)->format('F') }}
                            </option>
                        @endfor
                    </select>
                </div>
                <div class="col-auto">
                    <label for="annee" class="form-label">Année</label>
                    <select id="annee" name="annee" class="form-select">
                        @foreach($annees as $annee)
                            <option value="{{ $annee }}" {{ (old('annee', $stats['periode']['annee'] ?? now()->year) == $annee) ? 'selected' : '' }}>{{ $annee }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-primary">Filtrer</button>
                </div>
            </form>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card text-white bg-primary h-100">
                <div class="card-body">
                    <h6>Total factures</h6>
                    <h3>{{ number_format($stats['total'] ?? 0, 0, ',', ' ') }}</h3>
                    <small>Période: {{ $stats['periode']['mois'] ?? '' }}/{{ $stats['periode']['annee'] ?? '' }}</small>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card text-white bg-success h-100">
                <div class="card-body">
                    <h6>Factures payées</h6>
                    <h3>{{ number_format($stats['payees'] ?? 0, 0, ',', ' ') }}</h3>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card text-white bg-danger h-100">
                <div class="card-body">
                    <h6>Non payées</h6>
                    <h3>{{ number_format($stats['non_payees'] ?? 0, 0, ',', ' ') }}</h3>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card text-white bg-warning h-100">
                <div class="card-body">
                    <h6>Partiellement payées</h6>
                    <h3>{{ number_format($stats['partielles'] ?? 0, 0, ',', ' ') }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-4 mb-3">
            <div class="card h-100">
                <div class="card-body">
                    <h6>Montant total à recouvrer</h6>
                    <h3>{{ number_format($stats['montant_total'] ?? 0, 0, ',', ' ') }} FC</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card h-100">
                <div class="card-body">
                    <h6>Montant déjà recouvré</h6>
                    <h3>{{ number_format($stats['montant_paye'] ?? 0, 0, ',', ' ') }} FC</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card h-100">
                <div class="card-body">
                    <h6>Reste à payer</h6>
                    <h3>{{ number_format($stats['reste_a_payer'] ?? 0, 0, ',', ' ') }} FC</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12">
            <h5>Évolution 12 mois</h5>
            <div class="table-responsive">
                <table class="table table-sm table-striped">
                    <thead>
                        <tr>
                            <th>Période</th>
                            <th>Total</th>
                            <th>Payées</th>
                            <th>Montant</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($stats['evolution'] as $ligne)
                            <tr>
                                <td>{{ $ligne['periode'] }}</td>
                                <td>{{ $ligne['total'] }}</td>
                                <td>{{ $ligne['payees'] }}</td>
                                <td>{{ number_format($ligne['montant'], 0, ',', ' ') }} FC</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection
