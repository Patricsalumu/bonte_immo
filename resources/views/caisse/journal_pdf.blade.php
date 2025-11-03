<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Journal de Caisse - {{ config('company.name') }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size:12px; color:#222 }
        .company-table { width:100%; margin-bottom:10px }
        .company-table td { vertical-align:top }
        .company-name { font-size:16px; font-weight:700 }
        .company-details { font-size:12px; color:#555 }
        h2 { color: #0d6efd; margin:0 0 6px 0 }
        .stats { margin-bottom:10px; display:flex; gap:8px }
        .stat { padding:8px; border:1px solid #e6e6e6; flex:1; text-align:center; background:#fafafa }
        table{ width:100%; border-collapse:collapse; margin-top:8px }
        th, td{ border:1px solid #ddd; padding:6px; font-size:11px }
        th{ background:#f8f9fa }
        .text-end { text-align:right }
        .small-muted { font-size:11px; color:#666 }
    </style>
</head>
<body>
    <table class="company-table">
        <tr>
            <td style="width:120px;">
                @php $logoPath = public_path(config('company.logo')) @endphp
                @if(file_exists($logoPath))
                    <img src="{{ $logoPath }}" alt="{{ config('company.name') }}" style="max-width:120px; max-height:80px;">
                @endif
            </td>
            <td>
                <div class="company-name">{{ config('company.name') }}</div>
                <div class="company-details">
                    {{ config('company.address') }}<br>
                    Tél : {{ config('company.phone') }}<br>
                    Email : {{ config('company.email') }}
                </div>
            </td>
            <td style="text-align:right; width:220px;">
                <h2>Journal de Caisse</h2>
                <div class="small-muted">Imprimé le {{ now()->format('d/m/Y H:i') }}</div>
                @if(request()->filled('q'))
                    <div class="small-muted">Recherche : <strong>{{ request('q') }}</strong></div>
                @endif
            </td>
        </tr>
    </table>

    <div class="stats">
        <div class="stat">
            <div class="small-muted">Total Entrées</div>
            <div style="font-weight:bold">{{ number_format($statistiques['total_entrees'] ?? 0, 2, ',', ' ') }} $</div>
        </div>
        <div class="stat">
            <div class="small-muted">Total Sorties</div>
            <div style="font-weight:bold">{{ number_format($statistiques['total_sorties'] ?? 0, 2, ',', ' ') }} $</div>
        </div>
        <div class="stat">
            <div class="small-muted">Solde Net</div>
            <div style="font-weight:bold">{{ number_format($statistiques['solde_net'] ?? 0, 2, ',', ' ') }} $</div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width:12%">Date</th>
                <th style="width:24%">Compte</th>
                <th style="width:10%">Type</th>
                <th>Description</th>
                <th style="width:16%" class="text-end">Montant</th>
            </tr>
        </thead>
        <tbody>
            @foreach($mouvements as $m)
                <tr>
                    <td>{{ $m->created_at->format('d/m/Y H:i') }}</td>
                    <td>
                        @php $compte = $m->type_mouvement == 'entree' ? $m->compteDestination : $m->compteSource; @endphp
                        @if($compte)
                            {{ $compte->nom_compte }} ({{ $compte->type ? ucfirst($compte->type) : '' }})
                        @else
                            N/A
                        @endif
                    </td>
                    <td>@if($m->type_mouvement == 'entree') Entrée @elseif($m->type_mouvement == 'sortie') Sortie @else Transfert @endif</td>
                    <td>@if($m->reference)<small>[{{ $m->reference }}]</small> @endif {{ $m->description }}</td>
                    <td class="text-end">{{ ($m->type_mouvement == 'entree' ? '+' : '-') . number_format($m->montant, 2, ',', ' ') }} $</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top:12px;font-size:11px;color:#666">Total mouvements : {{ $mouvements->count() }}</div>

    @if(!empty($periodeStats))
        <div style="margin-top:12px">
            <h4 style="margin:6px 0 4px 0">Entrées par période</h4>
            <table style="width:400px; border-collapse:collapse;">
                <thead>
                    <tr>
                        <th style="border:1px solid #ddd; padding:6px; text-align:left">Période</th>
                        <th style="border:1px solid #ddd; padding:6px; text-align:right">Montant</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($periodeStats as $ps)
                        <tr>
                            <td style="border:1px solid #ddd; padding:6px">{{ $ps['label'] }}</td>
                            <td style="border:1px solid #ddd; padding:6px; text-align:right">{{ number_format($ps['amount'], 2, ',', ' ') }} $</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</body>
</html>