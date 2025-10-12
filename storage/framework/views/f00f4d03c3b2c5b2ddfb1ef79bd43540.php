

<?php $__env->startSection('title', 'Gestion des Locataires'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">Gestion des Locataires</h1>
                <a href="<?php echo e(route('locataires.create')); ?>" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Nouveau Locataire
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
                    <h5 class="card-title mb-0">Liste des Locataires</h5>
                </div>
                <div class="card-body">
                    <?php if($locataires->count() > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Nom & Prénom</th>
                                        <th>Téléphone</th>
                                        <th>Appartement</th>
                                        <th>Date d'entrée</th>
                                        <th>Garantie</th>
                                         <th>Reste</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $locataires; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $locataire): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td>
                                                <strong><?php echo e($locataire->nom); ?> <?php echo e($locataire->prenom); ?></strong>
                                            </td>
                                            <td><?php echo e($locataire->telephone); ?></td>
                                            <td>
                                                <?php
                                                    $loyerActif = $locataire->loyers()->where('statut', 'actif')->first();
                                                ?>
                                                <?php if($loyerActif && $loyerActif->appartement): ?>
                                                    <span class="badge bg-info">
                                                        <?php echo e($loyerActif->appartement->immeuble->nom ?? 'N/A'); ?> - App. <?php echo e($loyerActif->appartement->numero); ?>

                                                    </span>
                                                <?php else: ?>
                                                    <span class="badge bg-warning">Non assigné</span>
                                                <?php endif; ?>
                                            </td>
                                            <td><?php echo e(\Carbon\Carbon::parse($locataire->date_entree)->format('d/m/Y')); ?></td>
                                            <td><?php echo e(number_format($locataire->garantie_initiale, 0, ',', ' ')); ?> FC</td>
                                            <td>
                                                <?php
                                                    $loyerActif = $locataire->loyers()->where('statut', 'actif')->first();
                                                ?>
                                                <?php echo e($loyerActif ? number_format($loyerActif->garantie_locative, 0, ',', ' ') . ' FC' : '-'); ?>

                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="<?php echo e(route('locataires.show', $locataire)); ?>" class="btn btn-sm btn-outline-info">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    <a href="<?php echo e(route('locataires.edit', $locataire)); ?>" class="btn btn-sm btn-outline-warning">
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
                            <i class="bi bi-people display-1 text-muted"></i>
                            <p class="text-muted mt-3">Aucun locataire enregistré</p>
                            <a href="<?php echo e(route('locataires.create')); ?>" class="btn btn-primary">
                                Enregistrer le premier locataire
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\immo\resources\views/locataires/index.blade.php ENDPATH**/ ?>