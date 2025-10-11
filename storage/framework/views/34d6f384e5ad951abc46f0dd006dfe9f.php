<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facture <?php echo e($facture->numero_facture); ?></title>
    <style>
        @page {
            margin: 2cm;
            size: A4;
        }
        
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
        }
        
        .header {
            border-bottom: 3px solid #2c5aa0;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        
        .company-info {
            float: left;
            width: 60%;
        }
        
        .company-name {
            font-size: 24px;
            font-weight: bold;
            color: #2c5aa0;
            margin-bottom: 5px;
        }
        
        .company-slogan {
            font-style: italic;
            color: #666;
            margin-bottom: 10px;
        }
        
        .company-details {
            font-size: 11px;
            line-height: 1.6;
        }
        
        .invoice-info {
            float: right;
            width: 35%;
            text-align: right;
        }
        
        .invoice-title {
            font-size: 28px;
            font-weight: bold;
            color: #2c5aa0;
            margin-bottom: 10px;
        }
        
        .invoice-number {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .invoice-date {
            font-size: 11px;
            color: #666;
        }
        
        .clearfix::after {
            content: "";
            display: table;
            clear: both;
        }
        
        .client-info {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 30px;
        }
        
        .client-title {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 10px;
            color: #2c5aa0;
        }
        
        .property-details {
            margin-bottom: 30px;
        }
        
        .section-title {
            font-size: 16px;
            font-weight: bold;
            color: #2c5aa0;
            border-bottom: 2px solid #2c5aa0;
            padding-bottom: 5px;
            margin-bottom: 15px;
        }
        
        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        .details-table th,
        .details-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        
        .details-table th {
            background-color: #2c5aa0;
            color: white;
            font-weight: bold;
        }
        
        .details-table tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        
        .amount-section {
            background-color: #e8f4fd;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 30px;
        }
        
        .amount-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }
        
        .amount-label {
            font-weight: bold;
        }
        
        .total-amount {
            font-size: 18px;
            font-weight: bold;
            color: #2c5aa0;
            border-top: 2px solid #2c5aa0;
            padding-top: 10px;
        }
        
        .payment-info {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        
        .payment-status {
            font-weight: bold;
            text-align: center;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        
        .status-paid {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .status-unpaid {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .status-partial {
            background-color: #fff3cd;
            color: #856404;
            border: 1px solid #ffeaa7;
        }
        
        .guarantee-section {
            background-color: #f8f9fa;
            border-left: 4px solid #dc3545;
            padding: 20px;
            margin-bottom: 30px;
        }
        
        .guarantee-title {
            font-size: 14px;
            font-weight: bold;
            color: #dc3545;
            margin-bottom: 10px;
        }
        
        .guarantee-text {
            font-size: 11px;
            line-height: 1.6;
            color: #666;
        }
        
        .notes-section {
            background-color: #e7f3ff;
            border-left: 4px solid #2c5aa0;
            padding: 15px;
            margin-bottom: 30px;
        }
        
        .notes-title {
            font-size: 14px;
            font-weight: bold;
            color: #2c5aa0;
            margin-bottom: 10px;
        }
        
        .footer {
            border-top: 2px solid #2c5aa0;
            padding-top: 20px;
            text-align: center;
            font-size: 10px;
            color: #666;
            margin-top: 40px;
        }
        
        .signature-section {
            margin-top: 40px;
            display: flex;
            justify-content: space-between;
        }
        
        .signature-box {
            width: 45%;
            text-align: center;
            border-top: 1px solid #333;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    <!-- En-tête -->
    <div class="header clearfix">
        <div class="company-info">
            <div class="company-name"><?php echo e($entreprise['nom']); ?></div>
            <div class="company-slogan">Votre partenaire immobilier de confiance</div>
            <div class="company-details">
                <strong>Adresse :</strong> <?php echo e($entreprise['adresse']); ?><br>
                <strong>Téléphone :</strong> <?php echo e($entreprise['telephone']); ?><br>
                <strong>Email :</strong> <?php echo e($entreprise['email']); ?>

            </div>
        </div>
        
        <div class="invoice-info">
            <div class="invoice-title">FACTURE</div>
            <div class="invoice-number">N° <?php echo e($facture->numero_facture); ?></div>
            <div class="invoice-date">
                Date d'émission : <?php echo e($facture->created_at->format('d/m/Y')); ?><br>
                Date d'échéance : <?php echo e($facture->date_echeance->format('d/m/Y')); ?>

            </div>
        </div>
    </div>

    <!-- Informations client -->
    <div class="client-info">
        <div class="client-title">FACTURÉ À :</div>
        <strong><?php echo e($facture->locataire->nom); ?> <?php echo e($facture->locataire->prenom); ?></strong><br>
        Téléphone : <?php echo e($facture->locataire->telephone); ?><br>
        <?php if($facture->locataire->email): ?>
            Email : <?php echo e($facture->locataire->email); ?><br>
        <?php endif; ?>
        <?php if($facture->locataire->adresse): ?>
            Adresse : <?php echo e($facture->locataire->adresse); ?>

        <?php endif; ?>
    </div>

    <!-- Détails de la propriété -->
    <div class="property-details">
        <div class="section-title">DÉTAILS DE LA PROPRIÉTÉ</div>
        <table class="details-table">
            <tr>
                <th>Immeuble</th>
                <td><?php echo e($facture->loyer->appartement->immeuble->nom); ?></td>
            </tr>
            <tr>
                <th>Appartement</th>
                <td>N° <?php echo e($facture->loyer->appartement->numero); ?></td>
            </tr>
            <tr>
                <th>Type</th>
                <td><?php echo e(ucfirst($facture->loyer->appartement->type)); ?></td>
            </tr>
            <tr>
                <th>Superficie</th>
                <td><?php echo e($facture->loyer->appartement->superficie); ?> m²</td>
            </tr>
            <tr>
                <th>Période de facturation</th>
                <td><?php echo e($facture->getMoisNom()); ?> <?php echo e($facture->annee); ?></td>
            </tr>
        </table>
    </div>

    <!-- Statut de paiement -->
    <?php if($facture->estPayee()): ?>
        <div class="payment-status status-paid">
            ✓ FACTURE PAYÉE
        </div>
    <?php elseif($facture->estPartielementPayee()): ?>
        <div class="payment-status status-partial">
            ⚠ FACTURE PARTIELLEMENT PAYÉE
        </div>
    <?php else: ?>
        <div class="payment-status status-unpaid">
            ⚠ FACTURE NON PAYÉE
        </div>
    <?php endif; ?>

    <!-- Montants -->
    <div class="amount-section">
        <div class="section-title">DÉTAIL DES MONTANTS</div>
        
        <div class="amount-row">
            <span class="amount-label">Loyer mensuel :</span>
            <span><?php echo e(number_format($facture->montant, 0, ',', ' ')); ?> CDF</span>
        </div>
        
        <?php if($facture->montant_paye > 0): ?>
            <div class="amount-row">
                <span class="amount-label">Montant déjà payé :</span>
                <span style="color: green;"><?php echo e(number_format($facture->montant_paye, 0, ',', ' ')); ?> CDF</span>
            </div>
        <?php endif; ?>
        
        <div class="amount-row total-amount">
            <span class="amount-label">Montant à payer :</span>
            <span><?php echo e(number_format($facture->montant - $facture->montant_paye, 0, ',', ' ')); ?> CDF</span>
        </div>
    </div>

    <!-- Information sur les paiements -->
    

    <!-- Section garantie locative -->
    <div class="guarantee-section">
        <div class="guarantee-title">🛡️ GARANTIE LOCATIVE</div>
        <div class="guarantee-text">
            <strong>Montant de la garantie :</strong> <?php echo e(number_format($facture->loyer->garantie_locative, 0, ',', ' ')); ?> CDF<br><br>
            
            <strong>CONDITIONS IMPORTANTES :</strong><br>
            • Si cette facture dépasse la date d'échéance sans être réglée, le gestionnaire sera dans l'obligation de la régler en utilisant la garantie locative.<br>
            • L'utilisation répétée de la garantie locative pour régler des factures peut entraîner la rupture du contrat de location.<br>
            • Le locataire devra reconstituer la garantie locative dans un délai de 30 jours après toute utilisation.<br>
            • Le non-reconstitution de la garantie locative constitue un motif de résiliation du contrat.
        </div>
    </div>

    <!-- Notes et conditions -->
    <?php if($facture->loyer->notes || $facture->estEnRetard()): ?>
        <div class="notes-section">
            <div class="notes-title">📋 NOTES ET CONDITIONS</div>
            
            <?php if($facture->estEnRetard()): ?>
                <p style="color: #dc3545; font-weight: bold;">
                    ⚠️ ATTENTION : Cette facture est en retard de <?php echo e($facture->getJoursRetard()); ?> jour(s).
                </p>
            <?php endif; ?>
            
            <?php if($facture->loyer->notes): ?>
                <p><strong>Notes du contrat :</strong><br><?php echo e($facture->loyer->notes); ?></p>
            <?php endif; ?>
            
            <p><strong>Modalités de paiement :</strong><br>
            • Paiement par espèces, virement bancaire ou mobile money<br>
            • Tout retard de paiement peut entraîner des pénalités<br>
            • En cas de difficultés, contactez immédiatement la gestion</p>
        </div>
    <?php endif; ?>

    <!-- Signatures -->
    <div class="signature-section">
        <div class="signature-box">
            <strong>Le Gestionnaire</strong><br>
            La Bonte Immo
        </div>
        <div class="signature-box">
            <strong>Le Locataire</strong><br>
            <?php echo e($facture->locataire->nom); ?> <?php echo e($facture->locataire->prenom); ?>

        </div>
    </div>

    <!-- Pied de page -->
    <div class="footer">
        <p><?php echo e($entreprise['nom']); ?> - <?php echo e($entreprise['adresse']); ?></p>
        <p>Téléphone : <?php echo e($entreprise['telephone']); ?> | Email : <?php echo e($entreprise['email']); ?></p>
        <p>Document généré le <?php echo e($date_generation); ?></p>
    </div>
</body>
</html><?php /**PATH C:\xampp\htdocs\immo\resources\views/factures/pdf.blade.php ENDPATH**/ ?>