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
                    <!-- Bouton générer facture pour le contrat en cours -->
                    @php
                        // Chercher un loyer 'actif' sinon prendre le plus récent comme fallback
                        $contratActif = $appartement->loyers->firstWhere('statut', 'actif')
                            ?? $appartement->loyers->sortByDesc('created_at')->first();
                    @endphp
                    @if($contratActif)
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#genererFactureLoyerModal"
                        data-loyer-id="{{ $contratActif->id }}"
                        data-montant="{{ $contratActif->montant ?? $contratActif->montant_mensuel ?? $appartement->loyer_mensuel }}">
                        <i class="fas fa-file-invoice"></i> Générer facture (contrat)
                    </button>
                    @endif
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('genererFactureLoyerModal');
    if (!modal) return;

    // Préremplir mois/année (mois précédent suggéré)
    const moisSelect = modal.querySelector('#gen_mois');
    const anneeSelect = modal.querySelector('#gen_annee');
    const dateEcheance = modal.querySelector('#gen_date_echeance');
    const verifierBtn = modal.querySelector('#gen_verifier_loyer');
    const genererBtn = modal.querySelector('#gen_generer_loyer');
    const resultat = modal.querySelector('#gen_resultat');

    // Quand le modal s'ouvre, récupérer info depuis le bouton qui a déclenché l'ouverture
    modal.addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;
        const loyerId = button ? button.getAttribute('data-loyer-id') : '';
        const montant = button ? button.getAttribute('data-montant') : '';

        // stocker dans dataset du modal pour usage par les handlers
        modal.dataset.loyerId = loyerId || '';
        modal.dataset.montant = montant || '';
    });

    const maintenant = new Date();
    let moisPrev = maintenant.getMonth();
    if (moisPrev === 0) moisPrev = 12; // janvier -> 12
    moisSelect.value = moisPrev;
    anneeSelect.value = maintenant.getFullYear();

    verifierBtn.addEventListener('click', function() {
        resultat.className = 'alert alert-info';
        resultat.textContent = 'Vérification en cours...';

    const loyerId = modal.dataset.loyerId || '';
    fetch('/factures/verifier-doublons-loyer', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ loyer_id: loyerId, mois: moisSelect.value, annee: anneeSelect.value })
        })
        .then(r => r.json())
        .then(data => {
            if (data.exists) {
                resultat.className = 'alert alert-warning';
                resultat.textContent = 'Une facture existe déjà pour ce contrat et cette période.';
                genererBtn.disabled = true;
            } else {
                resultat.className = 'alert alert-success';
                resultat.textContent = 'Aucune facture trouvée. Vous pouvez générer une nouvelle facture.';
                genererBtn.disabled = false;
            }
        })
        .catch(() => {
            resultat.className = 'alert alert-danger';
            resultat.textContent = 'Erreur lors de la vérification.';
        });
    });

    genererBtn.addEventListener('click', function() {
        genererBtn.disabled = true;
        resultat.className = 'alert alert-info';
        resultat.textContent = 'Génération en cours...';

    const loyerId2 = modal.dataset.loyerId || '';
    fetch('/factures/generer-pour-loyer', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ loyer_id: loyerId2, mois: moisSelect.value, annee: anneeSelect.value, date_echeance: dateEcheance.value })
        })
        .then(response => {
            if (!response.ok) throw response;
            return response.json();
        })
        .then(data => {
            if (data.created) {
                resultat.className = 'alert alert-success';
                resultat.textContent = 'Facture générée. Redirection...';
                window.location.href = '/factures/' + data.facture_id;
            } else {
                resultat.className = 'alert alert-warning';
                resultat.textContent = data.message || 'Impossible de créer la facture.';
                genererBtn.disabled = false;
            }
        })
        .catch(async (err) => {
            let msg = 'Erreur lors de la création.';
            try { const j = await err.json(); msg = j.message || msg; } catch(e){}
            resultat.className = 'alert alert-danger';
            resultat.textContent = msg;
            genererBtn.disabled = false;
        });
    });
});
</script>
@endpush


<!-- Modal génération pour le loyer -->
@if(isset($contratActif) && $contratActif)
<div class="modal fade" id="genererFactureLoyerModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Générer facture pour ce contrat</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Montant</label>
                    <input type="text" class="form-control" value="{{ number_format(optional($contratActif)->montant ?? optional($contratActif)->montant_mensuel ?? $appartement->loyer_mensuel, 0, ',', ' ') }}" readonly>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Mois</label>
                        <select id="gen_mois" class="form-select">
                            @for($m = 1; $m <= 12; $m++)
                                <option value="{{ $m }}">{{ DateTime::createFromFormat('!m', $m)->format('F') }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Année</label>
                        <select id="gen_annee" class="form-select">
                            @for($y = now()->year - 1; $y <= now()->year + 1; $y++)
                                <option value="{{ $y }}" {{ $y == now()->year ? 'selected' : '' }}>{{ $y }}</option>
                            @endfor
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Date d'échéance</label>
                    <input type="date" id="gen_date_echeance" class="form-control" value="{{ now()->addDays(12)->format('Y-m-d') }}">
                </div>

                <div id="gen_resultat" class="d-none"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" id="gen_verifier_loyer" class="btn btn-info">Vérifier doublons</button>
                <button type="button" id="gen_generer_loyer" class="btn btn-primary" disabled>Générer</button>
            </div>
        </div>
    </div>
</div>
@endif