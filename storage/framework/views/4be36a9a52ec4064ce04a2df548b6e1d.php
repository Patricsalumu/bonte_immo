<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Rapport Mensuel des Loyers</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        h2 { color: #007bff; margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ccc; padding: 5px; text-align: left; }
        th { background: #f8f9fa; }
        tfoot th { background: #e9ecef; }
        .stats { margin-top: 20px; }
        .stats li { margin-bottom: 5px; }
    </style>
</head>
<body>
    <h2>Rapport Mensuel des Loyers - <?php echo e($nomMois[$mois]); ?> <?php echo e($annee); ?></h2>
    <?php $__currentLoopData = $factures->groupBy('appartement.immeuble.nom'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $immeuble => $facturesImmeuble): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <h3>Immeuble : <?php echo e($immeuble); ?></h3>
        <table>
            <thead>
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
                <tr>
                    <th colspan="2">Sous-total immeuble</th>
                    <th><?php echo e(number_format($facturesImmeuble->sum('montant'), 0, ',', ' ')); ?> $</th>
                    <th><?php echo e(number_format($facturesImmeuble->sum('montant_payes_calc'), 0, ',', ' ')); ?> $</th>
                    <th colspan="2"></th>
                </tr>
            </tfoot>
        </table>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <ul class="stats">
        <li><strong>Total à facturer :</strong> <?php echo e(number_format($stats['total_factures'] ?? 0, 0, ',', ' ')); ?> $</li>
        <li><strong>Montant payé :</strong> <?php echo e(number_format($stats['montant_payes'] ?? 0, 0, ',', ' ')); ?> $</li>
        <li><strong>Reste à payer :</strong> <?php echo e(number_format($stats['reste_a_payer'] ?? 0, 0, ',', ' ')); ?> $</li>
    </ul>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\immo\resources\views/rapports/pdf/mensuel.blade.php ENDPATH**/ ?>