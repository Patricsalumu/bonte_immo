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
    <table style="width:100%; margin-bottom:10px;">
        <tr>
            <td style="width:120px; vertical-align:top;">
                <img src="<?php echo e(public_path('logo.png')); ?>" alt="Logo" style="max-width:100px; max-height:80px;">
            </td>
            <td style="vertical-align:top;">
                <strong>La Bonte Immo</strong><br>
                Avenue de la révolution, Q. Industriel C. Lshi<br>
                Tél : +243 970 000 000<br>
                Email : contact@labonteimmo.com<br>
            </td>
            <td style="text-align:right; vertical-align:top;">
                <h2 style="margin:0; color:#007bff;">Rapport Mensuel des Loyers</h2>
                <span><?php echo e($nomMois[$mois]); ?> <?php echo e($annee); ?></span>
            </td>
        </tr>
    </table>
    <?php
        $libelleStatut = '';
        if(isset($statut)) {
            if($statut === 'payee') $libelleStatut = 'Payées';
            elseif($statut === 'non_payee') $libelleStatut = 'Non payées';
            elseif($statut === 'partielle') $libelleStatut = 'Partielles';
        }
        $libellePercepteur = '';
        if(isset($percepteurId) && $percepteurId) {
            $percepteurObj = $percepteurs->where('id', $percepteurId)->first();
            $libellePercepteur = $percepteurObj ? $percepteurObj->nom : '';
        }
    ?>
    <div style="margin-bottom:15px;">
        <?php if($libellePercepteur): ?>
            <span><strong>Liste filtrée pour le percepteur :</strong> <?php echo e($libellePercepteur); ?></span><br>
        <?php endif; ?>
        <?php if($libelleStatut): ?>
            <span><strong>Statut des factures :</strong> <?php echo e($libelleStatut); ?></span>
        <?php endif; ?>
    </div>
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
    <table style="width:100%; margin-top:30px;">
        <tr>
            <td style="width:50%; vertical-align:top;">
                <strong>Montant encaissé par percepteur</strong>
                <table style="width:100%; border-collapse:collapse; margin-top:5px;">
                    <thead>
                        <tr>
                            <th style="background:#f8f9fa;">Percepteur</th>
                            <th style="background:#f8f9fa;">Montant encaissé</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $percepteurs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $percepteur): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($percepteur->nom); ?></td>
                                <td><?php echo e(number_format($percepteur->total_encaisse ?? 0, 0, ',', ' ')); ?> $</td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
                <?php if($percepteurId): ?>
                    <div style="margin-top:10px; color:green;">
                        <strong>Total payé chez le percepteur :</strong> <?php echo e(number_format($totalPayesPercepteur ?? 0, 0, ',', ' ')); ?> $
                    </div>
                <?php endif; ?>
            </td>
            <td style="width:50%; vertical-align:top;">
                <ul class="stats" style="list-style:none; padding-left:0;">
                    <li><strong>Total à facturer :</strong> <?php echo e(number_format($totalFactures ?? 0, 0, ',', ' ')); ?> $</li>
                    <li><strong>Montant payé :</strong> <?php echo e(number_format($totalPayes ?? 0, 0, ',', ' ')); ?> $</li>
                    <li><strong>Reste à payer :</strong> <?php echo e(number_format($resteAPayer ?? 0, 0, ',', ' ')); ?> $</li>
                </ul>
            </td>
        </tr>
    </table>
    <div style="margin-top:40px; text-align:right; font-size:11px; color:#555;">
        Généré le <?php echo e(\Carbon\Carbon::now()->format('d/m/Y à H:i')); ?> par La Bonte Immo App<br>
        &copy; <?php echo e(date('Y')); ?> La Bonte Immo. Tous droits réservés.
    </div>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\immo\resources\views/rapports/pdf/mensuel.blade.php ENDPATH**/ ?>