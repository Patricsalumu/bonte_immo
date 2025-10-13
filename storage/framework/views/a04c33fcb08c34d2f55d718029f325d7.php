

<?php $__env->startSection('title', 'Gestion des Immeubles'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">Gestion des Immeubles</h1>
                <a href="<?php echo e(route('immeubles.create')); ?>" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Nouvel Immeuble
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
                    <h5 class="card-title mb-0">Liste des Immeubles</h5>
                </div>
                <div class="card-body">
                    <?php if($immeubles->count() > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Nom</th>
                                        <th>Adresse</th>
                                        <th>Nombre d'appartements</th>
                                        <th>Gestionnaire</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $immeubles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $immeuble): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td>
                                                <strong><?php echo e($immeuble->nom); ?></strong>
                                            </td>
                                            <td><?php echo e($immeuble->adresse); ?></td>
                                            <td>
                                                <span class="badge bg-info">
                                                    <?php echo e($immeuble->appartements->count()); ?> appartements
                                                </span>
                                            </td>
                                            <td><?php echo e($immeuble->gestionnaire ?? 'Non assigné'); ?></td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="<?php echo e(route('immeubles.show', $immeuble)); ?>" class="btn btn-sm btn-outline-info">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    <a href="<?php echo e(route('immeubles.edit', $immeuble)); ?>" class="btn btn-sm btn-outline-warning">
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
                            <i class="bi bi-building display-1 text-muted"></i>
                            <p class="text-muted mt-
                            <a href="<?php echo e(route('immeubles.create')); ?>" class="btn btn-primary">
                                Créer le premier immeuble
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\immo\resources\views/immeubles/index.blade.php ENDPATH**/ ?>