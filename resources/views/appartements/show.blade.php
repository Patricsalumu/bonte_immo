@extends('layouts.app')

@section('title', 'Détails de l\'Appartement')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">{{ $appartement->immeuble->nom }} - Apt {{ $appartement->numero }}</h1>
    <div>
        <a href="{{ route('appartements.edit', $appartement) }}" class="btn btn-warning">
            <i class="fas fa-edit"></i> Modifier
        </a>
        <a href="{{ route('appartements.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Retour
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Informations de l'appartement</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Immeuble :</strong></td>
                                <td>
                                    <a href="{{ route('immeubles.show', $appartement->immeuble) }}">
                                        {{ $appartement->immeuble->nom }}
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Numéro :</strong></td>
                                <td>{{ $appartement->numero }}</td>
                            </tr>
                            <tr>
                                <td><strong>Type :</strong></td>
                                <td>{{ ucfirst(str_replace('_', ' ', $appartement->type)) }}</td>
                            </tr>
                            <tr>
                                <td><strong>Superficie :</strong></td>
                                <td>{{ $appartement->superficie }} m²</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Loyer mensuel :</strong></td>
                                <td><span class="text-success fw-bold">{{ number_format($appartement->loyer_mensuel, 0, ',', ' ') }} CDF</span></td>
                            </tr>
                            <tr>
                                <td><strong>Garantie locative :</strong></td>
                                <td>{{ number_format($appartement->garantie_locative, 0, ',', ' ') }} CDF</td>
                            </tr>
                            <tr>
                                <td><strong>Statut :</strong></td>
                                <td>
                                    @if($appartement->locataire)
                                        <span class="badge bg-warning">Occupé</span>
                                    @else
                                        <span class="badge bg-success">Libre</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Actif :</strong></td>
                                <td>
                                    @if($appartement->actif)
                                        <span class="badge bg-success">Oui</span>
                                    @else
                                        <span class="badge bg-danger">Non</span>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                
                @if($appartement->description)
                <div class="mt-3">
                    <strong>Description :</strong>
                    <p class="mt-2">{{ $appartement->description }}</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Locataire actuel -->
        @if($appartement->locataire)
        <div class="card mt-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Locataire actuel</h5>
                <a href="{{ route('locataires.show', $appartement->locataire) }}" class="btn btn-sm btn-outline-primary">
                    Voir le profil
                </a>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Nom :</strong></td>
                                <td>{{ $appartement->locataire->nom }} {{ $appartement->locataire->prenom }}</td>
                            </tr>
                            <tr>
                                <td><strong>Téléphone :</strong></td>
                                <td>{{ $appartement->locataire->telephone }}</td>
                            </tr>
                            <tr>
                                <td><strong>Email :</strong></td>
                                <td>{{ $appartement->locataire->email ?? 'Non renseigné' }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Profession :</strong></td>
                                <td>{{ $appartement->locataire->profession ?? 'Non renseignée' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Revenu mensuel :</strong></td>
                                <td>
                                    @if($appartement->locataire->revenu_mensuel)
                                        {{ number_format($appartement->locataire->revenu_mensuel, 0, ',', ' ') }} CDF
                                    @else
                                        Non renseigné
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Statut :</strong></td>
                                <td>
                                    @if($appartement->locataire->actif)
                                        <span class="badge bg-success">Actif</span>
                                    @else
                                        <span class="badge bg-danger">Inactif</span>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Historique des loyers -->
        <div class="card mt-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Historique des loyers</h5>
                @if($appartement->locataire)
                <a href="{{ route('loyers.create', ['appartement_id' => $appartement->id]) }}" class="btn btn-sm btn-primary">
                    <i class="fas fa-plus"></i> Nouveau loyer
                </a>
                @endif
            </div>
            <div class="card-body">
                @php
                    $factures = $appartement->loyers->flatMap->factures->sortByDesc('created_at');
                @endphp
                @if($factures->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Période</th>
                                    <th>Montant</th>
                                    <th>Statut</th>
                                    <th>Montant Payé</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($factures->take(10) as $facture)
                                <tr>
                                    <td>{{ $facture->created_at->format('d/m/Y') }}</td>
                                    <td>{{ str_pad($facture->mois, 2, '0', STR_PAD_LEFT) }}/{{ $facture->annee }}</td>
                                    <td>{{ number_format($facture->montant, 0, ',', ' ') }} $</td>
                                    <td>
                                        @if($facture->statut_paiement === 'paye')
                                            <span class="badge bg-success">Payée</span>
                                        @elseif($facture->statut_paiement === 'paye_en_retard')
                                            <span class="badge bg-success">Payée en retard</span>

                                        @elseif($facture->statut_paiement === 'partielle')
                                            <span class="badge bg-warning">Partielle</span>
                                        @else
                                            <span class="badge bg-danger">Non payée</span>
                                        @endif
                                    </td>
                                    <td>{{ number_format($facture->paiements->sum('montant'), 0, ',', ' ') }} $</td>
                                    <td>
                                        <a href="{{ route('factures.show', $facture) }}" class="btn btn-sm btn-outline-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if($appartement->loyers->count() > 10)
                    <div class="text-center mt-3">
                        <a href="{{ route('loyers.index', ['appartement' => $appartement->id]) }}" class="btn btn-outline-primary">
                            Voir tous les loyers
                        </a>
                    </div>
                    @endif
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-receipt fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Aucun loyer enregistré pour cet appartement</p>
                        @if($appartement->locataire)
                        <a href="{{ route('loyers.create', ['appartement_id' => $appartement->id]) }}" class="btn btn-primary">
                            Créer le premier loyer
                        </a>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <!-- Statistiques -->
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Résumé financier</h6>
            </div>
            <div class="card-body">
                @php
                    $totalPaye = 0;
                    $totalDu = 0;
                    $factures = $appartement->loyers->flatMap->factures;
                    $totalPaye = $factures->flatMap->paiements->sum('montant');
                    $totalDu = $factures->count() * $appartement->loyer_mensuel;
                @endphp
                <div class="row text-center">
                    <div class="col-12 mb-3">
                        <div class="border rounded p-3 bg-light">
                            <h4 class="text-success mb-0">{{ number_format($appartement->loyer_mensuel, 0, ',', ' ') }}</h4>
                            <small class="text-muted">$/mois</small>
                        </div>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="border rounded p-3">
                            <h5 class="text-primary mb-0">{{ number_format($totalPaye, 0, ',', ' ') }}</h5>
                            <small class="text-muted">Total Payé</small>
                        </div>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="border rounded p-3">
                            <h5 class="text-danger mb-0">{{ number_format($totalDu, 0, ',', ' ') }}</h5>
                            <small class="text-muted">Total Dû</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions rapides -->
        <div class="card mt-3">
            <div class="card-header">
                <h6 class="mb-0">Actions rapides</h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('appartements.edit', $appartement) }}" class="btn btn-outline-warning">
                        <i class="fas fa-edit"></i> Modifier l'appartement
                    </a>
                    @if($appartement->locataire)
                    <a href="{{ route('loyers.create', ['appartement_id' => $appartement->id]) }}" class="btn btn-outline-primary">
                        <i class="fas fa-plus"></i> Nouveau loyer
                    </a>
                    <a href="{{ route('locataires.show', $appartement->locataire) }}" class="btn btn-outline-info">
                        <i class="fas fa-user"></i> Voir le locataire
                    </a>
                    @else
                    <a href="{{ route('locataires.create', ['appartement_id' => $appartement->id]) }}" class="btn btn-outline-success">
                        <i class="fas fa-user-plus"></i> Assigner locataire
                    </a>
                    @endif
                    <a href="{{ route('immeubles.show', $appartement->immeuble) }}" class="btn btn-outline-secondary">
                        <i class="fas fa-building"></i> Voir l'immeuble
                    </a>
                </div>
            </div>
        </div>

        <!-- Informations techniques -->
        <div class="card mt-3 bg-light">
            <div class="card-body">
                <h6 class="card-title">
                    <i class="fas fa-info-circle"></i> Informations
                </h6>
                <ul class="small text-muted mb-0">
                    <li><strong>Créé le :</strong> {{ $appartement->created_at->format('d/m/Y') }}</li>
                    <li><strong>Modifié le :</strong> {{ $appartement->updated_at->format('d/m/Y') }}</li>
                    @if($appartement->loyers)
                    <li><strong>Nombre de loyers :</strong> {{ $appartement->loyers->count() }}</li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection