@extends('layouts.app')

@section('title', 'Rapport Mensuel des Loyers')

@section('content')
<div class="mb-3">
    <ul class="nav nav-tabs">
        <li class="nav-item">
            <a class="nav-link" href="{{ route('factures.dashboard') }}">Dashboard Factures</a>
        </li>
        <li class="nav-item">
            <a class="nav-link active" href="#">Rapport Mensuel</a>
        </li>
    </ul>
</div>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">
        <i class="fas fa-file-invoice-dollar"></i> Rapport Mensuel des Loyers
    </h1>
    <form method="GET" class="d-flex gap-2" action="{{ route('rapports.mensuel') }}">
        <select name="mois" class="form-select" style="width:120px">
            @foreach($nomMois as $num => $libelle)
                <option value="{{ $num }}" {{ $mois == $num ? 'selected' : '' }}>{{ $libelle }}</option>
            @endforeach
        </select>
        <select name="annee" class="form-select" style="width:100px">
            @for($i = date('Y'); $i >= date('Y')-5; $i--)
                <option value="{{ $i }}" {{ $annee == $i ? 'selected' : '' }}>{{ $i }}</option>
            @endfor
        </select>
            <select name="percepteur" class="form-select" style="width:150px">
                <option value="">Tous les percepteurs</option>
                @foreach($percepteurs as $percepteur)
                    <option value="{{ $percepteur->id }}" {{ request('percepteur') == $percepteur->id ? 'selected' : '' }}>{{ $percepteur->nom }}</option>
                @endforeach
            </select>
            <select name="statut" class="form-select" style="width:150px">
                <option value="">Tous les statuts</option>
                <option value="payee" {{ request('statut') == 'payee' ? 'selected' : '' }}>Payées</option>
                <option value="non_payee" {{ request('statut') == 'non_payee' ? 'selected' : '' }}>Non payées</option>
                <option value="partielle" {{ request('statut') == 'partielle' ? 'selected' : '' }}>Partielles</option>
            </select>
        <button type="submit" class="btn btn-primary">Filtrer</button>
        <a href="{{ route('rapports.export', [
            'type'=>'mensuel',
            'format'=>'pdf',
            'mois'=>$mois,
            'annee'=>$annee,
            'percepteur'=>request('percepteur'),
            'statut'=>request('statut')
        ]) }}" class="btn btn-success">
            <i class="fas fa-file-pdf"></i> Exporter PDF
        </a>
    </form>
</div>


@foreach($factures->groupBy('appartement.immeuble.nom') as $immeuble => $facturesImmeuble)
    <h4 class="mt-4 mb-2 text-primary">Immeuble : {{ $immeuble }}</h4>
    <div class="table-responsive mb-3">
        <table class="table table-bordered table-sm">
            <thead class="table-light">
                <tr>
                    <th>Appartement</th>
                    <th>Client</th>
                    <th>Montant Facture</th>
                    <th>Montant Payé</th>
                    <th>Date Paiement</th>
                    <th>Percepteur</th>
                </tr>
            </thead>
            <tbody>
                @foreach($facturesImmeuble as $facture)
                @php
                    $paiementsValides = $facture->paiements->where('est_annule', false);
                    $montantPayes = $paiementsValides->sum('montant');
                    $dernierPaiement = $paiementsValides->sortByDesc('created_at')->first();
                    $datePaiement = $dernierPaiement ? \Carbon\Carbon::parse($dernierPaiement->created_at)->format('d/m/Y') : '-';
                    $percepteurNom = $dernierPaiement && $dernierPaiement->utilisateur ? $dernierPaiement->utilisateur->nom : '-';
                @endphp
                <tr>
                    <td>{{ $facture->appartement->numero ?? 'N/A' }}</td>
                    <td>{{ $facture->locataire->nom ?? 'N/A' }}</td>
                    <td>{{ number_format($facture->montant, 0, ',', ' ') }} $</td>
                    <td>{{ number_format($montantPayes, 0, ',', ' ') }} $</td>
                    <td>{{ $datePaiement }}</td>
                    <td>{{ $percepteurNom }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                @php
                    $sousTotalPayes = $facturesImmeuble->reduce(function($carry, $facture) {
                        return $carry + $facture->paiements->where('est_annule', false)->sum('montant');
                    }, 0);
                @endphp
                <tr class="table-secondary">
                    <th colspan="2">Sous-total immeuble</th>
                    <th>{{ number_format($facturesImmeuble->sum('montant'), 0, ',', ' ') }} $</th>
                    <th>{{ number_format($sousTotalPayes, 0, ',', ' ') }} $</th>
                    <th colspan="2"></th>
                </tr>
            </tfoot>
        </table>
    </div>
@endforeach



@php
    $totalFactures = $factures->sum('montant');
    $totalPayes = $factures->reduce(function($carry, $facture) {
        return $carry + $facture->paiements->where('est_annule', false)->sum('montant');
    }, 0);
    $resteAPayer = $totalFactures - $totalPayes;
@endphp
<div class="row mt-4">
    <div class="col-md-6">
        <div class="card mb-3">
            <div class="card-body">
                <h5 class="card-title">Montant encaissé par percepteur</h5>
                <ul class="list-group list-group-flush">
                    @foreach($percepteurs as $percepteur)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>{{ $percepteur->nom }}</span>
                            <span class="fw-bold text-success">
                                {{ number_format($percepteur->total_encaisse ?? 0, 0, ',', ' ') }} $
                            </span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Statistiques du mois</h5>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">Total à facturer : <strong>{{ number_format($totalFactures, 0, ',', ' ') }} $</strong></li>
                    <li class="list-group-item">Montant payé : <strong>{{ number_format($totalPayes, 0, ',', ' ') }} $</strong></li>
                    <li class="list-group-item">Reste à payer : <strong>{{ number_format($resteAPayer, 0, ',', ' ') }} $</strong></li>
                    @if(request('percepteur'))
                        <li class="list-group-item text-success">Total payé chez le percepteur : <strong>{{ number_format($totalPayesPercepteur, 0, ',', ' ') }} $</strong></li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
