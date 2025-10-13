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
    <h2>Rapport Mensuel des Loyers - {{ $nomMois[$mois] }} {{ $annee }}</h2>
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
    <ul class="stats">
        <li><strong>Total à facturer :</strong> {{ number_format($stats['total_factures'] ?? 0, 0, ',', ' ') }} $</li>
        <li><strong>Montant payé :</strong> {{ number_format($stats['montant_payes'] ?? 0, 0, ',', ' ') }} $</li>
        <li><strong>Reste à payer :</strong> {{ number_format($stats['reste_a_payer'] ?? 0, 0, ',', ' ') }} $</li>
    </ul>
</body>
</html>
