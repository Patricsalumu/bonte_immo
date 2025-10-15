

<?php $__env->startSection('title', 'Contrat de Loyer #' . $loyer->id); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Contrat de Loyer #<?php echo e($loyer->id); ?></h1>
    <div>
        <a href="<?php echo e(route('loyers.edit', $loyer)); ?>" class="btn btn-warning">
            <i class="fas fa-edit"></i> Modifier
        </a>
        <a href="<?php echo e(route('loyers.index')); ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Retour
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    Informations du contrat
                    <?php if($loyer->statut === 'actif'): ?>
                        <span class="badge bg-success ms-2">Actif</span>
                    <?php else: ?>
                        <span class="badge bg-secondary ms-2">Inactif</span>
                    <?php endif; ?>
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Locataire</h6>
                        <p class="mb-1"><strong><?php echo e($loyer->locataire->nom); ?> <?php echo e($loyer->locataire->prenom); ?></strong></p>
                        <p class="text-muted mb-3"><?php echo e($loyer->locataire->telephone); ?></p>

                        <h6>Appartement</h6>
                        <p class="mb-1"><strong><?php echo e($loyer->appartement->immeuble->nom); ?></strong></p>
                        <p class="mb-1">Appartement <?php echo e($loyer->appartement->numero); ?></p>
                        <p class="text-muted mb-3"><?php echo e($loyer->appartement->type); ?> - <?php echo e($loyer->appartement->superficie); ?> m²</p>
                    </div>
                    <div class="col-md-6">
                        <h6>Détails financiers</h6>
                        <p class="mb-1"><strong>Montant du loyer:</strong> <?php echo e(number_format($loyer->montant, 0, ',', ' ')); ?> $</p>
                        <p class="mb-3"><strong>Garantie locative:</strong> <?php echo e(number_format($loyer->garantie_locative, 0, ',', ' ')); ?> $</p>

                        <h6>Durée du contrat</h6>
                        <p class="mb-1"><strong>Date de début:</strong> <?php echo e($loyer->date_debut->format('d/m/Y')); ?></p>
                        <?php if($loyer->date_fin): ?>
                            <p class="mb-1"><strong>Date de fin:</strong> <?php echo e($loyer->date_fin->format('d/m/Y')); ?></p>
                            <p class="text-muted mb-3"><?php echo e($loyer->duree); ?></p>
                        <?php else: ?>
                            <p class="text-muted mb-3">Contrat à durée indéterminée</p>
                        <?php endif; ?>
                    </div>
                </div>

                <?php if($loyer->notes): ?>
                    <hr>
                    <h6>Notes et conditions particulières</h6>
                    <p class="mb-0"><?php echo e($loyer->notes); ?></p>
                <?php endif; ?>
            </div>
        </div>

        <?php if($loyer->factures && $loyer->factures->count() > 0): ?>
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Factures liées à ce contrat</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Mois</th>
                                <th>Montant</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $loyer->factures; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $facture): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($facture->getMoisNom()); ?> <?php echo e($facture->annee); ?></td>
                                    <td><?php echo e(number_format($facture->montant, 0, ',', ' ')); ?> $</td>
                                    <td>
                                        <?php if($facture->estPayee()): ?>
                                            <span class="badge bg-success">Payée</span>
                                        <?php elseif($facture->estPartielementPayee()): ?>
                                            <span class="badge bg-warning">Partielle</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger">Impayée</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="<?php echo e(route('factures.show', $facture)); ?>" class="btn btn-sm btn-outline-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Actions</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="<?php echo e(route('loyers.edit', $loyer)); ?>" class="btn btn-warning">
                        <i class="fas fa-edit"></i> Modifier le contrat
                    </a>
                    
                    <?php if($loyer->statut === 'actif'): ?>
                        <form action="<?php echo e(route('loyers.desactiver', $loyer)); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('PATCH'); ?>
                            <button type="submit" class="btn btn-danger w-100" 
                                    onclick="return confirm('Êtes-vous sûr de vouloir désactiver ce contrat ?')">
                                <i class="fas fa-times-circle"></i> Désactiver le contrat
                            </button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header">
                <h5 class="card-title mb-0">Informations système</h5>
            </div>
            <div class="card-body">
                <p class="mb-1"><strong>ID:</strong> <?php echo e($loyer->id); ?></p>
                <p class="mb-1"><strong>Créé le:</strong> <?php echo e($loyer->created_at->format('d/m/Y à H:i')); ?></p>
                <p class="mb-0"><strong>Modifié le:</strong> <?php echo e($loyer->updated_at->format('d/m/Y à H:i')); ?></p>
            </div>
        </div>

        <?php if($loyer->estEnCours()): ?>
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="card-title mb-0">Statut du contrat</h5>
            </div>
            <div class="card-body">
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> Contrat en cours
                    <hr>
                    <small>Ce contrat est actuellement actif et en vigueur.</small>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\immo\resources\views/loyers/show.blade.php ENDPATH**/ ?>