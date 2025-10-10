

<?php $__env->startSection('title', 'Factures et Paiements'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Factures et Paiements</h1>
</div>

<?php if(session('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?php echo e(session('success')); ?>

        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if(session('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?php echo e(session('error')); ?>

        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<!-- Statistiques rapides -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">En attente</h6>
                        <h4><?php echo e($loyers->where('statut', 'en_attente')->count()); ?></h4>
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
                        <h4><?php echo e($loyers->where('statut', 'en_retard')->count()); ?></h4>
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
                        <h4><?php echo e($loyers->where('statut', 'paye')->count()); ?></h4>
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
                        <h4><?php echo e(number_format($loyers->sum('montant'), 0, ',', ' ')); ?> CDF</h4>
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
        <?php if($loyers->count() > 0): ?>
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
                        <?php $__currentLoopData = $loyers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $loyer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr class="<?php echo e($loyer->statut == 'en_retard' ? 'table-danger' : ($loyer->statut == 'paye' ? 'table-success' : '')); ?>">
                            <td>
                                <strong>#<?php echo e(str_pad($loyer->id, 6, '0', STR_PAD_LEFT)); ?></strong>
                            </td>
                            <td>
                                <div>
                                    <strong><?php echo e($loyer->locataire->nom); ?> <?php echo e($loyer->locataire->prenom); ?></strong>
                                    <br>
                                    <small class="text-muted"><?php echo e($loyer->locataire->telephone); ?></small>
                                </div>
                            </td>
                            <td>
                                <div>
                                    <strong><?php echo e($loyer->appartement->immeuble->nom); ?></strong>
                                    <br>
                                    <small class="text-muted">Apt <?php echo e($loyer->appartement->numero); ?></small>
                                </div>
                            </td>
                            <td>
                                <strong><?php echo e(number_format($loyer->montant, 0, ',', ' ')); ?> CDF</strong>
                            </td>
                            <td>
                                <?php if($loyer->statut == 'paye'): ?>
                                    <span class="badge bg-success">Payé</span>
                                <?php elseif($loyer->statut == 'en_retard'): ?>
                                    <span class="badge bg-danger">En retard</span>
                                <?php else: ?>
                                    <span class="badge bg-warning">En attente</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <?php if($loyer->statut != 'paye'): ?>
                                        <button type="button" 
                                                class="btn btn-success btn-sm" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#modalPaiement<?php echo e($loyer->id); ?>">
                                            <i class="fas fa-credit-card"></i> Régler Facture
                                        </button>
                                    <?php endif; ?>
                                    
                                    <a href="<?php echo e(route('loyers.show', $loyer)); ?>" 
                                       class="btn btn-outline-info btn-sm">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </div>

                                <!-- Modal de paiement -->
                                <?php if($loyer->statut != 'paye'): ?>
                                <div class="modal fade" id="modalPaiement<?php echo e($loyer->id); ?>" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Régler la facture #<?php echo e(str_pad($loyer->id, 6, '0', STR_PAD_LEFT)); ?></h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <form method="POST" action="<?php echo e(route('loyers.marquer-paye', $loyer)); ?>">
                                                <?php echo csrf_field(); ?>
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label class="form-label">Montant à payer</label>
                                                        <input type="text" class="form-control" 
                                                               value="<?php echo e(number_format($loyer->montant, 0, ',', ' ')); ?> CDF" 
                                                               readonly>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="mode_paiement<?php echo e($loyer->id); ?>" class="form-label">Mode de paiement *</label>
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
                                                               value="<?php echo e(date('Y-m-d')); ?>" 
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
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="text-center py-5">
                <i class="fas fa-file-invoice fa-4x text-muted mb-3"></i>
                <h4 class="text-muted">Aucune facture trouvée</h4>
                <p class="text-muted">Aucune facture disponible pour le moment.</p>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>
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
                                    <?php $__currentLoopData = $paiements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $paiement): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><?php echo e(\Carbon\Carbon::parse($paiement->date_paiement)->format('d/m/Y')); ?></td>
                                            <td>
                                                <?php if($paiement->loyer && $paiement->loyer->locataire): ?>
                                                    <?php echo e($paiement->loyer->locataire->nom); ?> <?php echo e($paiement->loyer->locataire->prenom); ?>

                                                <?php else: ?>
                                                    <span class="text-muted">N/A</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if($paiement->loyer && $paiement->loyer->appartement): ?>
                                                    <?php echo e($paiement->loyer->appartement->immeuble->nom ?? 'N/A'); ?> - 
                                                    App. <?php echo e($paiement->loyer->appartement->numero); ?>

                                                <?php else: ?>
                                                    <span class="text-muted">N/A</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if($paiement->loyer): ?>
                                                    <?php echo e(str_pad($paiement->loyer->mois, 2, '0', STR_PAD_LEFT)); ?>/<?php echo e($paiement->loyer->annee); ?>

                                                <?php else: ?>
                                                    <span class="text-muted">N/A</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <strong><?php echo e(number_format($paiement->montant, 0, ',', ' ')); ?> FC</strong>
                                            </td>
                                            <td>
                                                <?php switch($paiement->mode_paiement):
                                                    case ('especes'): ?>
                                                        <span class="badge bg-success">Espèces</span>
                                                        <?php break; ?>
                                                    <?php case ('cheque'): ?>
                                                        <span class="badge bg-info">Chèque</span>
                                                        <?php break; ?>
                                                    <?php case ('virement'): ?>
                                                        <span class="badge bg-primary">Virement</span>
                                                        <?php break; ?>
                                                    <?php case ('mobile_money'): ?>
                                                        <span class="badge bg-warning">Mobile Money</span>
                                                        <?php break; ?>
                                                    <?php default: ?>
                                                        <span class="badge bg-secondary"><?php echo e(ucfirst($paiement->mode_paiement)); ?></span>
                                                <?php endswitch; ?>
                                            </td>
                                            <td><?php echo e($paiement->reference ?? '-'); ?></td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="<?php echo e(route('paiements.show', $paiement)); ?>" class="btn btn-sm btn-outline-info">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    <a href="<?php echo e(route('paiements.edit', $paiement)); ?>" class="btn btn-sm btn-outline-warning">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-4">
                            <i class="bi bi-credit-card display-1 text-muted"></i>
                            <p class="text-muted mt-3">Aucun paiement enregistré</p>
                            <a href="<?php echo e(route('paiements.create')); ?>" class="btn btn-primary">
                                Enregistrer le premier paiement
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\immo\resources\views/paiements/index.blade.php ENDPATH**/ ?>