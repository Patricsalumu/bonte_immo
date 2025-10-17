@extends('layouts.app')

@section('title', 'Tableau de bord - ' . config('company.name'))

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
                    <h3 class="text-success mb-1">{{ number_format($recettesMois, 0, ',', ' ') }} $</h3>
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
                    Factures Impayées ({{ $facturesImpayees }} $)
                </h5>
            </div>
            <div class="card-body">
                @if($facturesImpayees > 0)
                    <p class="text-warning mb-3">{{ number_format($facturesImpayees, 0, ',', ' ') }} $ facture(s) non payée(s) ce mois.</p>
                    <a href="#" class="btn btn-warning btn-custom">
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
</div>
@endsection

@section('scripts')
<script id="graphique-data" type="application/json">{!! json_encode($graphiqueData) !!}</script>
<script>
    // Graphique des loyers
    const ctx = document.getElementById('loyersChart').getContext('2d');
    const chartData = JSON.parse(document.getElementById('graphique-data').textContent);

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
                            return new Intl.NumberFormat('fr-FR').format(value) + ' $';
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
                                   new Intl.NumberFormat('fr-FR').format(context.parsed.y) + ' $';
                        }
                    }
                }
            }
        }
    });
</script>
@endsection