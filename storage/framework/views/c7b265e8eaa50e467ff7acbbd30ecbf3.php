

<?php $__env->startSection('title', 'Détails de l\'Immeuble'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0"><?php echo e($immeuble->nom); ?></h1>
    <div>
        <a href="<?php echo e(route('immeubles.edit', $immeuble)); ?>" class="btn btn-warning">
            <i class="fas fa-edit"></i> Modifier
        </a>
        <a href="<?php echo e(route('immeubles.index')); ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Retour
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Informations générales</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Nom :</strong></td>
                                <td><?php echo e($immeuble->nom); ?></td>
                            </tr>
                            <tr>
                                <td><strong>Adresse :</strong></td>
                                <td><?php echo e($immeuble->adresse); ?></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Nombre d'étages :</strong></td>
                                <td><?php echo e($immeuble->nombre_etages); ?></td>
                            </tr>
                            <tr>
                                <td><strong>Statut :</strong></td>
                                <td>
                                    <?php if($immeuble->actif): ?>
                                        <span class="badge bg-success">Actif</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">Inactif</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Date de création :</strong></td>
                                <td><?php echo e($immeuble->created_at->format('d/m/Y H:i')); ?></td>
                            </tr>
                            <tr>
                                <td><strong>Dernière modification :</strong></td>
                                <td><?php echo e($immeuble->updated_at->format('d/m/Y H:i')); ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
                
                <?php if($immeuble->description): ?>
                <div class="mt-3">
                    <strong>Description :</strong>
                    <p class="mt-2"><?php echo e($immeuble->description); ?></p>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Liste des appartements -->
        <div class="card mt-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Appartements (<?php echo e($immeuble->appartements->count()); ?>)</h5>
                <a href="<?php echo e(route('appartements.create', ['immeuble_id' => $immeuble->id])); ?>" class="btn btn-sm btn-primary">
                    <i class="fas fa-plus"></i> Ajouter appartement
                </a>
            </div>
            <div class="card-body">
                <?php if($immeuble->appartements->count() > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Numéro</th>
                                    <th>Type</th>
                                    <th>Loyer mensuel</th>
                                    <th>Locataire</th>
                                    <th>Statut</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $immeuble->appartements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $appartement): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><strong><?php echo e($appartement->numero); ?></strong></td>
                                    <td><?php echo e(ucfirst(str_replace('_', ' ', $appartement->type))); ?></td>
                                    <td><?php echo e(number_format($appartement->loyer_mensuel, 0, ',', ' ')); ?> CDF</td>
                                    <td>
                                        <?php if($appartement->locataire): ?>
                                            <a href="<?php echo e(route('locataires.show', $appartement->locataire)); ?>">
                                                <?php echo e($appartement->locataire->nom); ?> <?php echo e($appartement->locataire->prenom); ?>

                                            </a>
                                        <?php else: ?>
                                            <span class="text-muted">Libre</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if($appartement->locataire): ?>
                                            <span class="badge bg-warning">Occupé</span>
                                        <?php else: ?>
                                            <span class="badge bg-success">Libre</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="<?php echo e(route('appartements.show', $appartement)); ?>" class="btn btn-sm btn-outline-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="<?php echo e(route('appartements.edit', $appartement)); ?>" class="btn btn-sm btn-outline-warning">
                                                <i class="fas fa-edit"></i>
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
                        <i class="fas fa-building fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Aucun appartement dans cet immeuble</p>
                        <a href="<?php echo e(route('appartements.create', ['immeuble_id' => $immeuble->id])); ?>" class="btn btn-primary">
                            Ajouter le premier appartement
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <!-- Statistiques -->
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Statistiques</h6>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6 mb-3">
                        <div class="border rounded p-3">
                            <h4 class="text-primary mb-0"><?php echo e($immeuble->appartements->count()); ?></h4>
                            <small class="text-muted">Appartements</small>
                        </div>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="border rounded p-3">
                            <h4 class="text-success mb-0"><?php echo e($immeuble->appartements->whereNull('locataire_id')->count()); ?></h4>
                            <small class="text-muted">Libres</small>
                        </div>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="border rounded p-3">
                            <h4 class="text-warning mb-0"><?php echo e($immeuble->appartements->whereNotNull('locataire_id')->count()); ?></h4>
                            <small class="text-muted">Occupés</small>
                        </div>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="border rounded p-3">
                            <h4 class="text-info mb-0"><?php echo e(number_format($immeuble->appartements->sum('loyer_mensuel'), 0, ',', ' ')); ?></h4>
                            <small class="text-muted">CDF/mois</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions rapides -->
        <div class="card mt-3">
            <div class="card-header">
                <h6 class="mb-0">Actions rapides</h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="<?php echo e(route('appartements.create', ['immeuble_id' => $immeuble->id])); ?>" class="btn btn-outline-primary">
                        <i class="fas fa-plus"></i> Nouvel appartement
                    </a>
                    <a href="<?php echo e(route('immeubles.edit', $immeuble)); ?>" class="btn btn-outline-warning">
                        <i class="fas fa-edit"></i> Modifier l'immeuble
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\immo\resources\views/immeubles/show.blade.php ENDPATH**/ ?>