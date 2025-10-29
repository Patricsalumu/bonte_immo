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
                        <h3 class="text-success mb-1">{{ number_format($recettesTotales, 0, ',', ' ') }} $</h3>
                        <p class="text-muted mb-0">Recettes totales</p>
                        <p class="text-muted small mb-0">Recettes du mois : {{ number_format($recettesMois, 0, ',', ' ') }} $</p>
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
                    Factures Impayées
                </h5>
            </div>
            <div class="card-body">
                @if($facturesImpayeesCountAll > 0)
                    <p class="text-warning mb-2">Total factures impayées (toutes périodes) : <strong>{{ number_format($facturesImpayeesCountAll, 0, ',', ' ') }}</strong></p>
                    <p class="text-warning mb-3">Montant restant total : <strong>{{ number_format($facturesImpayeesAmountAll, 0, ',', ' ') }} $</strong></p>
                    <a href="{{ route('rapports.index') }}" class="btn btn-warning btn-custom">
                        <i class="bi bi-eye"></i> Voir les factures impayées
                    </a>
                    <a href="{{ route('rapports.export', ['type' => 'factures_impayees', 'format' => 'pdf']) }}" class="btn btn-outline-light btn-custom ms-2">
                        <i class="bi bi-download"></i> Export PDF
                    </a>
                @else
                    <p class="text-success mb-0">
                        <i class="bi bi-check-circle"></i>
                        Il n'y a pas de factures impayées.
                    </p>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Graphiques -->
<div class="row mb-4">
    <div class="col-12 mb-3">
        <div class="card stats-card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-bar-chart"></i>
                    Évolution des Loyers (12 derniers mois)
                </h5>
            </div>
            <div class="card-body">
                <canvas id="loyersChart" height="400"></canvas>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script id="graphique-data" type="application/json">{!! json_encode($graphiqueData) !!}</script>
<script>
    // Graphique des loyers + étiquettes de pourcentage au-dessus de chaque barre
    const ctx = document.getElementById('loyersChart').getContext('2d');
    const chartData = JSON.parse(document.getElementById('graphique-data').textContent);

    // Plugin local Chart.js pour dessiner les pourcentages (payé / impayé) au-dessus des barres
    const percentPlugin = {
        id: 'percentPlugin',
        afterDatasetsDraw(chart, args, options) {
            const { ctx, chartArea } = chart;
            ctx.save();
            ctx.font = '12px sans-serif';
            ctx.textAlign = 'center';
            ctx.textBaseline = 'bottom';

            const datasets = chart.data.datasets;
            // Pour chaque index (mois), calculer total et pourcentages
            const length = datasets[0].data.length;
            for (let i = 0; i < length; i++) {
                const value0 = Number(datasets[0].data[i] || 0);
                const value1 = Number(datasets[1].data[i] || 0);
                const total = value0 + value1;

                // éviter division par zéro
                const pct0 = total > 0 ? Math.round((value0 / total) * 100) : 0;
                const pct1 = total > 0 ? Math.round((value1 / total) * 100) : 0;

                // Récupérer les objets bar pour chaque dataset afin d'obtenir position
                const meta0 = chart.getDatasetMeta(0);
                const meta1 = chart.getDatasetMeta(1);
                const bar0 = meta0.data[i];
                const bar1 = meta1.data[i];

                if (bar0) {
                    const x = bar0.x;
                    // placer petit peu au-dessus du sommet de la barre
                    const y = bar0.y - 6;
                    // si la barre est suffisamment haute, écrire en blanc, sinon en noir
                    const height0 = Math.abs(bar0.base - bar0.y) || 0;
                    ctx.fillStyle = height0 > 18 ? '#ffffff' : '#000000';
                    ctx.fillText(pct0 + '%', x, y);
                }

                if (bar1) {
                    const x = bar1.x;
                    const y = bar1.y - 6;
                    const height1 = Math.abs(bar1.base - bar1.y) || 0;
                    ctx.fillStyle = height1 > 18 ? '#ffffff' : '#000000';
                    ctx.fillText(pct1 + '%', x, y);
                }
            }

            ctx.restore();
        }
    };

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
                    backgroundColor: 'rgba(0,0,0,0.85)',
                    titleColor: '#ffffff',
                    bodyColor: '#ffffff',
                    callbacks: {
                        label: function(context) {
                            // valeur courante
                            const idx = context.dataIndex;
                            const datasets = context.chart.data.datasets;
                            const value = Number(context.parsed.y || 0);
                            // autre dataset (payé vs impayé)
                            const otherIdx = context.datasetIndex === 0 ? 1 : 0;
                            const otherValue = Number(datasets[otherIdx].data[idx] || 0);
                            const total = value + otherValue;
                            const pct = total > 0 ? Math.round((value / total) * 100) : 0;
                            return context.dataset.label + ': ' + new Intl.NumberFormat('fr-FR').format(value) + ' $ (' + pct + '%)';
                        }
                    }
                }
            }
        },
        plugins: [percentPlugin]
    });
</script>
@endsection