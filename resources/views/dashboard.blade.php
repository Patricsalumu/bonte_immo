@extends('layouts.app')

@section('title', 'Tableau de bord - La Bonte Immo')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h1 class="h3 mb-3">
            <i class="bi bi-speedometer2"></i>
            Tableau de bord
        </h1>
    </div>
</div>

<!-- Statistiques principales -->
<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="stats-card p-4 text-center">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h3 class="text-primary mb-1">{{ $totalAppartements }}</h3>
                    <p class="text-muted mb-0">Total Appartements</p>
                </div>
                <i class="bi bi-house-door text-primary" style="font-size: 2.5rem;"></i>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-3">
        <div class="stats-card p-4 text-center">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h3 class="text-success mb-1">{{ $appartementsOccupes }}</h3>
                    <p class="text-muted mb-0">Occupés</p>
                </div>
                <i class="bi bi-check-circle text-success" style="font-size: 2.5rem;"></i>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-3">
        <div class="stats-card p-4 text-center">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h3 class="text-info mb-1">{{ $appartementsLibres }}</h3>
                    <p class="text-muted mb-0">Libres</p>
                </div>
                <i class="bi bi-door-open text-info" style="font-size: 2.5rem;"></i>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-3">
        <div class="stats-card p-4 text-center">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h3 class="text-success mb-1">{{ number_format($recettesMois, 0, ',', ' ') }} CDF</h3>
                    <p class="text-muted mb-0">Recettes du Mois</p>
                </div>
                <i class="bi bi-currency-dollar text-success" style="font-size: 2.5rem;"></i>
            </div>
        </div>
    </div>
</div>

<!-- Contrats de loyer -->
<div class="row mb-4">
    <div class="col-md-6 mb-3">
        <div class="stats-card p-4 text-center">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h3 class="text-success mb-1">{{ $contratsActifs }}</h3>
                    <p class="text-muted mb-0">Contrats Actifs</p>
                </div>
                <i class="fas fa-file-contract text-success" style="font-size: 2.5rem;"></i>
            </div>
        </div>
    </div>
    
    <div class="col-md-6 mb-3">
        <div class="stats-card p-4 text-center">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h3 class="text-secondary mb-1">{{ $contratsInactifs }}</h3>
                    <p class="text-muted mb-0">Contrats Inactifs</p>
                </div>
                <i class="fas fa-file-contract text-secondary" style="font-size: 2.5rem;"></i>
            </div>
        </div>
    </div>
</div>

<!-- Factures impayées et Paiements récents -->
<div class="row mb-4">
    <!-- Factures impayées -->
    <div class="col-md-6 mb-3">
        <div class="card stats-card">
            <div class="card-header bg-warning text-white">
                <h5 class="mb-0">
                    <i class="bi bi-exclamation-triangle"></i>
                    Factures Impayées ({{ $facturesImpayees }})
                </h5>
            </div>
            <div class="card-body">
                @if($facturesImpayees > 0)
                    <p class="text-warning mb-3">{{ $facturesImpayees }} facture(s) non payée(s) ce mois.</p>
                    <a href="{{ route('factures.impayees') }}" class="btn btn-warning btn-custom">
                        <i class="bi bi-eye"></i> Voir les détails
                    </a>
                @else
                    <p class="text-success mb-0">
                        <i class="bi bi-check-circle"></i>
                        Toutes les factures du mois sont payées !
                    </p>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Paiements récents -->
    <div class="col-md-6 mb-3">
        <div class="card stats-card">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0">
                    <i class="bi bi-clock-history"></i>
                    Paiements Récents
                </h5>
            </div>
            <div class="card-body">
                @if($paiementsRecents->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($paiementsRecents->take(5) as $paiement)
                        <div class="list-group-item border-0 px-0">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1">{{ $paiement->locataire->nom }}</h6>
                                    <small class="text-muted">
                                        {{ $paiement->loyer->appartement->numero ?? 'N/A' }} - 
                                        {{ number_format($paiement->montant, 0, ',', ' ') }} CDF
                                    </small>
                                </div>
                                <small class="text-muted">{{ $paiement->date_paiement->format('d/m') }}</small>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <a href="{{ route('paiements.index') }}" class="btn btn-info btn-custom btn-sm mt-2">
                        Voir tous les paiements
                    </a>
                @else
                    <p class="text-muted mb-0">Aucun paiement récent.</p>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Graphiques -->
<div class="row mb-4">
    <div class="col-md-8 mb-3">
        <div class="card stats-card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-bar-chart"></i>
                    Évolution des Loyers (6 derniers mois)
                </h5>
            </div>
            <div class="card-body">
                <canvas id="loyersChart" height="300"></canvas>
            </div>
        </div>
    </div>
    
    <!-- Garanties locatives -->
    <div class="col-md-4 mb-3">
        <div class="card stats-card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-shield-check"></i>
                    Garanties Locatives
                </h5>
            </div>
            <div class="card-body">
                @if($contratsAvecGarantie->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($contratsAvecGarantie->take(5) as $contrat)
                        <div class="list-group-item border-0 px-0">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="mb-1">{{ $contrat['nom'] }}</h6>
                                    <small class="text-muted">Apt. {{ $contrat['appartement'] }}</small>
                                </div>
                                <div class="text-end">
                                    <span class="badge bg-primary">
                                        {{ number_format($contrat['garantie_restante'], 0, ',', ' ') }} CDF
                                    </span>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted mb-0">Aucune garantie locative enregistrée.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Graphique des loyers
    const ctx = document.getElementById('loyersChart').getContext('2d');
    const chartData = @json($graphiqueData);
    
    const loyersChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: chartData.map(item => item.mois),
            datasets: [
                {
                    label: 'Loyers Payés',
                    data: chartData.map(item => item.payes),
                    backgroundColor: 'rgba(40, 167, 69, 0.8)',
                    borderColor: 'rgba(40, 167, 69, 1)',
                    borderWidth: 1
                },
                {
                    label: 'Loyers Impayés',
                    data: chartData.map(item => item.impayes),
                    backgroundColor: 'rgba(220, 53, 69, 0.8)',
                    borderColor: 'rgba(220, 53, 69, 1)',
                    borderWidth: 1
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return new Intl.NumberFormat('fr-FR').format(value) + ' CDF';
                        }
                    }
                }
            },
            plugins: {
                legend: {
                    position: 'top',
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.dataset.label + ': ' + 
                                   new Intl.NumberFormat('fr-FR').format(context.parsed.y) + ' CDF';
                        }
                    }
                }
            }
        }
    });
</script>
@endsection