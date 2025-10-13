@extends('layouts.app')

@section('title', 'Rapports et Statistiques')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">
        <i class="fas fa-chart-bar"></i> Rapports et Statistiques
    </h1>
    <div class="d-flex gap-2">
        <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalRapportMensuel">
            <i class="fas fa-calendar-alt"></i> Rapport Mensuel
        </button>
        <div class="dropdown">
            <button class="btn btn-success dropdown-toggle" type="button" data-bs-toggle="dropdown">
                <i class="fas fa-download"></i> Exporter
            </button>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="{{ route('rapports.export', ['format' => 'pdf']) }}">
                    <i class="fas fa-file-pdf"></i> PDF
                </a></li>
                <li><a class="dropdown-item" href="{{ route('rapports.export', ['format' => 'excel']) }}">
                    <i class="fas fa-file-excel"></i> Excel
                </a></li>
            </ul>
        </div>
    </div>
</div>

<!-- Statistiques générales -->
<div class="row mb-4">
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">Total Immeubles</h6>
                        <h3 class="mb-0">{{ $stats['total_immeubles'] ?? 0 }}</h3>
                        <small>{{ $stats['immeubles_actifs'] ?? 0 }} actifs</small>
                    </div>
                    <i class="fas fa-building fa-2x opacity-75"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">Total Appartements</h6>
                        <h3 class="mb-0">{{ $stats['total_appartements'] ?? 0 }}</h3>
                        <small>{{ $stats['appartements_disponibles'] ?? 0 }} disponibles</small>
                    </div>
                    <i class="fas fa-home fa-2x opacity-75"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">Total Locataires</h6>
                        <h3 class="mb-0">{{ $stats['total_locataires'] ?? 0 }}</h3>
                        <small>{{ $stats['locataires_actifs'] ?? 0 }} actifs</small>
                    </div>
                    <i class="fas fa-users fa-2x opacity-75"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">Revenus Mensuels</h6>
                        <h3 class="mb-0">{{ number_format($stats['revenus_mensuels'] ?? 0, 0, ',', ' ') }} $</h3>
                        <small>Ce mois</small>
                    </div>
                    <i class="fas fa-dollar-sign fa-2x opacity-75"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Graphiques et tableaux -->
<div class="row">
    <div class="col-lg-8 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-chart-line"></i> Évolution des Revenus (12 derniers mois)
                </h5>
            </div>
            <div class="card-body">
                <canvas id="revenusChart" height="100"></canvas>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-chart-pie"></i> Répartition Occupancy
                </h5>
            </div>
            <div class="card-body">
                <canvas id="occupancyChart" height="150"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-exclamation-triangle"></i> Retards de Paiement
                </h5>
            </div>
            <div class="card-body">
                @if(!empty($retards))
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Locataire</th>
                                    <th>Appartement</th>
                                    <th>Montant</th>
                                    <th>Retard</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($retards as $retard)
                                <tr>
                                    <td>{{ $retard->locataire->nom ?? 'N/A' }}</td>
                                    <td>{{ $retard->appartement->numero ?? 'N/A' }}</td>
                                    <td>{{ number_format($retard->montant ?? 0, 0, ',', ' ') }} $</td>
                                    <td>
                                        <span class="badge bg-danger">
                                            {{ $retard->jours_retard ?? 0 }} jours
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center text-muted">
                        <i class="fas fa-check-circle fa-3x mb-2"></i>
                        <p>Aucun retard de paiement !</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-star"></i> Top 5 Immeubles (Revenus)
                </h5>
            </div>
            <div class="card-body">
                @if(!empty($top_immeubles))
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Immeuble</th>
                                    <th>Appartements</th>
                                    <th>Revenus</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($top_immeubles as $immeuble)
                                <tr>
                                    <td>{{ $immeuble->nom ?? 'N/A' }}</td>
                                    <td>{{ $immeuble->appartements_count ?? 0 }}</td>
                                    <td>
                                        <strong>{{ number_format($immeuble->revenus ?? 0, 0, ',', ' ') }} $</strong>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center text-muted">
                        <i class="fas fa-chart-bar fa-3x mb-2"></i>
                        <p>Aucune donnée disponible</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Modal Rapport Mensuel -->
<div class="modal fade" id="modalRapportMensuel" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Générer un Rapport Mensuel</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="GET" action="{{ route('rapports.mensuel') }}">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="mois" class="form-label">Mois</label>
                            <select name="mois" id="mois" class="form-select" required>
                                @for($i = 1; $i <= 12; $i++)
                                    <option value="{{ $i }}" {{ date('n') == $i ? 'selected' : '' }}>
                                        {{ DateTime::createFromFormat('!m', $i)->format('F') }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="annee" class="form-label">Année</label>
                            <select name="annee" id="annee" class="form-select" required>
                                @for($i = date('Y'); $i >= date('Y') - 5; $i--)
                                    <option value="{{ $i }}" {{ date('Y') == $i ? 'selected' : '' }}>
                                        {{ $i }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="format" class="form-label">Format</label>
                        <select name="format" id="format" class="form-select">
                            <option value="pdf">PDF</option>
                            <option value="excel">Excel</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-download"></i> Générer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Graphique des revenus
    const revenusCtx = document.getElementById('revenusChart').getContext('2d');
    new Chart(revenusCtx, {
        type: 'line',
        data: {
            labels: @json($chartData['revenus']['labels'] ?? []),
            datasets: [{
                label: 'Revenus ($)',
                data: @json($chartData['revenus']['data'] ?? []),
                borderColor: 'rgb(75, 192, 192)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                tension: 0.1,
                fill: true
            }]
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
            }
        }
    });

    // Graphique d'occupation
    const occupancyCtx = document.getElementById('occupancyChart').getContext('2d');
    new Chart(occupancyCtx, {
        type: 'doughnut',
        data: {
            labels: ['Occupés', 'Libres'],
            datasets: [{
                data: [
                    {{ $stats['appartements_occupes'] ?? 0 }}, 
                    {{ $stats['appartements_disponibles'] ?? 0 }}
                ],
                backgroundColor: [
                    'rgba(40, 167, 69, 0.8)',
                    'rgba(220, 53, 69, 0.8)'
                ],
                borderColor: [
                    'rgba(40, 167, 69, 1)',
                    'rgba(220, 53, 69, 1)'
                ],
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
});
</script>
@endsection