@extends('layouts.app')

@section('title', 'Gestion des Appartements')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">Gestion des Appartements</h1>
                <a href="{{ route('appartements.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Nouvel Appartement
                </a>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Liste des Appartements</h5>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('appartements.index') }}" class="row g-3 mb-3">
                        <div class="col-md-3">
                            <input type="text" name="immeuble" class="form-control" placeholder="Nom de l'immeuble" value="{{ request('immeuble') }}">
                        </div>
                        <div class="col-md-3">
                            <input type="text" name="numero" class="form-control" placeholder="Numéro d'appartement" value="{{ request('numero') }}">
                        </div>
                        <div class="col-md-2">
                            <select name="statut" class="form-select">
                                <option value="">Tous les statuts</option>
                                <option value="libre" {{ request('statut') == 'libre' ? 'selected' : '' }}>Libre</option>
                                <option value="occupe" {{ request('statut') == 'occupe' ? 'selected' : '' }}>Occupé</option>
                                <option value="preavis" {{ request('statut') == 'preavis' ? 'selected' : '' }}>Préavis</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-outline-primary w-100">
                                <i class="bi bi-search"></i> Rechercher
                            </button>
                        </div>
                    </form>
                    @if($appartements->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Immeuble</th>
                                        <th>Numéro</th>
                                        <th>Type</th>
                                        <th>Statut</th>
                                        <th>
                                            @php
                                                $currentSort = request('sort');
                                                $currentDir = request('direction') === 'asc' ? 'asc' : 'desc';
                                                $nextDir = ($currentSort === 'factures_impayees' && $currentDir === 'asc') ? 'desc' : 'asc';
                                                $isActiveSort = $currentSort === 'factures_impayees';
                                            @endphp
                                            <a href="{{ route('appartements.index', array_merge(request()->query(), ['sort' => 'factures_impayees', 'direction' => $nextDir])) }}" class="text-white text-decoration-none {{ $isActiveSort ? 'active-sort-link' : '' }}">
                                                Factures impayées
                                            </a>
                                        </th>
                                        <th>Garantie</th>
                                        <th>Loyer</th>
                                        <th>Locataire</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($appartements as $appartement)
                                        <tr>
                                            <td>{{ $appartement->immeuble->nom ?? 'N/A' }}</td>
                                            <td><strong>{{ $appartement->numero }}</strong></td>
                                            <td>{{ ucfirst($appartement->type) }}</td>
                                            <td>
                                                @php
                                                    // Déterminer les loyers actifs : date_debut <= now AND (date_fin is null OR date_fin >= now)
                                                    // Filtrer les loyers actifs de façon robuste :
                                                    // - date_debut doit exister et être <= today
                                                    // - date_fin peut être NULL/empty/0000-00-00 => considéré indéterminé (actif)
                                                    $activeLeases = $appartement->loyers->filter(function($l) {
                                                        // respecter le champ statut ('actif'|'inactif')
                                                        if (!isset($l->statut) || $l->statut !== 'actif') return false;

                                                        $debutRaw = isset($l->date_debut) ? trim((string)$l->date_debut) : '';
                                                        if ($debutRaw === '') return false;
                                                        try {
                                                            $debut = \Carbon\Carbon::parse($debutRaw);
                                                        } catch (\Exception $e) {
                                                            return false;
                                                        }

                                                        $finRaw = isset($l->date_fin) ? trim((string)$l->date_fin) : '';
                                                        // traiter valeurs vides ou 0000-00-00 comme NULL
                                                        if ($finRaw === '' || in_array($finRaw, ['0000-00-00', '0000-00-00 00:00:00'])) {
                                                            $fin = null;
                                                        } else {
                                                            try {
                                                                $fin = \Carbon\Carbon::parse($finRaw);
                                                            } catch (\Exception $e) {
                                                                $fin = null;
                                                            }
                                                        }

                                                        return $debut->lte(now()) && (is_null($fin) || $fin->gte(now()));
                                                    });

                                                    if ($activeLeases->isEmpty()) {
                                                        $status = 'libre';
                                                    } else {
                                                        // Si un des loyers actifs a date_fin null => occupé
                                                        if ($activeLeases->contains(function($l) { return is_null($l->date_fin); })) {
                                                            $status = 'occupe';
                                                        } else {
                                                            $maxDateFin = $activeLeases->pluck('date_fin')->max();
                                                            if (\Carbon\Carbon::parse($maxDateFin)->lte(now()->addMonths(3))) {
                                                                $status = 'preavis';
                                                            } else {
                                                                $status = 'occupe';
                                                            }
                                                        }
                                                    }
                                                @endphp
                                                @if($status == 'libre')
                                                    <span class="badge bg-danger">Libre</span>
                                                @elseif($status == 'occupe')
                                                    <span class="badge bg-success">Occupé</span>
                                                @else
                                                    <span class="badge bg-warning">Préavis</span>
                                                @endif
                                            </td>
                                            <td>
                                                @php
                                                    $badgeCount = $appartement->factures_impayees_count ?? 0;
                                                @endphp
                                                <a href="{{ route('factures.index', array_merge(request()->query(), ['appartement_id' => $appartement->id, 'impayees' => 1])) }}" class="text-decoration-none">
                                                    <span class="badge bg-danger">{{ $badgeCount }}</span>
                                                </a>
                                                @if(request()->has('debug') || app()->environment('local'))
                                                    <div class="mt-1 small text-muted">
                                                        Loyers total: {{ $appartement->loyers->count() }}, actifs: {{ $activeLeases->count() }}
                                                        <ul class="mb-0">
                                                            @foreach($appartement->loyers as $l)
                                                                @php
                                                                    $debutRaw = isset($l->date_debut) ? trim((string)$l->date_debut) : '';
                                                                    try { $debut = $debutRaw === '' ? null : \Carbon\Carbon::parse($debutRaw); } catch (\Exception $e) { $debut = null; }
                                                                    $finRaw = isset($l->date_fin) ? trim((string)$l->date_fin) : '';
                                                                    if ($finRaw === '' || in_array($finRaw, ['0000-00-00', '0000-00-00 00:00:00'])) {
                                                                        $fin = null;
                                                                    } else {
                                                                        try { $fin = \Carbon\Carbon::parse($finRaw); } catch (\Exception $e) { $fin = null; }
                                                                    }
                                                                    $isActive = ($l->statut ?? '') === 'actif' && $debut && $debut->lte(now()) && (is_null($fin) || $fin->gte(now()));
                                                                @endphp
                                                                <li>#{{ $l->id }} — statut="{{ $l->statut ?? 'null' }}" debut="{{ $debutRaw ?: 'null' }}" fin="{{ $finRaw ?: 'null' }}" active={{ $isActive ? '1' : '0' }}</li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                @endif
                                            </td>
                                            <td>{{ $appartement->garantie_locative }} $</td>
                                            <td>{{ number_format($appartement->loyer_mensuel, 0, ',', ' ') }} $</td>
                                            <td>
                                                @if($appartement->locataire)
                                                    <span class="badge bg-success">
                                                        {{ $appartement->locataire->nom }} {{ $appartement->locataire->prenom }}
                                                    </span>
                                                @else
                                                    <span class="badge bg-warning">Libre</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('appartements.show', $appartement) }}" class="btn btn-sm btn-outline-info">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    <a href="{{ route('appartements.edit', $appartement) }}" class="btn btn-sm btn-outline-warning">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div>
                                <p class="mb-0 text-muted">Affichage {{ $appartements->firstItem() }} - {{ $appartements->lastItem() }} sur {{ $appartements->total() }} appartements</p>
                            </div>
                            <div>
                                <style>
                                    /* Supprimer visuellement les SVG du paginator et rendre la pagination responsive */
                                    .pagination svg,
                                    .pagination .w-5,
                                    .pagination .h-5,
                                    .pagination .page-link svg,
                                    .pagination .page-link .fa,
                                    .pagination .page-link .sr-only {
                                        display: none !important;
                                    }
                                    .pagination {
                                        display: inline-flex;
                                        flex-wrap: wrap;
                                        gap: .25rem;
                                        font-size: .9rem;
                                    }
                                    @media (max-width: 576px) {
                                        .pagination {
                                            overflow-x: auto;
                                            -webkit-overflow-scrolling: touch;
                                            white-space: nowrap;
                                        }
                                        .pagination li { white-space: nowrap; }
                                    }
                                </style>
                                {{-- Rendre les liens de pagination sans les icônes SVG --}}
                                {{ $appartements->appends(request()->query())->links('vendor.pagination.custom') }}
                            </div>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="bi bi-house display-1 text-muted"></i>
                            <p class="text-muted mt-3">Aucun appartement enregistré</p>
                            <a href="{{ route('appartements.create') }}" class="btn btn-primary">
                                Créer le premier appartement
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection