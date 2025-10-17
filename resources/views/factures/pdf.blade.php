<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facture {{ $facture->numero_facture }}</title>
    <style>
        @page {
            size: A4;
            margin: 1.5cm;
        }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 11px;
            line-height: 1.4;
            color: #333;
            position: relative;
        }

        /* Filigrane */
        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-30deg);
            font-size: 90px;
            font-weight: bold;
            color: rgba(0, 128, 0, 0.15);
            pointer-events: none;
            z-index: 0;
            white-space: nowrap;
        }

        .watermark.unpaid {
            color: rgba(220, 53, 69, 0.15);
        }

        /* En-tête */
        .header {
            border-bottom: 2px solid #2c5aa0;
            margin-bottom: 15px;
            padding-bottom: 10px;
        }

        .company-info {
            float: left;
            width: 60%;
        }

        .company-name {
            font-size: 20px;
            font-weight: bold;
            color: #2c5aa0;
        }

        .company-slogan {
            font-style: italic;
            font-size: 11px;
            color: #666;
            margin-bottom: 5px;
        }

        .company-details {
            font-size: 10px;
            line-height: 1.4;
        }

        .invoice-info {
            float: right;
            width: 35%;
            text-align: right;
        }

        .invoice-title {
            font-size: 22px;
            font-weight: bold;
            color: #2c5aa0;
        }

        .invoice-number {
            font-weight: bold;
            margin-top: 5px;
        }

        .invoice-date {
            font-size: 10px;
            color: #555;
        }

        .clearfix::after {
            content: "";
            display: table;
            clear: both;
        }

        /* Bloc client & propriété */
        .client-info {
            display: flex;
            justify-content: space-between;
            margin-top: 15px;
            margin-bottom: 15px;
            background: #f8f9fa;
            padding: 10px;
            border-radius: 4px;
        }

        .client-col {
            width: 48%;
        }

        .client-title {
            font-size: 12px;
            font-weight: bold;
            color: #2c5aa0;
            margin-bottom: 5px;
        }

        .client-col-right {
            text-align: right;
        }

        /* Tableau détails */
        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            margin-bottom: 15px;
        }

        .details-table th,
        .details-table td {
            border: 1px solid #ddd;
            padding: 8px;
            font-size: 11px;
        }

        .details-table th {
            background: #2c5aa0;
            color: #fff;
            text-align: left;
        }

        .details-table tr:nth-child(even) {
            background: #f5f7fa;
        }

        /* Garantie */
        .guarantee-section {
            background: #f8f9fa;
            border-left: 4px solid #dc3545;
            padding: 10px;
            margin-top: 10px;
        }

        .guarantee-title {
            font-weight: bold;
            color: #dc3545;
            margin-bottom: 5px;
        }

        .guarantee-text {
            font-size: 10px;
            color: #555;
        }

        /* Pied de page */
        .footer {
            border-top: 1px solid #2c5aa0;
            text-align: center;
            font-size: 9px;
            color: #666;
            margin-top: 15px;
            padding-top: 10px;
        }

        /* Signatures (facultatif) */
        .signature-section {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }

        .signature-box {
            width: 45%;
            text-align: center;
            border-top: 1px solid #333;
            padding-top: 5px;
            font-size: 10px;
        }
    </style>
</head>
<body>

    {{-- Filigrane selon statut --}}
    @if($facture->estPayee())
        <div class="watermark">PAYÉ</div>
    @else
        <div class="watermark unpaid">NON PAYÉ</div>
    @endif

    <!-- En-tête -->
    <div class="header clearfix">
        <div class="company-info">
            {{-- Logo --}}
            @php $logoPath = public_path(config('company.logo')) @endphp
            @if(file_exists($logoPath))
                <div style="float:right; width:35%; text-align:right;">
                    <img src="{{ $logoPath }}" alt="{{ config('company.name') }}" style="max-width:140px; max-height:80px;" />
                </div>
            @endif

            <div class="company-name">{{ config('company.name') }}</div>
            <div class="company-slogan">Votre partenaire immobilier de confiance</div>
            <div class="company-details">
                <strong>Adresse :</strong> {{ config('company.address') }}<br>
                <strong>Téléphone :</strong> {{ config('company.phone') }}<br>
                <strong>Email :</strong> {{ config('company.email') }}
            </div>
        </div>
        <div class="invoice-info">
            <div class="invoice-title">FACTURE</div>
            <div class="invoice-number">N° {{ $facture->numero_facture }}</div>
            <div class="invoice-date">
                Émise le {{ $facture->created_at->format('d/m/Y') }}<br>
                Échéance : {{ $facture->date_echeance->format('d/m/Y') }}
            </div>
        </div>
    </div>

        <!-- Client & appartement -->
    <div class="client-info" style="align-items: flex-start;">
        <div class="client-col" style="vertical-align: top;">
            <div class="client-title">FACTURÉ À :</div>
            <strong>{{ $facture->locataire->nom }} {{ $facture->locataire->prenom }}</strong><br>
            Tél : {{ $facture->locataire->telephone }}<br>
            @if($facture->locataire->email) Email : {{ $facture->locataire->email }}<br> @endif
            @if($facture->locataire->adresse) {{ $facture->locataire->adresse }} @endif
        </div>
        <div class="client-col client-col-right" style="vertical-align: top; text-align: right; margin-top: 0;">
            <div class="client-title">APPARTEMENT / IMMEUBLE</div>
            Immeuble : {{ $facture->loyer->appartement->immeuble->nom }}<br>
            Appartement : N°{{ $facture->loyer->appartement->numero }}<br>
            Loyer : {{ number_format($facture->montant, 0, ',', ' ') }} $<br>
            Période : {{ str_pad($facture->mois, 2, '0', STR_PAD_LEFT) }}/{{ $facture->annee }}
        </div>
    </div>


    <!-- Paiement si payé -->
    @if($facture->estPayee() && $facture->paiements->count() > 0)
        @php $paiement = $facture->paiements->sortByDesc('date_paiement')->first(); @endphp
        <table class="details-table">
            <tr>
                <th colspan="5" style="background: #28a745; color: #fff; text-align: center;">Détails des Paiements</th>
            </tr>
            <tr>
                <th>Date</th>
                <th>Perçu par</th>
                <th>Mode</th>
                <th>Référence</th>
                <th>Montant</th>
            </tr>
            @foreach($facture->paiements as $paiement)
            <tr>
                <td>{{ $paiement->date_paiement ? $paiement->date_paiement->format('d/m/Y') : '-' }}</td>
                <td>{{ $paiement->utilisateur ? ($paiement->utilisateur->nom ?? $paiement->utilisateur->name ?? '-') : '-' }}</td>
                <td>{{ ucfirst($paiement->mode_paiement) }}</td>
                <td>{{ $paiement->reference_paiement ?? '-' }}</td>
                <td>{{ number_format($paiement->montant, 0, ',', ' ') }} $</td>
            </tr>
            @endforeach
            <tr>
                <th colspan="4" style="text-align:right;">Total payé</th>
                <td><strong>{{ number_format($facture->paiements->sum('montant'), 0, ',', ' ') }} $</strong></td>
            </tr>
        </table>
    @endif

<div class="guarantee-section">
     <div class="guarantee-title">🛡️ GARANTIE LOCATIVE</div> 
     <div class="guarantee-text">
         <!-- <strong>Montant de la garantie :</strong> {{ number_format($facture->loyer->garantie_locative, 0, ',', ' ') }} $<br>  -->
         <strong>CONDITIONS IMPORTANTES :</strong><br> 
         • Si cette facture dépasse la date d'échéance sans être réglée, 
         le gestionnaire sera dans l'obligation de la régler en utilisant la garantie locative.<br> 
        • L'utilisation répétée de la garantie locative pour régler des factures peut entraîner
         la rupture du contrat de location.<br>
 </div> </div>

    <!-- Footer -->
    <div class="footer">
        Document généré le {{ $date_generation }} par <strong>{{ config('company.name') }}</strong>.
    </div>

</body>
</html>
