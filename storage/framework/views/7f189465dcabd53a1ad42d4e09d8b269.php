

<?php $__env->startSection('title', 'Gestion des Loyers'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">Gestion des Loyers</h1>
                <a href="<?php echo e(route('loyers.create')); ?>" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Nouveau Contrat
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
                    <h5 class="card-title mb-0">Liste des Contrats de Loyer</h5>
                </div>
                <div class="card-body">
                    <form method="GET" action="<?php echo e(route('loyers.index')); ?>" class="row g-3 mb-3">
                        <div class="col-md-4">
                            <input type="text" name="appartement" class="form-control" placeholder="Immeuble ou numéro d'appartement" value="<?php echo e(request('appartement')); ?>">
                        </div>
                        <div class="col-md-4">
                            <input type="text" name="client" class="form-control" placeholder="Nom ou prénom du client" value="<?php echo e(request('client')); ?>">
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-outline-primary w-100">
                                <i class="fas fa-search"></i> Rechercher
                            </button>
                        </div>
                    </form>
                    <?php if($loyers->count() > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>ID</th>
                                        <th>Locataire</th>
                                        <th>Appartement</th>
                                        <th>Montant</th>
                                        <th>Période</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $loyers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $loyer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td>
                                                <strong>#<?php echo e($loyer->id); ?></strong>
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
                                            <td><?php echo e(number_format($loyer->montant, 0, ',', ' ')); ?> $</td>
                                            <td>
                                                <div>
                                                    <small class="text-muted">Du:</small> <?php echo e($loyer->date_debut->format('d/m/Y')); ?><br>
                                                    <?php if($loyer->date_fin): ?>
                                                        <small class="text-muted">Au:</small> <?php echo e($loyer->date_fin->format('d/m/Y')); ?>

                                                    <?php else: ?>
                                                        <small class="text-muted">Durée indéterminée</small>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                            <td>
                                                <?php if($loyer->statut === 'actif'): ?>
                                                    <span class="badge bg-success">Actif</span>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary">Inactif</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="<?php echo e(route('loyers.show', $loyer)); ?>" class="btn btn-sm btn-outline-info">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="<?php echo e(route('loyers.edit', $loyer)); ?>" class="btn btn-sm btn-outline-warning">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <?php if($loyer->statut === 'actif'): ?>
                                                        <form action="<?php echo e(route('loyers.desactiver', $loyer)); ?>" method="POST" class="d-inline">
                                                            <?php echo csrf_field(); ?>
                                                            <?php echo method_field('PATCH'); ?>
                                                            <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                                    onclick="return confirm('Désactiver ce contrat ?')">
                                                                <i class="fas fa-times-circle"></i>
                                                            </button>
                                                        </form>
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
                            <i class="fas fa-file-contract display-1 text-muted"></i>
                            <p class="text-muted mt-3">Aucun contrat de loyer enregistré</p>
                            <a href="<?php echo e(route('loyers.create')); ?>" class="btn btn-primary">
                                Créer le premier contrat
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