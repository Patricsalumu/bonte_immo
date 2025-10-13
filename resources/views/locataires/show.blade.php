@extends('layouts.app')

@section('title', 'Profil du Locataire')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">{{ $locataire->nom }} {{ $locataire->prenom }}</h1>
    <div>
        <a href="{{ route('locataires.edit', $locataire) }}" class="btn btn-warning">
            <i class="fas fa-edit"></i> Modifier
        </a>
        <a href="{{ route('locataires.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Retour
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Informations personnelles</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Nom complet :</strong></td>
                                <td>{{ $locataire->nom }} {{ $locataire->prenom }}</td>
                            </tr>
                            <tr>
                                <td><strong>Date de naissance :</strong></td>
                                <td>
                                    @if($locataire->date_naissance)
                                        {{ \Carbon\Carbon::parse($locataire->date_naissance)->format('d/m/Y') }}
                                        ({{ \Carbon\Carbon::parse($locataire->date_naissance)->age }} ans)
                                    @else
                                        Non renseignée
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Téléphone :</strong></td>
                                <td>{{ $locataire->telephone }}</td>
                            </tr>
                            <tr>
                                <td><strong>Email :</strong></td>
                                <td>{{ $locataire->email ?? 'Non renseigné' }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Adresse :</strong></td>
                                <td>{{ $locataire->adresse ?? 'Non renseignée' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Carte d'identité :</strong></td>
                                <td>{{ $locataire->numero_carte_identite ?? 'Non renseigné' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Statut :</strong></td>
                                <td>
                                    @if($locataire->actif)
                                        <span class="badge bg-success">Actif</span>
                                    @else
                                        <span class="badge bg-danger">Inactif</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Membre depuis :</strong></td>
                                <td>{{ $locataire->created_at->format('d/m/Y') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Informations professionnelles -->
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0">Informations professionnelles</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Profession :</strong></td>
                                <td>{{ $locataire->profession ?? 'Non renseignée' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Employeur :</strong></td>
                                <td>{{ $locataire->employeur ?? 'Non renseigné' }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Revenu mensuel :</strong></td>
                                <td>
                                    @if($locataire->revenu_mensuel)
                                        {{ number_format($locataire->revenu_mensuel, 0, ',', ' ') }} $
                                    @else
                                        Non renseigné
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact d'urgence -->
        @if($locataire->contact_urgence_nom || $locataire->contact_urgence_telephone)
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0">Contact d'urgence</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Nom :</strong></td>
                                <td>{{ $locataire->contact_urgence_nom ?? 'Non renseigné' }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Téléphone :</strong></td>
                                <td>{{ $locataire->contact_urgence_telephone ?? 'Non renseigné' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Appartement actuel -->
        @if($locataire->appartement)
        <div class="card mt-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Appartement actuel</h5>
                <a href="{{ route('appartements.show', $locataire->appartement) }}" class="btn btn-sm btn-outline-primary">
                    Voir l'appartement
                </a>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Immeuble :</strong></td>
                                <td>
                                    <a href="{{ route('immeubles.show', $locataire->appartement->immeuble) }}">
                                        {{ $locataire->appartement->immeuble->nom }}
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Appartement :</strong></td>
                                <td>{{ $locataire->appartement->numero }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Type :</strong></td>
                                <td>{{ ucfirst(str_replace('_', ' ', $locataire->appartement->type)) }}</td>
                            </tr>
                            <tr>
                                <td><strong>Loyer mensuel :</strong></td>
                                <td><span class="fw-bold text-success">{{ number_format($locataire->appartement->loyer_mensuel, 0, ',', ' ') }} $</span></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Historique des paiements -->
        <div class="card mt-4">
            <div class="card-body">
                @php
                    $paiements = $locataire->paiements()->with(['facture', 'utilisateur'])->orderByDesc('date_paiement')->get();
                @endphp
                @if($paiements->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Motif</th>
                                    <th>Utilisateur</th>
                                    <th>Notes</th>
                                    <th>Référence</th>
                                    <th>Montant</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($paiements as $paiement)
                                <tr>
                                    <td>{{ $paiement->date_paiement ? $paiement->date_paiement->format('d/m/Y') : '-' }}</td>
                                    <td>
                                        @if($paiement->facture)
                                            Loyer {{ str_pad($paiement->facture->mois, 2, '0', STR_PAD_LEFT) }}/{{ $paiement->facture->annee }}
                                        @else
                                            {{ ucfirst($paiement->mode_paiement) }}
                                        @endif
                                    </td>
                                    <td>{{ $paiement->utilisateur ? ($paiement->utilisateur->nom ?? $paiement->utilisateur->name ?? '-') : '-' }}</td>
                                    <td>{{ $paiement->notes ?? '-' }}</td>
                                    <td>{{ $paiement->reference_paiement ?? '-' }}</td>
                                    <td>{{ number_format($paiement->montant, 0, ',', ' ') }} FC</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-money-bill fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Aucun paiement enregistré pour ce locataire</p>
                        <a href="{{ route('paiements.create', ['locataire_id' => $locataire->id]) }}" class="btn btn-success">
                            Enregistrer le premier paiement
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Notes -->
        @if($locataire->notes)
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0">Notes</h5>
            </div>
            <div class="card-body">
                <p>{{ $locataire->notes }}</p>
            </div>
        </div>
        @endif
    </div>

    <div class="col-md-4">
        <!-- Résumé financier -->
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Résumé financier</h6>
            </div>
            <div class="card-body">
                @php
                    $totalPaiements = $locataire->paiements()->where('est_annule', false)->sum('montant');
                    $impayes = $locataire->factures()->where('statut_paiement', 'non_paye')->sum('montant');
                @endphp
                <div class="row text-center">
                    <div class="col-6 mb-3">
                        <div class="border rounded p-3">
                            <h5 class="text-success mb-0">{{ number_format($totalPaiements, 0, ',', ' ') }}</h5>
                            <small class="text-muted">Total payé</small>
                        </div>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="border rounded p-3">
                            <h5 class="text-danger mb-0">{{ number_format($impayes, 0, ',', ' ') }}</h5>
                            <small class="text-muted">Impayés</small>
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
                    <a href="{{ route('locataires.edit', $locataire) }}" class="btn btn-outline-warning">
                        <i class="fas fa-edit"></i> Modifier le profil
                    </a>
                    @if($locataire->appartement)
                    <a href="{{ route('loyers.create', ['locataire_id' => $locataire->id]) }}" class="btn btn-outline-primary">
                        <i class="fas fa-plus"></i> Nouveau loyer
                    </a>
                    <a href="{{ route('appartements.show', $locataire->appartement) }}" class="btn btn-outline-info">
                        <i class="fas fa-home"></i> Voir l'appartement
                    </a>
                    @else
                    <a href="{{ route('appartements.index') }}" class="btn btn-outline-success">
                        <i class="fas fa-home"></i> Assigner appartement
                    </a>
                    @endif
                    <a href="{{ route('paiements.create', ['locataire_id' => $locataire->id]) }}" class="btn btn-outline-success">
                        <i class="fas fa-money-bill"></i> Enregistrer paiement
                    </a>
                </div>
            </div>
        </div>

        <!-- Solvabilité -->
        @if($locataire->revenu_mensuel)
        <div class="card mt-3 bg-light">
            <div class="card-body">
                <h6 class="card-title">
                    <i class="fas fa-chart-line"></i> Évaluation financière
                </h6>
                <div class="row">
                    <div class="col-12 mb-2">
                        <small class="text-muted">Capacité de paiement estimée :</small>
                        <div class="fw-bold">{{ number_format($locataire->revenu_mensuel * 0.33, 0, ',', ' ') }} $</div>
                    </div>
                    @if($loyerMensuel > 0)
                    <div class="col-12 mb-2">
                        <small class="text-muted">Reste à vivre :</small>
                        <div class="fw-bold">{{ number_format($locataire->revenu_mensuel - $loyerMensuel, 0, ',', ' ') }} $</div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @endif

        <!-- Informations système -->
        <div class="card mt-3 bg-light">
            <div class="card-body">
                <h6 class="card-title">
                    <i class="fas fa-info-circle"></i> Informations
                </h6>
                <ul class="small text-muted mb-0">
                    <li><strong>Créé le :</strong> {{ $locataire->created_at->format('d/m/Y H:i') }}</li>
                    <li><strong>Modifié le :</strong> {{ $locataire->updated_at->format('d/m/Y H:i') }}</li>
                    @if($locataire->loyers)
                    <li><strong>Nombre de loyers :</strong> {{ $locataire->loyers->count() }}</li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection