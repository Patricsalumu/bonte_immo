

<?php $__env->startSection('title', 'Gestion des Appartements'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">Gestion des Appartements</h1>
                <a href="<?php echo e(route('appartements.create')); ?>" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Nouvel Appartement
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
                    <h5 class="card-title mb-0">Liste des Appartements</h5>
                </div>
                <div class="card-body">
                    <form method="GET" action="<?php echo e(route('appartements.index')); ?>" class="row g-3 mb-3">
                        <div class="col-md-4">
                            <input type="text" name="immeuble" class="form-control" placeholder="Nom de l'immeuble" value="<?php echo e(request('immeuble')); ?>">
                        </div>
                        <div class="col-md-4">
                            <input type="text" name="numero" class="form-control" placeholder="Numéro d'appartement" value="<?php echo e(request('numero')); ?>">
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-outline-primary w-100">
                                <i class="bi bi-search"></i> Rechercher
                            </button>
                        </div>
                    </form>
                    <?php if($appartements->count() > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Immeuble</th>
                                        <th>Numéro</th>
                                        <th>Type</th>
                                        <th>Garantie</th>
                                        <th>Loyer</th>
                                        <th>Locataire</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $appartements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $appartement): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><?php echo e($appartement->immeuble->nom ?? 'N/A'); ?></td>
                                            <td><strong><?php echo e($appartement->numero); ?></strong></td>
                                            <td><?php echo e(ucfirst($appartement->type)); ?></td>
                                            <td><?php echo e($appartement->garantie_locative); ?> $</td>
                                            <td><?php echo e(number_format($appartement->loyer_mensuel, 0, ',', ' ')); ?> $</td>
                                            <td>
                                                <?php if($appartement->locataire): ?>
                                                    <span class="badge bg-success">
                                                        <?php echo e($appartement->locataire->nom); ?> <?php echo e($appartement->locataire->prenom); ?>

                                                    </span>
                                                <?php else: ?>
                                                    <span class="badge bg-warning">Libre</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if($appartement->locataire): ?>
                                                    <span class="badge bg-success">Occupé</span>
                                                <?php else: ?>
                                                    <span class="badge bg-danger">Libre</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="<?php echo e(route('appartements.show', $appartement)); ?>" class="btn btn-sm btn-outline-info">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    <a href="<?php echo e(route('appartements.edit', $appartement)); ?>" class="btn btn-sm btn-outline-warning">
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
                            <i class="bi bi-house display-1 text-muted"></i>
                            <p class="text-muted mt-3">Aucun appartement enregistré</p>
                            <a href="<?php echo e(route('appartements.create')); ?>" class="btn btn-primary">
                                Créer le premier appartement
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\immo\resources\views/appartements/index.blade.php ENDPATH**/ ?>