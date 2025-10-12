@extends('layouts.app')

@section('title', 'Détail de la facture')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Facture #{{ $facture->id }}</h4>
                    <a href="{{ route('factures.export-pdf', $facture) }}" class="btn btn-outline-danger">
                        <i class="bi bi-file-pdf"></i> Télécharger PDF
                    </a>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <td><strong>Période :</strong></td>
                            <td>{{ str_pad($facture->mois, 2, '0', STR_PAD_LEFT) }}/{{ $facture->annee }}</td>
                        </tr>
                        <tr>
                            <td><strong>Montant :</strong></td>
                            <td>{{ number_format($facture->montant, 0, ',', ' ') }} FC</td>
                        </tr>
                        <tr>
                            <td><strong>Statut :</strong></td>
                            <td>
                                @php
                                    $montantPaye = $facture->paiements->sum('montant');
                                @endphp
                                @if($montantPaye >= $facture->montant)
                                    <span class="badge bg-success">Payée</span>
                                @elseif($montantPaye > 0)
                                    <span class="badge bg-warning">Partielle</span>
                                @else
                                    <span class="badge bg-danger">Non payée</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Date de création :</strong></td>
                            <td>{{ $facture->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                        <tr>
                            <td><strong>Appartement :</strong></td>
                            <td>
                                @if($facture->loyer && $facture->loyer->appartement)
                                    <a href="{{ route('appartements.show', $facture->loyer->appartement) }}">
                                        {{ $facture->loyer->appartement->numero }} - {{ $facture->loyer->appartement->immeuble->nom ?? '' }}
                                    </a>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Locataire :</strong></td>
                            <td>
                                @if($facture->loyer && $facture->loyer->locataire)
                                    <a href="{{ route('locataires.show', $facture->loyer->locataire) }}">
                                        {{ $facture->loyer->locataire->nom }} {{ $facture->loyer->locataire->prenom }}
                                    </a>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- Paiements associés en bas, pleine largeur -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Paiements associés</h6>
                </div>
                <div class="card-body">
                    @if($facture->paiements && $facture->paiements->count() > 0)
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Montant</th>
                                    <th>Mode</th>
                                    <th>Référence</th>
                                    <th>Notes</th>
                                    <th>Utilisateur</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($facture->paiements as $paiement)
                                <tr>
                                    <td>{{ $paiement->date_paiement ? $paiement->date_paiement->format('d/m/Y') : '-' }}</td>
                                    <td>{{ number_format($paiement->montant, 0, ',', ' ') }} FC</td>
                                    <td>{{ ucfirst($paiement->mode_paiement) }}</td>
                                    <td>{{ $paiement->reference ?? '-' }}</td>
                                    <td>{{ $paiement->notes ?? '-' }}</td>
                                    <td>{{ $paiement->utilisateur ? ($paiement->utilisateur->nom ?? $paiement->utilisateur->name ?? '-') : '-' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="text-muted">Aucun paiement enregistré pour cette facture.</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection