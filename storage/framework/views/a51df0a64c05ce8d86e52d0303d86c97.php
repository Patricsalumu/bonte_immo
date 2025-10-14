

<?php $__env->startSection('title', 'Détail de la facture'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Facture #<?php echo e($facture->id); ?></h4>
                    <div class="d-flex gap-2">
                        <a href="<?php echo e(route('factures.export-pdf', $facture)); ?>" class="btn btn-outline-danger">
                            <i class="bi bi-file-pdf"></i> Télécharger PDF
                        </a>
                        <?php if(auth()->user() && auth()->user()->isAdmin()): ?>
                        <form method="POST" action="<?php echo e(route('factures.destroy', $facture)); ?>" onsubmit="return confirm('Confirmer la suppression de cette facture ? Cette action est irréversible.')">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="btn btn-outline-danger">
                                <i class="bi bi-trash"></i> Supprimer
                            </button>
                        </form>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <td><strong>Période :</strong></td>
                            <td><?php echo e(str_pad($facture->mois, 2, '0', STR_PAD_LEFT)); ?>/<?php echo e($facture->annee); ?></td>
                        </tr>
                        <tr>
                            <td><strong>Montant :</strong></td>
                            <td><?php echo e(number_format($facture->montant, 0, ',', ' ')); ?> $</td>
                        </tr>
                        <tr>
                            <td><strong>Statut :</strong></td>
                            <td>
                                <?php
                                    $montantPaye = $facture->paiements->sum('montant');
                                ?>
                                <?php if($montantPaye >= $facture->montant): ?>
                                    <span class="badge bg-success">Payée</span>
                                <?php elseif($montantPaye > 0): ?>
                                    <span class="badge bg-warning">Partielle</span>
                                <?php else: ?>
                                    <span class="badge bg-danger">Non payée</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Date de création :</strong></td>
                            <td><?php echo e($facture->created_at->format('d/m/Y H:i')); ?></td>
                        </tr>
                        <tr>
                            <td><strong>Appartement :</strong></td>
                            <td>
                                <?php if($facture->loyer && $facture->loyer->appartement): ?>
                                    <a href="<?php echo e(route('appartements.show', $facture->loyer->appartement)); ?>">
                                        <?php echo e($facture->loyer->appartement->numero); ?> - <?php echo e($facture->loyer->appartement->immeuble->nom ?? ''); ?>

                                    </a>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Locataire :</strong></td>
                            <td>
                                <?php if($facture->loyer && $facture->loyer->locataire): ?>
                                    <a href="<?php echo e(route('locataires.show', $facture->loyer->locataire)); ?>">
                                        <?php echo e($facture->loyer->locataire->nom); ?> <?php echo e($facture->loyer->locataire->prenom); ?>

                                    </a>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    </table>

                    <?php if($montantPaye < $facture->montant && auth()->user()): ?>
                    <hr>
                    <h5 class="mb-3">Régler la facture</h5>
                    <form method="POST" action="<?php echo e(route('factures.marquer-payee', $facture)); ?>">
                        <?php echo csrf_field(); ?>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Montant à payer</label>
                                <input type="number" class="form-control" name="montant"
                                       value="<?php echo e($facture->montant - $montantPaye); ?>"
                                       min="1"
                                       max="<?php echo e($facture->montant - $montantPaye); ?>"
                                       step="0.01" required>
                                <div class="form-text">
                                    Montant restant: <?php echo e(number_format($facture->montant - $montantPaye, 0, ',', ' ')); ?> $
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Mode de paiement *</label>
                                <select name="mode_paiement" class="form-select" required>
                                    <option value="">Sélectionner...</option>
                                    <option value="cash">Espèces</option>
                                    <option value="virement">Virement bancaire</option>
                                    <option value="mobile_money">Mobile Money</option>
                                    <option value="garantie_locative">
                                        Garantie locative (<?php echo e(number_format($facture->loyer->garantie_locative ?? 0, 0, ',', ' ')); ?> $ disponible)
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Référence</label>
                            <input type="text" name="reference" class="form-control"
                                   placeholder="Numéro de transaction, référence...">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Notes</label>
                            <textarea name="notes" class="form-control" rows="2" placeholder="Notes facultatives..."></textarea>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-check"></i> Confirmer le paiement
                            </button>
                        </div>
                    </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <!-- Paiements associés en bas, pleine largeur -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Paiements associés</h6>
                </div>
                <div class="card-body">
                    <?php if($facture->paiements && $facture->paiements->count() > 0): ?>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Montant</th>
                                    <th>Mode</th>
                                    <th>Référence</th>
                                    <th>Notes</th>
                                    <th>Utilisateur</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $facture->paiements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $paiement): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($paiement->date_paiement ? $paiement->date_paiement->format('d/m/Y') : '-'); ?></td>
                                    <td><?php echo e(number_format($paiement->montant, 0, ',', ' ')); ?> $</td>
                                    <td><?php echo e(ucfirst($paiement->mode_paiement)); ?></td>
                                    <td><?php echo e($paiement->reference ?? '-'); ?></td>
                                    <td><?php echo e($paiement->notes ?? '-'); ?></td>
                                    <td><?php echo e($paiement->utilisateur ? ($paiement->utilisateur->nom ?? $paiement->utilisateur->name ?? '-') : '-'); ?></td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <div class="text-muted">Aucun paiement enregistré pour cette facture.</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\immo\resources\views/factures/show.blade.php ENDPATH**/ ?>