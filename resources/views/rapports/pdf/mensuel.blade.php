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
                <img src="{{ public_path('logo.png') }}" alt="Logo" style="max-width:100px; max-height:80px;">
            </td>
            <td style="vertical-align:top;">
                <strong>La Bonte Immo</strong><br>
                Avenue de la révolution, Q. Industriel C. Lshi<br>
                Tél : +243 970 000 000<br>
                Email : contact@labonteimmo.com<br>
            </td>
            <td style="text-align:right; vertical-align:top;">
                <h2 style="margin:0; color:#007bff;">Rapport Mensuel des Loyers</h2>
                <span>{{ $nomMois[$mois] }} {{ $annee }}</span>
            </td>
        </tr>
    </table>
    @foreach($factures->groupBy('appartement.immeuble.nom') as $immeuble => $facturesImmeuble)
        <h3>Immeuble : {{ $immeuble }}</h3>
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
                @foreach($facturesImmeuble as $facture)
                @php
                    $paiementsValides = $facture->paiements->where('est_annule', false);
                    $montantPayes = $paiementsValides->sum('montant');
                    $dernierPaiement = $paiementsValides->sortByDesc('created_at')->first();
                    $datePaiement = $dernierPaiement ? \Carbon\Carbon::parse($dernierPaiement->created_at)->format('d/m/Y') : '-';
                    $percepteurNom = $dernierPaiement && $dernierPaiement->utilisateur ? $dernierPaiement->utilisateur->nom : '-';
                @endphp
                <tr>
                    <td>{{ $facture->appartement->numero ?? 'N/A' }}</td>
                    <td>{{ $facture->locataire->nom ?? 'N/A' }}</td>
                    <td>{{ number_format($facture->montant, 0, ',', ' ') }} $</td>
                    <td>{{ number_format($montantPayes, 0, ',', ' ') }} $</td>
                    <td>{{ $datePaiement }}</td>
                    <td>{{ $percepteurNom }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="2">Sous-total immeuble</th>
                    <th>{{ number_format($facturesImmeuble->sum('montant'), 0, ',', ' ') }} $</th>
                    <th>{{ number_format($facturesImmeuble->sum('montant_payes_calc'), 0, ',', ' ') }} $</th>
                    <th colspan="2"></th>
                </tr>
            </tfoot>
        </table>
    @endforeach
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
                        @foreach($percepteurs as $percepteur)
                            <tr>
                                <td>{{ $percepteur->nom }}</td>
                                <td>{{ number_format($percepteur->total_encaisse ?? 0, 0, ',', ' ') }} $</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                @if($percepteurId)
                    <div style="margin-top:10px; color:green;">
                        <strong>Total payé chez le percepteur :</strong> {{ number_format($totalPayesPercepteur ?? 0, 0, ',', ' ') }} $
                    </div>
                @endif
            </td>
            <td style="width:50%; vertical-align:top;">
                <ul class="stats" style="list-style:none; padding-left:0;">
                    <li><strong>Total à facturer :</strong> {{ number_format($totalFactures ?? 0, 0, ',', ' ') }} $</li>
                    <li><strong>Montant payé :</strong> {{ number_format($totalPayes ?? 0, 0, ',', ' ') }} $</li>
                    <li><strong>Reste à payer :</strong> {{ number_format($resteAPayer ?? 0, 0, ',', ' ') }} $</li>
                </ul>
            </td>
        </tr>
    </table>
</body>
</html>
