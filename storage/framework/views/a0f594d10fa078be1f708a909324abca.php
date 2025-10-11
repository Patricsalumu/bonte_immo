

<?php $__env->startSection('title', 'Détails de l\'Appartement'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0"><?php echo e($appartement->immeuble->nom); ?> - Apt <?php echo e($appartement->numero); ?></h1>
    <div>
        <a href="<?php echo e(route('appartements.edit', $appartement)); ?>" class="btn btn-warning">
            <i class="fas fa-edit"></i> Modifier
        </a>
        <a href="<?php echo e(route('appartements.index')); ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Retour
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Informations de l'appartement</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Immeuble :</strong></td>
                                <td>
                                    <a href="<?php echo e(route('immeubles.show', $appartement->immeuble)); ?>">
                                        <?php echo e($appartement->immeuble->nom); ?>

                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Numéro :</strong></td>
                                <td><?php echo e($appartement->numero); ?></td>
                            </tr>
                            <tr>
                                <td><strong>Type :</strong></td>
                                <td><?php echo e(ucfirst(str_replace('_', ' ', $appartement->type))); ?></td>
                            </tr>
                            <tr>
                                <td><strong>Superficie :</strong></td>
                                <td><?php echo e($appartement->superficie); ?> m²</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Loyer mensuel :</strong></td>
                                <td><span class="text-success fw-bold"><?php echo e(number_format($appartement->loyer_mensuel, 0, ',', ' ')); ?> CDF</span></td>
                            </tr>
                            <tr>
                                <td><strong>Garantie locative :</strong></td>
                                <td><?php echo e(number_format($appartement->garantie_locative, 0, ',', ' ')); ?> CDF</td>
                            </tr>
                            <tr>
                                <td><strong>Statut :</strong></td>
                                <td>
                                    <?php if($appartement->locataire): ?>
                                        <span class="badge bg-warning">Occupé</span>
                                    <?php else: ?>
                                        <span class="badge bg-success">Libre</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Actif :</strong></td>
                                <td>
                                    <?php if($appartement->actif): ?>
                                        <span class="badge bg-success">Oui</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">Non</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                
                <?php if($appartement->description): ?>
                <div class="mt-3">
                    <strong>Description :</strong>
                    <p class="mt-2"><?php echo e($appartement->description); ?></p>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Locataire actuel -->
        <?php if($appartement->locataire): ?>
        <div class="card mt-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Locataire actuel</h5>
                <a href="<?php echo e(route('locataires.show', $appartement->locataire)); ?>" class="btn btn-sm btn-outline-primary">
                    Voir le profil
                </a>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Nom :</strong></td>
                                <td><?php echo e($appartement->locataire->nom); ?> <?php echo e($appartement->locataire->prenom); ?></td>
                            </tr>
                            <tr>
                                <td><strong>Téléphone :</strong></td>
                                <td><?php echo e($appartement->locataire->telephone); ?></td>
                            </tr>
                            <tr>
                                <td><strong>Email :</strong></td>
                                <td><?php echo e($appartement->locataire->email ?? 'Non renseigné'); ?></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Profession :</strong></td>
                                <td><?php echo e($appartement->locataire->profession ?? 'Non renseignée'); ?></td>
                            </tr>
                            <tr>
                                <td><strong>Revenu mensuel :</strong></td>
                                <td>
                                    <?php if($appartement->locataire->revenu_mensuel): ?>
                                        <?php echo e(number_format($appartement->locataire->revenu_mensuel, 0, ',', ' ')); ?> CDF
                                    <?php else: ?>
                                        Non renseigné
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Statut :</strong></td>
                                <td>
                                    <?php if($appartement->locataire->actif): ?>
                                        <span class="badge bg-success">Actif</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">Inactif</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Historique des loyers -->
        <div class="card mt-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Historique des loyers</h5>
                <?php if($appartement->locataire): ?>
                <a href="<?php echo e(route('loyers.create', ['appartement_id' => $appartement->id])); ?>" class="btn btn-sm btn-primary">
                    <i class="fas fa-plus"></i> Nouveau loyer
                </a>
                <?php endif; ?>
            </div>
            <div class="card-body">
                <?php if($appartement->loyers && $appartement->loyers->count() > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Période</th>
                                    <th>Montant</th>
                                    <th>Échéance</th>
                                    <th>Statut</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $appartement->loyers->take(10); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $loyer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e(str_pad($loyer->mois, 2, '0', STR_PAD_LEFT)); ?>/<?php echo e($loyer->annee); ?></td>
                                    <td><?php echo e(number_format($loyer->montant, 0, ',', ' ')); ?> CDF</td>
                                    <td><?php echo e($loyer->date_echeance ? $loyer->date_echeance->format('d/m/Y') : '-'); ?></td>
                                    <td>
                                        <?php switch($loyer->statut):
                                            case ('paye'): ?>
                                                <span class="badge bg-success">Payé</span>
                                                <?php break; ?>
                                            <?php case ('impaye'): ?>
                                                <span class="badge bg-danger">Impayé</span>
                                                <?php break; ?>
                                            <?php case ('partiel'): ?>
                                                <span class="badge bg-warning">Partiel</span>
                                                <?php break; ?>
                                            <?php default: ?>
                                                <span class="badge bg-secondary"><?php echo e(ucfirst($loyer->statut)); ?></span>
                                        <?php endswitch; ?>
                                    </td>
                                    <td>
                                        <a href="<?php echo e(route('loyers.show', $loyer)); ?>" class="btn btn-sm btn-outline-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                    <?php if($appartement->loyers->count() > 10): ?>
                    <div class="text-center mt-3">
                        <a href="<?php echo e(route('loyers.index', ['appartement' => $appartement->id])); ?>" class="btn btn-outline-primary">
                            Voir tous les loyers
                        </a>
                    </div>
                    <?php endif; ?>
                <?php else: ?>
                    <div class="text-center py-4">
                        <i class="fas fa-receipt fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Aucun loyer enregistré pour cet appartement</p>
                        <?php if($appartement->locataire): ?>
                        <a href="<?php echo e(route('loyers.create', ['appartement_id' => $appartement->id])); ?>" class="btn btn-primary">
                            Créer le premier loyer
                        </a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <!-- Statistiques -->
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Résumé financier</h6>
            </div>
            <div class="card-body">
                <?php
                    $totalLoyers = $appartement->loyers ? $appartement->loyers->sum('montant') : 0;
                    $loyersPayes = $appartement->loyers ? $appartement->loyers->where('statut', 'paye')->sum('montant') : 0;
                    $loyersImpayes = $appartement->loyers ? $appartement->loyers->where('statut', 'impaye')->sum('montant') : 0;
                ?>
                
                <div class="row text-center">
                    <div class="col-12 mb-3">
                        <div class="border rounded p-3 bg-light">
                            <h4 class="text-success mb-0"><?php echo e(number_format($appartement->loyer_mensuel, 0, ',', ' ')); ?></h4>
                            <small class="text-muted">CDF/mois</small>
                        </div>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="border rounded p-3">
                            <h5 class="text-primary mb-0"><?php echo e(number_format($loyersPayes, 0, ',', ' ')); ?></h5>
                            <small class="text-muted">Payés</small>
                        </div>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="border rounded p-3">
                            <h5 class="text-danger mb-0"><?php echo e(number_format($loyersImpayes, 0, ',', ' ')); ?></h5>
                            <small class="text-muted">Impayés</small>
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
                    <a href="<?php echo e(route('appartements.edit', $appartement)); ?>" class="btn btn-outline-warning">
                        <i class="fas fa-edit"></i> Modifier l'appartement
                    </a>
                    <?php if($appartement->locataire): ?>
                    <a href="<?php echo e(route('loyers.create', ['appartement_id' => $appartement->id])); ?>" class="btn btn-outline-primary">
                        <i class="fas fa-plus"></i> Nouveau loyer
                    </a>
                    <a href="<?php echo e(route('locataires.show', $appartement->locataire)); ?>" class="btn btn-outline-info">
                        <i class="fas fa-user"></i> Voir le locataire
                    </a>
                    <?php else: ?>
                    <a href="<?php echo e(route('locataires.create', ['appartement_id' => $appartement->id])); ?>" class="btn btn-outline-success">
                        <i class="fas fa-user-plus"></i> Assigner locataire
                    </a>
                    <?php endif; ?>
                    <a href="<?php echo e(route('immeubles.show', $appartement->immeuble)); ?>" class="btn btn-outline-secondary">
                        <i class="fas fa-building"></i> Voir l'immeuble
                    </a>
                </div>
            </div>
        </div>

        <!-- Informations techniques -->
        <div class="card mt-3 bg-light">
            <div class="card-body">
                <h6 class="card-title">
                    <i class="fas fa-info-circle"></i> Informations
                </h6>
                <ul class="small text-muted mb-0">
                    <li><strong>Créé le :</strong> <?php echo e($appartement->created_at->format('d/m/Y')); ?></li>
                    <li><strong>Modifié le :</strong> <?php echo e($appartement->updated_at->format('d/m/Y')); ?></li>
                    <?php if($appartement->loyers): ?>
                    <li><strong>Nombre de loyers :</strong> <?php echo e($appartement->loyers->count()); ?></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\immo\resources\views/appartements/show.blade.php ENDPATH**/ ?>