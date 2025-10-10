

<?php $__env->startSection('title', 'Gestion des Loyers'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">Gestion des Loyers</h1>
                <a href="<?php echo e(route('loyers.create')); ?>" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Nouveau Loyer
                </a>
            </div>

            <?php if(session('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?php echo e(session('success')); ?>

                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Liste des Loyers</h5>
                </div>
                <div class="card-body">
                    <?php if($loyers->count() > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Période</th>
                                        <th>Locataire</th>
                                        <th>Appartement</th>
                                        <th>Montant</th>
                                        <th>Échéance</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $loyers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $loyer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td>
                                                <strong><?php echo e(str_pad($loyer->mois, 2, '0', STR_PAD_LEFT)); ?>/<?php echo e($loyer->annee); ?></strong>
                                            </td>
                                            <td>
                                                <?php if($loyer->locataire): ?>
                                                    <?php echo e($loyer->locataire->nom); ?> <?php echo e($loyer->locataire->prenom); ?>

                                                <?php else: ?>
                                                    <span class="text-muted">N/A</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if($loyer->appartement): ?>
                                                    <?php echo e($loyer->appartement->immeuble->nom ?? 'N/A'); ?> - 
                                                    App. <?php echo e($loyer->appartement->numero); ?>

                                                <?php else: ?>
                                                    <span class="text-muted">N/A</span>
                                                <?php endif; ?>
                                            </td>
                                            <td><?php echo e(number_format($loyer->montant, 0, ',', ' ')); ?> FC</td>
                                            <td><?php echo e(\Carbon\Carbon::parse($loyer->date_echeance)->format('d/m/Y')); ?></td>
                                            <td>
                                                <?php switch($loyer->statut):
                                                    case ('paye'): ?>
                                                        <span class="badge bg-success">Payé</span>
                                                        <?php break; ?>
                                                    <?php case ('partiel'): ?>
                                                        <span class="badge bg-warning">Partiel</span>
                                                        <?php break; ?>
                                                    <?php default: ?>
                                                        <span class="badge bg-danger">Impayé</span>
                                                <?php endswitch; ?>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="<?php echo e(route('loyers.show', $loyer)); ?>" class="btn btn-sm btn-outline-info">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    <?php if($loyer->statut !== 'paye'): ?>
                                                        <form action="<?php echo e(route('loyers.marquer-paye', $loyer)); ?>" method="POST" class="d-inline">
                                                            <?php echo csrf_field(); ?>
                                                            <button type="submit" class="btn btn-sm btn-outline-success" 
                                                                    onclick="return confirm('Marquer ce loyer comme payé ?')">
                                                                <i class="bi bi-check-circle"></i>
                                                            </button>
                                                        </form>
                                                    <?php endif; ?>
                                                    <a href="<?php echo e(route('loyers.edit', $loyer)); ?>" class="btn btn-sm btn-outline-warning">
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
                            <i class="bi bi-calendar-check display-1 text-muted"></i>
                            <p class="text-muted mt-3">Aucun loyer enregistré</p>
                            <a href="<?php echo e(route('loyers.create')); ?>" class="btn btn-primary">
                                Créer le premier loyer
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\immo\resources\views/loyers/index.blade.php ENDPATH**/ ?>