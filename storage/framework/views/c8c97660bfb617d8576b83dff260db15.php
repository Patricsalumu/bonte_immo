

<?php $__env->startSection('title', 'Profil du Locataire'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0"><?php echo e($locataire->nom); ?> <?php echo e($locataire->prenom); ?></h1>
    <div>
        <a href="<?php echo e(route('locataires.edit', $locataire)); ?>" class="btn btn-warning">
            <i class="fas fa-edit"></i> Modifier
        </a>
        <a href="<?php echo e(route('locataires.index')); ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Retour
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Informations personnelles</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Nom complet :</strong></td>
                                <td><?php echo e($locataire->nom); ?> <?php echo e($locataire->prenom); ?></td>
                            </tr>
                            <tr>
                                <td><strong>Date de naissance :</strong></td>
                                <td>
                                    <?php if($locataire->date_naissance): ?>
                                        <?php echo e(\Carbon\Carbon::parse($locataire->date_naissance)->format('d/m/Y')); ?>

                                        (<?php echo e(\Carbon\Carbon::parse($locataire->date_naissance)->age); ?> ans)
                                    <?php else: ?>
                                        Non renseignée
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Téléphone :</strong></td>
                                <td><?php echo e($locataire->telephone); ?></td>
                            </tr>
                            <tr>
                                <td><strong>Email :</strong></td>
                                <td><?php echo e($locataire->email ?? 'Non renseigné'); ?></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Adresse :</strong></td>
                                <td><?php echo e($locataire->adresse ?? 'Non renseignée'); ?></td>
                            </tr>
                            <tr>
                                <td><strong>Carte d'identité :</strong></td>
                                <td><?php echo e($locataire->numero_carte_identite ?? 'Non renseigné'); ?></td>
                            </tr>
                            <tr>
                                <td><strong>Statut :</strong></td>
                                <td>
                                    <?php if($locataire->actif): ?>
                                        <span class="badge bg-success">Actif</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">Inactif</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Membre depuis :</strong></td>
                                <td><?php echo e($locataire->created_at->format('d/m/Y')); ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Informations professionnelles -->
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0">Informations professionnelles</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Profession :</strong></td>
                                <td><?php echo e($locataire->profession ?? 'Non renseignée'); ?></td>
                            </tr>
                            <tr>
                                <td><strong>Employeur :</strong></td>
                                <td><?php echo e($locataire->employeur ?? 'Non renseigné'); ?></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Revenu mensuel :</strong></td>
                                <td>
                                    <?php if($locataire->revenu_mensuel): ?>
                                        <?php echo e(number_format($locataire->revenu_mensuel, 0, ',', ' ')); ?> $
                                    <?php else: ?>
                                        Non renseigné
                                    <?php endif; ?>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact d'urgence -->
        <?php if($locataire->contact_urgence_nom || $locataire->contact_urgence_telephone): ?>
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0">Contact d'urgence</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Nom :</strong></td>
                                <td><?php echo e($locataire->contact_urgence_nom ?? 'Non renseigné'); ?></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Téléphone :</strong></td>
                                <td><?php echo e($locataire->contact_urgence_telephone ?? 'Non renseigné'); ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Appartement actuel -->
        <?php if($locataire->appartement): ?>
        <div class="card mt-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Appartement actuel</h5>
                <a href="<?php echo e(route('appartements.show', $locataire->appartement)); ?>" class="btn btn-sm btn-outline-primary">
                    Voir l'appartement
                </a>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Immeuble :</strong></td>
                                <td>
                                    <a href="<?php echo e(route('immeubles.show', $locataire->appartement->immeuble)); ?>">
                                        <?php echo e($locataire->appartement->immeuble->nom); ?>

                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Appartement :</strong></td>
                                <td><?php echo e($locataire->appartement->numero); ?></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Type :</strong></td>
                                <td><?php echo e(ucfirst(str_replace('_', ' ', $locataire->appartement->type))); ?></td>
                            </tr>
                            <tr>
                                <td><strong>Loyer mensuel :</strong></td>
                                <td><span class="fw-bold text-success"><?php echo e(number_format($locataire->appartement->loyer_mensuel, 0, ',', ' ')); ?> $</span></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Historique des paiements -->
        <div class="card mt-4">
            <div class="card-body">
                <?php
                    $paiements = $locataire->paiements()->with(['facture', 'utilisateur'])->orderByDesc('date_paiement')->get();
                ?>
                <?php if($paiements->count() > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Motif</th>
                                    <th>Utilisateur</th>
                                    <th>Notes</th>
                                    <th>Référence</th>
                                    <th>Montant</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $paiements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $paiement): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($paiement->date_paiement ? $paiement->date_paiement->format('d/m/Y') : '-'); ?></td>
                                    <td>
                                        <?php if($paiement->facture): ?>
                                            Loyer <?php echo e(str_pad($paiement->facture->mois, 2, '0', STR_PAD_LEFT)); ?>/<?php echo e($paiement->facture->annee); ?>

                                        <?php else: ?>
                                            <?php echo e(ucfirst($paiement->mode_paiement)); ?>

                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo e($paiement->utilisateur ? ($paiement->utilisateur->nom ?? $paiement->utilisateur->name ?? '-') : '-'); ?></td>
                                    <td><?php echo e($paiement->notes ?? '-'); ?></td>
                                    <td><?php echo e($paiement->reference_paiement ?? '-'); ?></td>
                                    <td><?php echo e(number_format($paiement->montant, 0, ',', ' ')); ?> FC</td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center py-4">
                        <i class="fas fa-money-bill fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Aucun paiement enregistré pour ce locataire</p>
                        <a href="<?php echo e(route('paiements.create', ['locataire_id' => $locataire->id])); ?>" class="btn btn-success">
                            Enregistrer le premier paiement
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Notes -->
        <?php if($locataire->notes): ?>
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0">Notes</h5>
            </div>
            <div class="card-body">
                <p><?php echo e($locataire->notes); ?></p>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <div class="col-md-4">
        <!-- Résumé financier -->
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Résumé financier</h6>
            </div>
            <div class="card-body">
                <?php
                    $totalPaiements = $locataire->paiements()->where('est_annule', false)->sum('montant');
                    $impayes = $locataire->factures()->where('statut_paiement', 'non_paye')->sum('montant');
                ?>
                <div class="row text-center">
                    <div class="col-6 mb-3">
                        <div class="border rounded p-3">
                            <h5 class="text-success mb-0"><?php echo e(number_format($totalPaiements, 0, ',', ' ')); ?></h5>
                            <small class="text-muted">Total payé</small>
                        </div>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="border rounded p-3">
                            <h5 class="text-danger mb-0"><?php echo e(number_format($impayes, 0, ',', ' ')); ?></h5>
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
                    <a href="<?php echo e(route('locataires.edit', $locataire)); ?>" class="btn btn-outline-warning">
                        <i class="fas fa-edit"></i> Modifier le profil
                    </a>
                    <?php if($locataire->appartement): ?>
                    <a href="<?php echo e(route('loyers.create', ['locataire_id' => $locataire->id])); ?>" class="btn btn-outline-primary">
                        <i class="fas fa-plus"></i> Nouveau loyer
                    </a>
                    <a href="<?php echo e(route('appartements.show', $locataire->appartement)); ?>" class="btn btn-outline-info">
                        <i class="fas fa-home"></i> Voir l'appartement
                    </a>
                    <?php else: ?>
                    <a href="<?php echo e(route('appartements.index')); ?>" class="btn btn-outline-success">
                        <i class="fas fa-home"></i> Assigner appartement
                    </a>
                    <?php endif; ?>
                    <a href="<?php echo e(route('paiements.create', ['locataire_id' => $locataire->id])); ?>" class="btn btn-outline-success">
                        <i class="fas fa-money-bill"></i> Enregistrer paiement
                    </a>
                </div>
            </div>
        </div>

        <!-- Solvabilité -->
        <?php if($locataire->revenu_mensuel): ?>
        <div class="card mt-3 bg-light">
            <div class="card-body">
                <h6 class="card-title">
                    <i class="fas fa-chart-line"></i> Évaluation financière
                </h6>
                <div class="row">
                    <div class="col-12 mb-2">
                        <small class="text-muted">Capacité de paiement estimée :</small>
                        <div class="fw-bold"><?php echo e(number_format($locataire->revenu_mensuel * 0.33, 0, ',', ' ')); ?> $</div>
                    </div>
                    <?php if($loyerMensuel > 0): ?>
                    <div class="col-12 mb-2">
                        <small class="text-muted">Reste à vivre :</small>
                        <div class="fw-bold"><?php echo e(number_format($locataire->revenu_mensuel - $loyerMensuel, 0, ',', ' ')); ?> $</div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Informations système -->
        <div class="card mt-3 bg-light">
            <div class="card-body">
                <h6 class="card-title">
                    <i class="fas fa-info-circle"></i> Informations
                </h6>
                <ul class="small text-muted mb-0">
                    <li><strong>Créé le :</strong> <?php echo e($locataire->created_at->format('d/m/Y H:i')); ?></li>
                    <li><strong>Modifié le :</strong> <?php echo e($locataire->updated_at->format('d/m/Y H:i')); ?></li>
                    <?php if($locataire->loyers): ?>
                    <li><strong>Nombre de loyers :</strong> <?php echo e($locataire->loyers->count()); ?></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\immo\resources\views/locataires/show.blade.php ENDPATH**/ ?>