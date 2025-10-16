<?php if($factures->count() > 0): ?>
    <tbody>
        <?php $__currentLoopData = $factures; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $facture): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <tr class="<?php echo e($facture->estEnRetard() ? 'table-danger' : ($facture->estPayee() ? 'table-success' : '')); ?>">
            <td>
                <strong><?php echo e($facture->numero_facture); ?></strong>
                <br>
                <small class="text-muted"><?php echo e($facture->created_at->format('d/m/Y')); ?></small>
            </td>
            <td>
                <div>
                    <?php if($facture->locataire): ?>
                        <strong><?php echo e($facture->locataire->nom); ?> <?php echo e($facture->locataire->prenom); ?></strong>
                        <br>
                        <small class="text-muted"><?php echo e($facture->locataire->telephone); ?></small>
                    <?php else: ?>
                        <span class="text-muted">Locataire non trouvé</span>
                    <?php endif; ?>
                </div>
            </td>
            <td>
                <div>
                    <?php if($facture->loyer && $facture->loyer->appartement && $facture->loyer->appartement->immeuble): ?>
                        <strong><?php echo e($facture->loyer->appartement->immeuble->nom); ?></strong>
                        <br>
                        <small class="text-muted">Apt <?php echo e($facture->loyer->appartement->numero); ?></small>
                    <?php else: ?>
                        <span class="text-muted">Appartement non trouvé</span>
                    <?php endif; ?>
                </div>
            </td>
            <td>
                <strong><?php echo e($facture->getMoisNom()); ?> <?php echo e($facture->annee); ?></strong>
                <br>
                <small class="text-muted">Échéance: <?php echo e($facture->date_echeance->format('d/m/Y')); ?></small>
            </td>
            <td>
                <strong><?php echo e(number_format($facture->montant, 0, ',', ' ')); ?> $</strong>
                <?php if($facture->montant_paye > 0): ?>
                    <br>
                    <small class="text-success">Payé: <?php echo e(number_format($facture->montant_paye, 0, ',', ' ')); ?> $</small>
                <?php endif; ?>
            </td>
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
            <td>
                <div class="btn-group" role="group">
                    <a href="<?php echo e(route('factures.export-pdf', $facture)); ?>" 
                       class="btn btn-outline-primary btn-sm" 
                       target="_blank">
                        <i class="fas fa-file-pdf"></i> PDF
                    </a>
                    <?php if($facture->locataire && $facture->locataire->telephone): ?>
                    <button type="button" 
                        class="btn btn-outline-success btn-sm" 
                        data-telephone="<?php echo e($facture->locataire->telephone); ?>"
                        data-prenom="<?php echo e($facture->locataire->prenom ?? ''); ?>"
                        data-nom="<?php echo e($facture->locataire->nom); ?>"
                        data-numero="<?php echo e($facture->numero_facture); ?>"
                        data-id="<?php echo e($facture->id); ?>"
                        data-montant="<?php echo e(number_format($facture->montant, 0, ' ', ' ')); ?>"
                        data-mois="<?php echo e($facture->getMoisNom()); ?> <?php echo e($facture->annee); ?>"
                        data-echeance="<?php echo e($facture->date_echeance->format('d/m/Y')); ?>"
                        onclick="partagerWhatsAppData(this)">
                        <i class="fab fa-whatsapp"></i> WhatsApp
                    </button>
                    <?php endif; ?>
                    <?php if($facture->peutRecevoirPaiement()): ?>
                        <button type="button"
                                class="btn btn-success btn-sm btn-open-modal-paiement"
                                data-bs-toggle="modal"
                                data-bs-target="#modalPaiement"
                                data-facture-id="<?php echo e($facture->id); ?>"
                                data-loyer-id="<?php echo e($facture->loyer_id); ?>"
                                data-numero="<?php echo e($facture->numero_facture); ?>"
                                data-montant="<?php echo e($facture->montant); ?>"
                                data-montant-restant="<?php echo e($facture->montant - $facture->montantPaye()); ?>"
                                data-garantie="<?php echo e($facture->loyer->garantie_locative ?? 0); ?>">
                            <i class="fas fa-credit-card"></i> Payer

                            <?php if($facture->montantPaye() > 0): ?>
                                <small>(<?php echo e(number_format($facture->montantRestant(), 0, ',', ' ')); ?> $ restant)</small>
                            <?php endif; ?>
                        </button>
                    <?php endif; ?>
                    <a href="<?php echo e(route('factures.show', $facture)); ?>" 
                       class="btn btn-outline-info btn-sm">
                        <i class="fas fa-eye"></i>
                    </a>
                </div>
            </td>
        </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </tbody>
    <tfoot>
        <tr>
            <td colspan="7">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <p class="mb-0 text-muted">Affichage <?php echo e($factures->firstItem()); ?> - <?php echo e($factures->lastItem()); ?> sur <?php echo e($factures->total()); ?> factures</p>
                    </div>
                    <div class="flex-fill text-center">
                        <?php echo e($factures->links('vendor.pagination.custom')); ?>

                    </div>
                    <div class="flex-shrink-0"></div>
                </div>
            </td>
        </tr>
    </tfoot>
<?php else: ?>
    <tbody>
        <tr id="messageNoResult">
            <td colspan="7" class="text-center py-4"><i class="fas fa-search fa-2x text-muted mb-2"></i><br>Aucune facture ne correspond à vos critères de recherche.</td>
        </tr>
    </tbody>
<?php endif; ?>
<?php /**PATH C:\xampp\htdocs\immo\resources\views/factures/_table_rows.blade.php ENDPATH**/ ?>