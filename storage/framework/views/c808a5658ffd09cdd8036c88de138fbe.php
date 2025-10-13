

<?php $__env->startSection('title', 'Journal de Caisse'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <!-- Navigation par onglets -->
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h1 class="h3 mb-0">Gestion de la Caisse</h1>
            </div>
            
            <!-- Onglets de navigation -->
            <ul class="nav nav-tabs mb-4" id="caisseTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link" href="<?php echo e(route('caisse.index')); ?>">
                        <i class="bi bi-speedometer2"></i> Tableau de Bord
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" href="<?php echo e(route('comptes-financiers.index')); ?>">
                        <i class="bi bi-bank"></i> Comptes Financiers
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link active" href="<?php echo e(route('caisse.journal')); ?>">
                        <i class="bi bi-journal-text"></i> Journal de Caisse
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Filtres et actions -->
    <div class="row mb-4">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Filtres</h5>
                    <form method="GET" action="<?php echo e(route('caisse.journal')); ?>" class="row g-3">
                        <div class="col-md-3">
                            <label for="compte_id" class="form-label">Compte</label>
                            <select class="form-select" id="compte_id" name="compte_id">
                                <option value="">Tous les comptes</option>
                                <?php $__currentLoopData = $comptes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $compte): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($compte->id); ?>" <?php echo e(request('compte_id') == $compte->id ? 'selected' : ''); ?>>
                                        <?php echo e($compte->nom); ?> (<?php echo e(ucfirst($compte->type)); ?>)
                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="type_mouvement" class="form-label">Type</label>
                            <select class="form-select" id="type_mouvement" name="type_mouvement">
                                <option value="">Tous les types</option>
                                <option value="entree" <?php echo e(request('type_mouvement') == 'entree' ? 'selected' : ''); ?>>Entrée</option>
                                <option value="sortie" <?php echo e(request('type_mouvement') == 'sortie' ? 'selected' : ''); ?>>Sortie</option>
                                <option value="transfert" <?php echo e(request('type_mouvement') == 'transfert' ? 'selected' : ''); ?>>Transfert</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="date_debut" class="form-label">Date début</label>
                            <input type="date" class="form-control" id="date_debut" name="date_debut" value="<?php echo e(request('date_debut')); ?>">
                        </div>
                        <div class="col-md-2">
                            <label for="date_fin" class="form-label">Date fin</label>
                            <input type="date" class="form-control" id="date_fin" name="date_fin" value="<?php echo e(request('date_fin')); ?>">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">&nbsp;</label>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-search"></i> Filtrer
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Actions Rapides</h5>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin')): ?>
                    <div class="d-grid gap-2">
                        <a href="<?php echo e(route('caisse.create')); ?>" class="btn btn-success">
                            <i class="bi bi-plus-circle"></i> Nouveau Mouvement
                        </a>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#transfertModal">
                            <i class="bi bi-arrow-left-right"></i> Effectuer un Transfert
                        </button>
                        <a href="<?php echo e(route('caisse.journal', array_merge(request()->all(), ['export' => 'pdf']))); ?>" class="btn btn-outline-danger">
                            <i class="bi bi-file-pdf"></i> Exporter PDF
                        </a>
                    </div>
                    <?php endif; ?>
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
                    <h4 class="mb-0"><?php echo e(number_format($statistiques['total_entrees'], 0, ',', ' ')); ?> $</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-danger">
                <div class="card-body">
                    <h6 class="card-title">Total Sorties</h6>
                    <h4 class="mb-0"><?php echo e(number_format($statistiques['total_sorties'], 0, ',', ' ')); ?> $</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-info">
                <div class="card-body">
                    <h6 class="card-title">Total Transferts</h6>
                    <h4 class="mb-0"><?php echo e(number_format($statistiques['total_transferts'], 0, ',', ' ')); ?> $</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-primary">
                <div class="card-body">
                    <h6 class="card-title">Solde Net</h6>
                    <h4 class="mb-0"><?php echo e(number_format($statistiques['solde_net'], 0, ',', ' ')); ?> $</h4>
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
            <?php if($mouvements->count() > 0): ?>
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
                                <th class="text-end">Solde</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $mouvements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mouvement): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($mouvement->date_operation->format('d/m/Y H:i')); ?></td>
                                    <td>
                                        <?php
                                            $compte = $mouvement->type_mouvement == 'entree' ? $mouvement->compteDestination : $mouvement->compteSource;
                                        ?>
                                        <?php if($compte): ?>
                                            <span class="badge bg-secondary"><?php echo e($compte->nom); ?></span>
                                            <small class="text-muted d-block"><?php echo e(ucfirst($compte->type)); ?></small>
                                        <?php else: ?>
                                            <span class="text-muted">N/A</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if($mouvement->type_mouvement == 'entree'): ?>
                                            <span class="badge bg-success">Entrée</span>
                                        <?php elseif($mouvement->type_mouvement == 'sortie'): ?>
                                            <span class="badge bg-danger">Sortie</span>
                                        <?php else: ?>
                                            <span class="badge bg-info">Transfert</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo e($mouvement->description); ?></td>
                                    <td>
                                        <?php if($mouvement->reference): ?>
                                            <code><?php echo e($mouvement->reference); ?></code>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-end">
                                        <?php if($mouvement->type_mouvement == 'entree'): ?>
                                            <span class="text-success">+<?php echo e(number_format($mouvement->montant, 0, ',', ' ')); ?> $</span>
                                        <?php else: ?>
                                            <span class="text-danger">-<?php echo e(number_format($mouvement->montant, 0, ',', ' ')); ?> $</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-end">
                                        <?php
                                            $compte = $mouvement->type_mouvement == 'entree' ? $mouvement->compteDestination : $mouvement->compteSource;
                                        ?>
                                        <?php if($compte): ?>
                                            <strong><?php echo e(number_format($compte->solde, 0, ',', ' ')); ?> $</strong>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin')): ?>
                                        <div class="btn-group btn-group-sm">
                                            <button type="button" class="btn btn-outline-info" data-bs-toggle="tooltip" title="Détails">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                            <?php if($mouvement->type_mouvement == 'transfert' && $mouvement->created_at->diffInHours() < 24): ?>
                                                <form method="POST" action="<?php echo e(route('caisse.annuler', $mouvement->id)); ?>" class="d-inline">
                                                    <?php echo csrf_field(); ?>
                                                    <button type="submit" class="btn btn-outline-danger" 
                                                            onclick="return confirm('Êtes-vous sûr de vouloir annuler ce transfert ?')"
                                                            data-bs-toggle="tooltip" title="Annuler">
                                                        <i class="bi bi-x-circle"></i>
                                                    </button>
                                                </form>
                                            <?php endif; ?>
                                        </div>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div>
                        <small class="text-muted">
                            Affichage de <?php echo e($mouvements->firstItem()); ?> à <?php echo e($mouvements->lastItem()); ?> 
                            sur <?php echo e($mouvements->total()); ?> mouvements
                        </small>
                    </div>
                    <?php echo e($mouvements->appends(request()->query())->links()); ?>

                </div>
            <?php else: ?>
                <div class="text-center py-5">
                    <i class="bi bi-journal-x display-1 text-muted"></i>
                    <h5 class="mt-3">Aucun mouvement trouvé</h5>
                    <p class="text-muted">Aucun mouvement ne correspond aux critères sélectionnés.</p>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin')): ?>
                    <a href="<?php echo e(route('caisse.create')); ?>" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> Créer le premier mouvement
                    </a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin')): ?>
<!-- Modal de transfert -->
<div class="modal fade" id="transfertModal" tabindex="-1" aria-labelledby="transfertModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="transfertModalLabel">Effectuer un Transfert</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="<?php echo e(route('caisse.transfert.execute')); ?>">
                <?php echo csrf_field(); ?>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="compte_source" class="form-label">Compte Source</label>
                            <select class="form-select" id="compte_source" name="compte_source" required>
                                <option value="">Sélectionnez le compte source</option>
                                <?php $__currentLoopData = $comptes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $compte): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($compte->id); ?>">
                                        <?php echo e($compte->nom); ?> (<?php echo e(number_format($compte->solde, 0, ',', ' ')); ?> $)
                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="compte_destination" class="form-label">Compte Destination</label>
                            <select class="form-select" id="compte_destination" name="compte_destination" required>
                                <option value="">Sélectionnez le compte destination</option>
                                <?php $__currentLoopData = $comptes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $compte): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($compte->id); ?>">
                                        <?php echo e($compte->nom); ?> (<?php echo e(ucfirst($compte->type)); ?>)
                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="montant" class="form-label">Montant ($)</label>
                            <input type="number" class="form-control" id="montant" name="montant" 
                                   step="0.01" min="0.01" required>
                        </div>
                        <div class="col-md-6">
                            <label for="reference" class="form-label">Référence</label>
                            <input type="text" class="form-control" id="reference" name="reference" 
                                   placeholder="Ex: TRF-<?php echo e(date('Ymd')); ?>-001">
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
<?php endif; ?>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
// Activation des tooltips
document.addEventListener('DOMContentLoaded', function() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});

// Validation du formulaire de transfert
document.getElementById('transfertModal').addEventListener('show.bs.modal', function() {
    const form = this.querySelector('form');
    const compteSource = form.querySelector('#compte_source');
    const compteDestination = form.querySelector('#compte_destination');
    
    // Empêcher la sélection du même compte
    compteSource.addEventListener('change', function() {
        updateDestinationOptions();
    });
    
    compteDestination.addEventListener('change', function() {
        updateSourceOptions();
    });
    
    function updateDestinationOptions() {
        const sourceValue = compteSource.value;
        const destinationOptions = compteDestination.querySelectorAll('option');
        
        destinationOptions.forEach(option => {
            if (option.value === sourceValue && option.value !== '') {
                option.disabled = true;
            } else {
                option.disabled = false;
            }
        });
    }
    
    function updateSourceOptions() {
        const destinationValue = compteDestination.value;
        const sourceOptions = compteSource.querySelectorAll('option');
        
        sourceOptions.forEach(option => {
            if (option.value === destinationValue && option.value !== '') {
                option.disabled = true;
            } else {
                option.disabled = false;
            }
        });
    }
});
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\immo\resources\views/caisse/journal.blade.php ENDPATH**/ ?>