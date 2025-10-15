

<?php $__env->startSection('title', 'Rapport Mensuel des Loyers'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">
        <i class="fas fa-file-invoice-dollar"></i> Rapport Mensuel des Loyers
    </h1>
    <form method="GET" class="d-flex gap-2" action="<?php echo e(route('rapports.mensuel')); ?>">
        <select name="mois" class="form-select" style="width:120px">
            <?php $__currentLoopData = $nomMois; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $num => $libelle): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($num); ?>" <?php echo e($mois == $num ? 'selected' : ''); ?>><?php echo e($libelle); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
        <select name="annee" class="form-select" style="width:100px">
            <?php for($i = date('Y'); $i >= date('Y')-5; $i--): ?>
                <option value="<?php echo e($i); ?>" <?php echo e($annee == $i ? 'selected' : ''); ?>><?php echo e($i); ?></option>
            <?php endfor; ?>
        </select>
            <select name="percepteur" class="form-select" style="width:150px">
                <option value="">Tous les percepteurs</option>
                <?php $__currentLoopData = $percepteurs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $percepteur): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($percepteur->id); ?>" <?php echo e(request('percepteur') == $percepteur->id ? 'selected' : ''); ?>><?php echo e($percepteur->nom); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <select name="statut" class="form-select" style="width:150px">
                <option value="">Tous les statuts</option>
                <option value="payee" <?php echo e(request('statut') == 'payee' ? 'selected' : ''); ?>>Payées</option>
                <option value="non_payee" <?php echo e(request('statut') == 'non_payee' ? 'selected' : ''); ?>>Non payées</option>
                <option value="partielle" <?php echo e(request('statut') == 'partielle' ? 'selected' : ''); ?>>Partielles</option>
            </select>
        <button type="submit" class="btn btn-primary">Filtrer</button>
        <a href="<?php echo e(route('rapports.export', ['type'=>'mensuel','format'=>'pdf','mois'=>$mois,'annee'=>$annee])); ?>" class="btn btn-success">
            <i class="fas fa-file-pdf"></i> Exporter PDF
        </a>
    </form>
</div>


<?php $__currentLoopData = $factures->groupBy('appartement.immeuble.nom'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $immeuble => $facturesImmeuble): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <h4 class="mt-4 mb-2 text-primary">Immeuble : <?php echo e($immeuble); ?></h4>
    <div class="table-responsive mb-3">
        <table class="table table-bordered table-sm">
            <thead class="table-light">
                <tr>
                    <th>Appartement</th>
                    <th>Client</th>
                    <th>Montant Facture</th>
                    <th>Montant Payé</th>
                    <th>Date Paiement</th>
                    <th>Percepteur</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $facturesImmeuble; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $facture): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    $paiementsValides = $facture->paiements->where('est_annule', false);
                    $montantPayes = $paiementsValides->sum('montant');
                    $dernierPaiement = $paiementsValides->sortByDesc('created_at')->first();
                    $datePaiement = $dernierPaiement ? \Carbon\Carbon::parse($dernierPaiement->created_at)->format('d/m/Y') : '-';
                    $percepteurNom = $dernierPaiement && $dernierPaiement->utilisateur ? $dernierPaiement->utilisateur->nom : '-';
                ?>
                <tr>
                    <td><?php echo e($facture->appartement->numero ?? 'N/A'); ?></td>
                    <td><?php echo e($facture->locataire->nom ?? 'N/A'); ?></td>
                    <td><?php echo e(number_format($facture->montant, 0, ',', ' ')); ?> $</td>
                    <td><?php echo e(number_format($montantPayes, 0, ',', ' ')); ?> $</td>
                    <td><?php echo e($datePaiement); ?></td>
                    <td><?php echo e($percepteurNom); ?></td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
            <tfoot>
                <?php
                    $sousTotalPayes = $facturesImmeuble->reduce(function($carry, $facture) {
                        return $carry + $facture->paiements->where('est_annule', false)->sum('montant');
                    }, 0);
                ?>
                <tr class="table-secondary">
                    <th colspan="2">Sous-total immeuble</th>
                    <th><?php echo e(number_format($facturesImmeuble->sum('montant'), 0, ',', ' ')); ?> $</th>
                    <th><?php echo e(number_format($sousTotalPayes, 0, ',', ' ')); ?> $</th>
                    <th colspan="2"></th>
                </tr>
            </tfoot>
        </table>
    </div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>



<?php
    $totalFactures = $factures->sum('montant');
    $totalPayes = $factures->reduce(function($carry, $facture) {
        return $carry + $facture->paiements->where('est_annule', false)->sum('montant');
    }, 0);
    $resteAPayer = $totalFactures - $totalPayes;
?>
<div class="row mt-4">
    <div class="col-md-6">
        <div class="card mb-3">
            <div class="card-body">
                <h5 class="card-title">Montant encaissé par percepteur</h5>
                <ul class="list-group list-group-flush">
                    <?php $__currentLoopData = $percepteurs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $percepteur): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span><?php echo e($percepteur->nom); ?></span>
                            <span class="fw-bold text-success">
                                <?php echo e(number_format($percepteur->total_encaisse ?? 0, 0, ',', ' ')); ?> $
                            </span>
                        </li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Statistiques du mois</h5>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">Total à facturer : <strong><?php echo e(number_format($totalFactures, 0, ',', ' ')); ?> $</strong></li>
                    <li class="list-group-item">Montant payé : <strong><?php echo e(number_format($totalPayes, 0, ',', ' ')); ?> $</strong></li>
                    <li class="list-group-item">Reste à payer : <strong><?php echo e(number_format($resteAPayer, 0, ',', ' ')); ?> $</strong></li>
                    <?php if(request('percepteur')): ?>
                        <li class="list-group-item text-success">Total payé chez le percepteur : <strong><?php echo e(number_format($totalPayesPercepteur, 0, ',', ' ')); ?> $</strong></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\immo\resources\views/rapports/mensuel.blade.php ENDPATH**/ ?>