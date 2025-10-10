

<?php $__env->startSection('title', 'Gestion de la Caisse'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">Gestion de la Caisse</h1>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin')): ?>
                <div class="btn-group">
                    <a href="<?php echo e(route('mouvements-caisse.create')); ?>" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> Nouveau Mouvement
                    </a>
                    <a href="<?php echo e(route('mouvements-caisse.transfert')); ?>" class="btn btn-outline-primary">
                        <i class="bi bi-arrow-left-right"></i> Transfert
                    </a>
                </div>
                <?php endif; ?>
            </div>

            <?php if(session('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?php echo e(session('success')); ?>

                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <!-- Résumé des comptes -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="card text-white bg-primary">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h5 class="card-title">Solde Total</h5>
                                    <h2 class="mb-0"><?php echo e(number_format($soldeTotal, 0, ',', ' ')); ?> FC</h2>
                                </div>
                                <div class="align-self-center">
                                    <i class="bi bi-wallet2 display-4"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-white bg-success">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h5 class="card-title">Nombre de Comptes</h5>
                                    <h2 class="mb-0"><?php echo e($comptes->count()); ?></h2>
                                </div>
                                <div class="align-self-center">
                                    <i class="bi bi-bank display-4"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-white bg-info">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h5 class="card-title">Mouvements Récents</h5>
                                    <h2 class="mb-0"><?php echo e($mouvementsRecents->count()); ?></h2>
                                </div>
                                <div class="align-self-center">
                                    <i class="bi bi-activity display-4"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Liste des comptes -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Comptes Financiers</h5>
                        </div>
                        <div class="card-body">
                            <?php if($comptes->count() > 0): ?>
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead class="table-dark">
                                            <tr>
                                                <th>Nom du Compte</th>
                                                <th>Type</th>
                                                <th>Solde</th>
                                                <th>Description</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $__currentLoopData = $comptes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $compte): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <tr>
                                                    <td><strong><?php echo e($compte->nom); ?></strong></td>
                                                    <td>
                                                        <?php switch($compte->type):
                                                            case ('caisse'): ?>
                                                                <span class="badge bg-success">Caisse</span>
                                                                <?php break; ?>
                                                            <?php case ('banque'): ?>
                                                                <span class="badge bg-primary">Banque</span>
                                                                <?php break; ?>
                                                            <?php case ('epargne'): ?>
                                                                <span class="badge bg-info">Épargne</span>
                                                                <?php break; ?>
                                                            <?php default: ?>
                                                                <span class="badge bg-secondary"><?php echo e(ucfirst($compte->type)); ?></span>
                                                        <?php endswitch; ?>
                                                    </td>
                                                    <td>
                                                        <strong class="<?php echo e($compte->solde >= 0 ? 'text-success' : 'text-danger'); ?>">
                                                            <?php echo e(number_format($compte->solde, 0, ',', ' ')); ?> FC
                                                        </strong>
                                                    </td>
                                                    <td><?php echo e($compte->description); ?></td>
                                                    <td>
                                                        <div class="btn-group" role="group">
                                                            <a href="<?php echo e(route('comptes-financiers.show', $compte)); ?>" class="btn btn-sm btn-outline-info">
                                                                <i class="bi bi-eye"></i>
                                                            </a>
                                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin')): ?>
                                                            <a href="<?php echo e(route('comptes-financiers.edit', $compte)); ?>" class="btn btn-sm btn-outline-warning">
                                                                <i class="bi bi-pencil"></i>
                                                            </a>
                                                            <?php endif; ?>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <div class="text-center py-4">
                                    <i class="bi bi-bank display-1 text-muted"></i>
                                    <p class="text-muted mt-3">Aucun compte financier configuré</p>
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin')): ?>
                                    <a href="<?php echo e(route('comptes-financiers.create')); ?>" class="btn btn-primary">
                                        Créer le premier compte
                                    </a>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mouvements récents -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Mouvements Récents</h5>
                        </div>
                        <div class="card-body">
                            <?php if($mouvementsRecents->count() > 0): ?>
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>Type</th>
                                                <th>Compte Source</th>
                                                <th>Compte Destination</th>
                                                <th>Montant</th>
                                                <th>Description</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $__currentLoopData = $mouvementsRecents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mouvement): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <tr>
                                                    <td><?php echo e(\Carbon\Carbon::parse($mouvement->date_operation)->format('d/m/Y H:i')); ?></td>
                                                    <td>
                                                        <?php switch($mouvement->type_mouvement):
                                                            case ('entree'): ?>
                                                                <span class="badge bg-success">Entrée</span>
                                                                <?php break; ?>
                                                            <?php case ('sortie'): ?>
                                                                <span class="badge bg-danger">Sortie</span>
                                                                <?php break; ?>
                                                            <?php case ('transfert'): ?>
                                                                <span class="badge bg-info">Transfert</span>
                                                                <?php break; ?>
                                                            <?php default: ?>
                                                                <span class="badge bg-secondary"><?php echo e(ucfirst($mouvement->type_mouvement)); ?></span>
                                                        <?php endswitch; ?>
                                                    </td>
                                                    <td><?php echo e($mouvement->compteSource->nom ?? '-'); ?></td>
                                                    <td><?php echo e($mouvement->compteDestination->nom ?? '-'); ?></td>
                                                    <td>
                                                        <strong class="<?php echo e($mouvement->type_mouvement === 'entree' ? 'text-success' : ($mouvement->type_mouvement === 'sortie' ? 'text-danger' : 'text-info')); ?>">
                                                            <?php echo e(number_format($mouvement->montant, 0, ',', ' ')); ?> FC
                                                        </strong>
                                                    </td>
                                                    <td><?php echo e($mouvement->description); ?></td>
                                                </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="text-center mt-3">
                                    <a href="<?php echo e(route('mouvements-caisse.index')); ?>" class="btn btn-outline-primary">
                                        Voir tous les mouvements
                                    </a>
                                </div>
                            <?php else: ?>
                                <div class="text-center py-4">
                                    <i class="bi bi-activity display-1 text-muted"></i>
                                    <p class="text-muted mt-3">Aucun mouvement enregistré</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\immo\resources\views/caisse/index.blade.php ENDPATH**/ ?>