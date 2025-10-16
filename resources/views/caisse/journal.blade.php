@extends('layouts.app')

@section('title', 'Journal de Caisse')

@section('content')
<div class="container-fluid">
    <!-- Modal Transfert de fonds -->
    <div class="modal fade" id="transfertModal" tabindex="-1" aria-labelledby="transfertModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="{{ route('caisse.transfert') }}">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="transfertModalLabel">
                            <i class="bi bi-arrow-left-right"></i> Transfert de fonds
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="compte_source_id" class="form-label">Compte source</label>
                            <select class="form-select" id="compte_source_id" name="compte_source_id" required>
                                <option value="">Sélectionner le compte source</option>
                                @foreach($comptes as $compte)
                                    <option value="{{ $compte->id }}">{{ $compte->nom_compte }} ({{ ucfirst($compte->type) }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="compte_destination_id" class="form-label">Compte destination</label>
                            <select class="form-select" id="compte_destination_id" name="compte_destination_id" required>
                                <option value="">Sélectionner le compte destination</option>
                                @foreach($comptes as $compte)
                                    <option value="{{ $compte->id }}">{{ $compte->nom_compte }} ({{ ucfirst($compte->type) }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="montant" class="form-label">Montant à transférer</label>
                            <div class="input-group">
                                <input type="number" class="form-control" id="montant" name="montant" min="1" step="0.01" required>
                                <span class="input-group-text" id="soldeSourceAffiche" style="min-width:120px;">Solde: -- $</span>
                            </div>
                        </div>
                        <!-- Champ motif supprimé, description obligatoire ci-dessous -->
                        <div class="mb-3">
                            <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="description" name="description" required placeholder="Description du transfert">
                        </div>
                        <input type="hidden" id="date_operation" name="date_operation" value="{{ date('Y-m-d') }}">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary">Valider le transfert</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Navigation par onglets -->
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h1 class="h3 mb-0">Gestion de la Caisse</h1>
            </div>
            
            <!-- Onglets de navigation -->
            <ul class="nav nav-tabs mb-4" id="caisseTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link" href="{{ route('caisse.index') }}">
                        <i class="bi bi-speedometer2"></i> Tableau de Bord
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link active" href="{{ route('caisse.journal') }}">
                        <i class="bi bi-journal-text"></i> Journal de Caisse
                    </a>
                </li>
            </ul>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Filtres et actions -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card mb-2">
                <div class="card-body py-2">
                    <form method="GET" action="{{ route('caisse.journal') }}" class="row g-2 align-items-end flex-wrap">
                        <div class="col-12 col-md-2">
                            <label for="compte_id" class="form-label">Compte</label>
                            <select class="form-select form-select-sm" id="compte_id" name="compte_id">
                                <option value="">Tous les comptes</option>
                                @foreach($comptes as $compte)
                                    <option value="{{ $compte->id }}" {{ request('compte_id') == $compte->id ? 'selected' : '' }}>
                                        {{ $compte->nom_compte }} ({{ ucfirst($compte->type) }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 col-md-2">
                            <label for="type_mouvement" class="form-label">Type</label>
                            <select class="form-select form-select-sm" id="type_mouvement" name="type_mouvement">
                                <option value="">Tous les types</option>
                                <option value="entree" {{ request('type_mouvement') == 'entree' ? 'selected' : '' }}>Entrée</option>
                                <option value="sortie" {{ request('type_mouvement') == 'sortie' ? 'selected' : '' }}>Sortie</option>
                                <option value="transfert" {{ request('type_mouvement') == 'transfert' ? 'selected' : '' }}>Transfert</option>
                            </select>
                        </div>
                        <div class="col-12 col-md-2">
                            <label for="date_debut" class="form-label">Date début</label>
                            <input type="date" class="form-control form-control-sm" id="date_debut" name="date_debut" value="{{ request('date_debut') }}">
                        </div>
                        <div class="col-12 col-md-2">
                            <label for="date_fin" class="form-label">Date fin</label>
                            <input type="date" class="form-control form-control-sm" id="date_fin" name="date_fin" value="{{ request('date_fin') }}">
                        </div>
                        <div class="col-12 col-md-2">
                            <label class="form-label d-none d-md-block">&nbsp;</label>
                            <button type="submit" class="btn btn-primary btn-sm w-100">
                                <i class="bi bi-search"></i> Filtrer
                            </button>
                        </div>
                        <div class="col-12 col-md-2">
                            <label class="form-label d-none d-md-block">&nbsp;</label>
                            <button type="button" class="btn btn-outline-primary btn-sm w-100" data-bs-toggle="modal" data-bs-target="#transfertModal">
                                <i class="bi bi-arrow-left-right"></i> Transfert de fonds
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Résumé des mouvements -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <h6 class="card-title">Total Entrées</h6>
                    <h4 class="mb-0">{{ number_format($statistiques['total_entrees'], 0, ',', ' ') }} $</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-danger">
                <div class="card-body">
                    <h6 class="card-title">Total Sorties</h6>
                    <h4 class="mb-0">{{ number_format($statistiques['total_sorties'], 0, ',', ' ') }} $</h4>
                </div>
            </div>
        </div>
        <!-- Carte Total Transferts supprimée -->
        <div class="col-md-3">
            <div class="card text-white bg-primary">
                <div class="card-body">
                    <h6 class="card-title">Solde Net</h6>
                    <h4 class="mb-0">{{ number_format($statistiques['solde_net'], 0, ',', ' ') }} $</h4>
                </div>
            </div>
        </div>
    </div>

    <!-- Journal des mouvements -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Journal des Mouvements</h5>
        </div>
        <div class="card-body">
            @if($mouvements->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>Date</th>
                                <th>Compte</th>
                                <th>Type</th>
                                <th>Description</th>
                                <th>Référence</th>
                                <th class="text-end">Montant</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($mouvements as $mouvement)
                                <tr>
                                    <td>{{ $mouvement->created_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        @php
                                            $compte = $mouvement->type_mouvement == 'entree' ? $mouvement->compteDestination : $mouvement->compteSource;
                                        @endphp
                                        @if($compte)
                                            <span class="badge bg-secondary">{{ $compte->nom_compte }}</span>
                                            <small class="text-muted d-block">{{ $compte->type ? ucfirst($compte->type) : '' }}</small>
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($mouvement->type_mouvement == 'entree')
                                            <span class="badge bg-success">Entrée</span>
                                        @elseif($mouvement->type_mouvement == 'sortie')
                                            <span class="badge bg-danger">Sortie</span>
                                        @else
                                            <span class="badge bg-info">Transfert</span>
                                        @endif
                                    </td>
                                    <td>{{ $mouvement->description }}</td>
                                    <td>
                                        @if($mouvement->reference)
                                            <code>{{ $mouvement->reference }}</code>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        @if($mouvement->type_mouvement == 'entree')
                                            <span class="text-success">+{{ number_format($mouvement->montant, 0, ',', ' ') }} $</span>
                                        @else
                                            <span class="text-danger">-{{ number_format($mouvement->montant, 0, ',', ' ') }} $</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div>
                        <small class="text-muted">
                            Affichage de {{ $mouvements->firstItem() }} à {{ $mouvements->lastItem() }} 
                            sur {{ $mouvements->total() }} mouvements
                        </small>
                    </div>
                    {{ $mouvements->appends(request()->query())->links('vendor.pagination.custom') }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-journal-x display-1 text-muted"></i>
                    <h5 class="mt-3">Aucun mouvement trouvé</h5>
                    <p class="text-muted">Aucun mouvement ne correspond aux critères sélectionnés.</p>
                    @can('admin')
                    <a href="{{ route('caisse.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> Créer le premier mouvement
                    </a>
                    @endcan
                </div>
            @endif
        </div>
    </div>
</div>

@can('admin')
<!-- Modal de transfert -->
<div class="modal fade" id="transfertModal" tabindex="-1" aria-labelledby="transfertModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="transfertModalLabel">Effectuer un Transfert</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('caisse.transfert.execute') }}">
                @csrf
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="compte_source" class="form-label">Compte Source</label>
                            <select class="form-select" id="compte_source" name="compte_source_id" required>
                                <option value="">Sélectionnez le compte source</option>
                                @foreach($comptes as $compte)
                                    <option value="{{ $compte->id }}">
                                        {{ $compte->nom }} ({{ number_format($compte->solde_actuel, 0, ',', ' ') }} $)
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="compte_destination" class="form-label">Compte Destination</label>
                            <select class="form-select" id="compte_destination" name="compte_destination_id" required>
                                <option value="">Sélectionnez le compte destination</option>
                                @foreach($comptes as $compte)
                                    <option value="{{ $compte->id }}">
                                        {{ $compte->nom }} ({{ ucfirst($compte->type) }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="montant" class="form-label">Montant ($)</label>
                            <div class="input-group">
                                <input type="number" class="form-control" id="montant" name="montant" step="0.01" min="0.01" required>
                                <span class="input-group-text" id="soldeSourceAffiche" style="min-width:120px;">Solde: -- $</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="reference" class="form-label">Référence</label>
                            <input type="text" class="form-control" id="reference" name="reference" 
                                   placeholder="Ex: TRF-{{ date('Ymd') }}-001">
                        </div>
                        <div class="col-12">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3" 
                                      placeholder="Motif du transfert..." required></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-arrow-left-right"></i> Effectuer le Transfert
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endcan

@endsection

@push('scripts')
<script>
// Activation des tooltips
document.addEventListener('DOMContentLoaded', function() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});

    // Validation du formulaire de transfert
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('transfertModal');
        modal.addEventListener('show.bs.modal', function() {
            const form = modal.querySelector('form');
            const compteSource = form.querySelector('#compte_source');
            const compteDestination = form.querySelector('#compte_destination');
            const montantInput = form.querySelector('#montant');
            const soldeSourceAffiche = form.querySelector('#soldeSourceAffiche');

            function updateDestinationOptions() {
                const sourceValue = compteSource.value;
                const destinationOptions = compteDestination.querySelectorAll('option');
                destinationOptions.forEach(option => {
                    option.disabled = (option.value === sourceValue && option.value !== '');
                });
            }
            function updateSourceOptions() {
                const destinationValue = compteDestination.value;
                const sourceOptions = compteSource.querySelectorAll('option');
                sourceOptions.forEach(option => {
                    option.disabled = (option.value === destinationValue && option.value !== '');
                });
            }
            function updateMontantMax() {
                let selectedOption = compteSource.options[compteSource.selectedIndex];
                if (selectedOption && selectedOption.value !== '') {
                    let soldeMatch = selectedOption.text.match(/\(([0-9\s.,]+)\s*\$\)/);
                    if (soldeMatch) {
                        let soldeStr = soldeMatch[1].replace(/\s/g, '').replace(/\./g, '').replace(/,/g, '.');
                        let solde = parseFloat(soldeStr);
                        if (!isNaN(solde)) {
                            montantInput.max = solde;
                        } else {
                            montantInput.max = '';
                        }
                    } else {
                        montantInput.max = '';
                    }
                } else {
                    montantInput.max = '';
                }
            }
            function updateSoldeAffiche() {
                let selectedOption = compteSource.options[compteSource.selectedIndex];
                if (selectedOption && selectedOption.value !== '') {
                    let soldeMatch = selectedOption.text.match(/\(([0-9\s.,]+)\s*\$\)/);
                    if (soldeMatch) {
                        let solde = soldeMatch[1].trim();
                        soldeSourceAffiche.textContent = 'Solde: ' + solde + ' $';
                    } else {
                        soldeSourceAffiche.textContent = 'Solde: -- $';
                    }
                } else {
                    soldeSourceAffiche.textContent = 'Solde: -- $';
                }
            }
            compteSource.addEventListener('change', function() {
                updateDestinationOptions();
                updateMontantMax();
                updateSoldeAffiche();
            });
            compteDestination.addEventListener('change', function() {
                updateSourceOptions();
            });
            // Initialiser au chargement du modal
            updateMontantMax();
            updateSoldeAffiche();
        });
    });
</script>
@endpush