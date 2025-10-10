@extends('layouts.app')

@section('title', 'Factures et Paiements')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Factures et Paiements</h1>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<!-- Statistiques rapides -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">En attente</h6>
                        <h4>{{ $loyers->where('statut', 'en_attente')->count() }}</h4>
                    </div>
                    <i class="fas fa-clock fa-2x"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-danger text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">En retard</h6>
                        <h4>{{ $loyers->where('statut', 'en_retard')->count() }}</h4>
                    </div>
                    <i class="fas fa-exclamation-triangle fa-2x"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">Payés ce mois</h6>
                        <h4>{{ $loyers->where('statut', 'paye')->count() }}</h4>
                    </div>
                    <i class="fas fa-check-circle fa-2x"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">Montant total</h6>
                        <h4>{{ number_format($loyers->sum('montant'), 0, ',', ' ') }} CDF</h4>
                    </div>
                    <i class="fas fa-dollar-sign fa-2x"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Liste des factures -->
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">
            <i class="fas fa-file-invoice-dollar"></i> Factures de Loyer
        </h5>
    </div>
    <div class="card-body">
        @if($loyers->count() > 0)
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Facture #</th>
                            <th>Locataire</th>
                            <th>Appartement</th>
                            <th>Montant</th>
                            <th>Statut</th>
                            <th width="200">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($loyers as $loyer)
                        <tr class="{{ $loyer->statut == 'en_retard' ? 'table-danger' : ($loyer->statut == 'paye' ? 'table-success' : '') }}">
                            <td>
                                <strong>#{{ str_pad($loyer->id, 6, '0', STR_PAD_LEFT) }}</strong>
                            </td>
                            <td>
                                <div>
                                    <strong>{{ $loyer->locataire->nom }} {{ $loyer->locataire->prenom }}</strong>
                                    <br>
                                    <small class="text-muted">{{ $loyer->locataire->telephone }}</small>
                                </div>
                            </td>
                            <td>
                                <div>
                                    <strong>{{ $loyer->appartement->immeuble->nom }}</strong>
                                    <br>
                                    <small class="text-muted">Apt {{ $loyer->appartement->numero }}</small>
                                </div>
                            </td>
                            <td>
                                <strong>{{ number_format($loyer->montant, 0, ',', ' ') }} CDF</strong>
                            </td>
                            <td>
                                @if($loyer->statut == 'paye')
                                    <span class="badge bg-success">Payé</span>
                                @elseif($loyer->statut == 'en_retard')
                                    <span class="badge bg-danger">En retard</span>
                                @else
                                    <span class="badge bg-warning">En attente</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    @if($loyer->statut != 'paye')
                                        <button type="button" 
                                                class="btn btn-success btn-sm" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#modalPaiement{{ $loyer->id }}">
                                            <i class="fas fa-credit-card"></i> Régler Facture
                                        </button>
                                    @endif
                                    
                                    <a href="{{ route('loyers.show', $loyer) }}" 
                                       class="btn btn-outline-info btn-sm">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </div>

                                <!-- Modal de paiement -->
                                @if($loyer->statut != 'paye')
                                <div class="modal fade" id="modalPaiement{{ $loyer->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Régler la facture #{{ str_pad($loyer->id, 6, '0', STR_PAD_LEFT) }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <form method="POST" action="{{ route('loyers.marquer-paye', $loyer) }}">
                                                @csrf
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label class="form-label">Montant à payer</label>
                                                        <input type="text" class="form-control" 
                                                               value="{{ number_format($loyer->montant, 0, ',', ' ') }} CDF" 
                                                               readonly>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="mode_paiement{{ $loyer->id }}" class="form-label">Mode de paiement *</label>
                                                        <select name="mode_paiement" class="form-select" required>
                                                            <option value="">Sélectionner...</option>
                                                            <option value="especes">Espèces</option>
                                                            <option value="virement">Virement bancaire</option>
                                                            <option value="mobile_money">Mobile Money</option>
                                                        </select>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Date de paiement *</label>
                                                        <input type="date" 
                                                               name="date_paiement" 
                                                               class="form-control" 
                                                               value="{{ date('Y-m-d') }}" 
                                                               required>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                                    <button type="submit" class="btn btn-success">
                                                        <i class="fas fa-check"></i> Confirmer le paiement
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-file-invoice fa-4x text-muted mb-3"></i>
                <h4 class="text-muted">Aucune facture trouvée</h4>
                <p class="text-muted">Aucune facture disponible pour le moment.</p>
            </div>
        @endif
    </div>
</div>
@endsection
                                <thead class="table-dark">
                                    <tr>
                                        <th>Date</th>
                                        <th>Locataire</th>
                                        <th>Appartement</th>
                                        <th>Période</th>
                                        <th>Montant</th>
                                        <th>Mode</th>
                                        <th>Référence</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($paiements as $paiement)
                                        <tr>
                                            <td>{{ \Carbon\Carbon::parse($paiement->date_paiement)->format('d/m/Y') }}</td>
                                            <td>
                                                @if($paiement->loyer && $paiement->loyer->locataire)
                                                    {{ $paiement->loyer->locataire->nom }} {{ $paiement->loyer->locataire->prenom }}
                                                @else
                                                    <span class="text-muted">N/A</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($paiement->loyer && $paiement->loyer->appartement)
                                                    {{ $paiement->loyer->appartement->immeuble->nom ?? 'N/A' }} - 
                                                    App. {{ $paiement->loyer->appartement->numero }}
                                                @else
                                                    <span class="text-muted">N/A</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($paiement->loyer)
                                                    {{ str_pad($paiement->loyer->mois, 2, '0', STR_PAD_LEFT) }}/{{ $paiement->loyer->annee }}
                                                @else
                                                    <span class="text-muted">N/A</span>
                                                @endif
                                            </td>
                                            <td>
                                                <strong>{{ number_format($paiement->montant, 0, ',', ' ') }} FC</strong>
                                            </td>
                                            <td>
                                                @switch($paiement->mode_paiement)
                                                    @case('especes')
                                                        <span class="badge bg-success">Espèces</span>
                                                        @break
                                                    @case('cheque')
                                                        <span class="badge bg-info">Chèque</span>
                                                        @break
                                                    @case('virement')
                                                        <span class="badge bg-primary">Virement</span>
                                                        @break
                                                    @case('mobile_money')
                                                        <span class="badge bg-warning">Mobile Money</span>
                                                        @break
                                                    @default
                                                        <span class="badge bg-secondary">{{ ucfirst($paiement->mode_paiement) }}</span>
                                                @endswitch
                                            </td>
                                            <td>{{ $paiement->reference ?? '-' }}</td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('paiements.show', $paiement) }}" class="btn btn-sm btn-outline-info">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    <a href="{{ route('paiements.edit', $paiement) }}" class="btn btn-sm btn-outline-warning">
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
                            <i class="bi bi-credit-card display-1 text-muted"></i>
                            <p class="text-muted mt-3">Aucun paiement enregistré</p>
                            <a href="{{ route('paiements.create') }}" class="btn btn-primary">
                                Enregistrer le premier paiement
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection