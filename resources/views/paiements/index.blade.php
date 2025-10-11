@extends('layouts.app')

@section('title', 'Factures et Paiements')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Factures et Paiements</h1>
    <div class="d-flex gap-2">
        <!-- Bouton pour générer les factures -->
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#genererFacturesModal">
            <i class="fas fa-file-invoice"></i> Générer factures
        </button>
        <a href="{{ route('paiements.create') }}" class="btn btn-success">
            <i class="fas fa-plus"></i> Nouveau paiement
        </a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<!-- Statistiques rapides -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">Non payées</h6>
                        <h4>{{ $factures->where('statut_paiement', 'non_paye')->count() }}</h4>
                    </div>
                    <i class="fas fa-clock fa-2x"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-danger text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">En retard</h6>
                        <h4>{{ $factures->filter(function($f) { return $f->estEnRetard(); })->count() }}</h4>
                    </div>
                    <i class="fas fa-exclamation-triangle fa-2x"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">Payées</h6>
                        <h4>{{ $factures->filter(function($f) { return $f->estPayee(); })->count() }}</h4>
                    </div>
                    <i class="fas fa-check-circle fa-2x"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">Montant total</h6>
                        <h4>{{ number_format($factures->sum('montant'), 0, ',', ' ') }} CDF</h4>
                    </div>
                    <i class="fas fa-dollar-sign fa-2x"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Liste des factures -->
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">
            <i class="fas fa-file-invoice-dollar"></i> Factures de Loyer
        </h5>
    </div>
    <div class="card-body">
        <!-- Filtres et barre de recherche -->
        <div class="row mb-4">
            <div class="col-md-3">
                <label class="form-label">Filtrer par statut</label>
                <select id="filtreStatut" class="form-select">
                    <option value="">Tous les statuts</option>
                    <option value="non_paye">Non payées</option>
                    <option value="paye">Payées</option>
                    <option value="en_retard">En retard</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Filtrer par mois</label>
                <select id="filtreMois" class="form-select">
                    <option value="">Tous les mois</option>
                    <option value="1">Janvier</option>
                    <option value="2">Février</option>
                    <option value="3">Mars</option>
                    <option value="4">Avril</option>
                    <option value="5">Mai</option>
                    <option value="6">Juin</option>
                    <option value="7">Juillet</option>
                    <option value="8">Août</option>
                    <option value="9">Septembre</option>
                    <option value="10">Octobre</option>
                    <option value="11">Novembre</option>
                    <option value="12">Décembre</option>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Rechercher</label>
                <div class="input-group">
                    <input type="text" id="rechercheFacture" class="form-control" 
                           placeholder="Numéro facture, client, téléphone, appartement...">
                    <button class="btn btn-outline-secondary" type="button" id="btnClearSearch">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        </div>
        @if($factures->count() > 0)
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Facture #</th>
                            <th>Locataire</th>
                            <th>Appartement</th>
                            <th>Période</th>
                            <th>Montant</th>
                            <th>Statut</th>
                            <th width="250">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($factures as $facture)
                        <tr class="{{ $facture->estEnRetard() ? 'table-danger' : ($facture->estPayee() ? 'table-success' : '') }}">
                            <td>
                                <strong>{{ $facture->numero_facture }}</strong>
                                <br>
                                <small class="text-muted">{{ $facture->created_at->format('d/m/Y') }}</small>
                            </td>
                            <td>
                                <div>
                                    <strong>{{ $facture->locataire->nom }} {{ $facture->locataire->prenom }}</strong>
                                    <br>
                                    <small class="text-muted">{{ $facture->locataire->telephone }}</small>
                                </div>
                            </td>
                            <td>
                                <div>
                                    <strong>{{ $facture->loyer->appartement->immeuble->nom }}</strong>
                                    <br>
                                    <small class="text-muted">Apt {{ $facture->loyer->appartement->numero }}</small>
                                </div>
                            </td>
                            <td>
                                <strong>{{ $facture->getMoisNom() }} {{ $facture->annee }}</strong>
                                <br>
                                <small class="text-muted">Échéance: {{ $facture->date_echeance->format('d/m/Y') }}</small>
                            </td>
                            <td>
                                <strong>{{ number_format($facture->montant, 0, ',', ' ') }} CDF</strong>
                                @if($facture->montant_paye > 0)
                                    <br>
                                    <small class="text-success">Payé: {{ number_format($facture->montant_paye, 0, ',', ' ') }} CDF</small>
                                @endif
                            </td>
                            <td>
                                @if($facture->estPayee())
                                    <span class="badge bg-success">Payée</span>
                                @elseif($facture->estPartielementPayee())
                                    <span class="badge bg-warning">Partielle</span>
                                @elseif($facture->estEnRetard())
                                    <span class="badge bg-danger">En retard</span>
                                @else
                                    <span class="badge bg-secondary">Non payée</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <!-- Bouton PDF -->
                                    <a href="{{ route('factures.export-pdf', $facture) }}" 
                                       class="btn btn-outline-primary btn-sm" 
                                       target="_blank">
                                        <i class="fas fa-file-pdf"></i> PDF
                                    </a>
                                    
                                    <!-- Bouton WhatsApp -->
                                    @if($facture->locataire && $facture->locataire->telephone)
                                    <button type="button" 
                                            class="btn btn-outline-success btn-sm" 
                                            onclick="partagerWhatsApp('{{ $facture->locataire->telephone }}', '{{ $facture->locataire->prenom ?? '' }}', '{{ $facture->locataire->nom }}', '{{ $facture->numero_facture }}', '{{ number_format($facture->montant, 0, ',', ' ') }}', '{{ $facture->getMoisNom() }} {{ $facture->annee }}', '{{ $facture->date_echeance->format('d/m/Y') }}')">
                                        <i class="fab fa-whatsapp"></i> WhatsApp
                                    </button>
                                    @endif
                                    
                                    @if(!$facture->estPayee())
                                        <button type="button" 
                                                class="btn btn-success btn-sm" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#modalPaiement{{ $facture->id }}">
                                            <i class="fas fa-credit-card"></i> Payer
                                        </button>
                                    @endif
                                    
                                    <a href="{{ route('factures.show', $facture) }}" 
                                       class="btn btn-outline-info btn-sm">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </div>

                                <!-- Modal de paiement -->
                                @if(!$facture->estPayee())
                                <div class="modal fade" id="modalPaiement{{ $facture->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Régler la facture {{ $facture->numero_facture }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <form method="POST" action="{{ route('factures.marquer-payee', $facture) }}">
                                                @csrf
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label class="form-label">Montant à payer</label>
                                                        <input type="number" class="form-control" name="montant"
                                                               value="{{ $facture->montant - $facture->montant_paye }}" 
                                                               min="1" max="{{ $facture->montant - $facture->montant_paye }}" required>
                                                        <div class="form-text">
                                                            Montant restant: {{ number_format($facture->montant - $facture->montant_paye, 0, ',', ' ') }} CDF
                                                        </div>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Mode de paiement *</label>
                                                        <select name="mode_paiement" class="form-select" required>
                                                            <option value="">Sélectionner...</option>
                                                            <option value="especes">Espèces</option>
                                                            <option value="virement">Virement bancaire</option>
                                                            <option value="mobile_money">Mobile Money</option>
                                                            <option value="garantie">Garantie locative</option>
                                                        </select>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Référence</label>
                                                        <input type="text" name="reference" class="form-control" 
                                                               placeholder="Numéro de transaction, référence...">
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                                    <button type="submit" class="btn btn-success">
                                                        <i class="fas fa-check"></i> Confirmer le paiement
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-file-invoice fa-4x text-muted mb-3"></i>
                <h4 class="text-muted">Aucune facture trouvée</h4>
                <p class="text-muted">Aucune facture disponible pour le moment.</p>
            </div>
        @endif
    </div>
</div>

<!-- Modal de génération des factures -->
<div class="modal fade" id="genererFacturesModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-file-invoice"></i> Générer les factures
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="genererFacturesForm" action="{{ route('factures.generer-mois') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        <strong>Système congolais :</strong> Les factures sont générées pour le mois écoulé. 
                        Par exemple, en octobre on facture septembre.
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="mois" class="form-label">Mois à facturer <span class="text-danger">*</span></label>
                            <select class="form-select" id="mois" name="mois" required>
                                <option value="">Sélectionner le mois</option>
                                <option value="1">Janvier</option>
                                <option value="2">Février</option>
                                <option value="3">Mars</option>
                                <option value="4">Avril</option>
                                <option value="5">Mai</option>
                                <option value="6">Juin</option>
                                <option value="7">Juillet</option>
                                <option value="8">Août</option>
                                <option value="9" {{ now()->month == 10 ? 'selected' : '' }}>Septembre</option>
                                <option value="10" {{ now()->month == 11 ? 'selected' : '' }}>Octobre</option>
                                <option value="11" {{ now()->month == 12 ? 'selected' : '' }}>Novembre</option>
                                <option value="12" {{ now()->month == 1 ? 'selected' : '' }}>Décembre</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="annee" class="form-label">Année <span class="text-danger">*</span></label>
                            <select class="form-select" id="annee" name="annee" required>
                                @for($year = now()->year - 1; $year <= now()->year + 1; $year++)
                                    <option value="{{ $year }}" {{ $year == now()->year ? 'selected' : '' }}>
                                        {{ $year }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                    </div>

                    <!-- Zone d'information sur les factures existantes -->
                    <div id="verificationResultat" class="alert d-none">
                        <!-- Contenu généré par JavaScript -->
                    </div>

                    <div class="form-text">
                        <i class="fas fa-shield-alt"></i>
                        <strong>Protection anti-doublon :</strong> Le système vérifie automatiquement qu'aucune facture 
                        n'existe déjà pour la même période et le même locataire.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="button" class="btn btn-info" id="verifierBtn">
                        <i class="fas fa-search"></i> Vérifier
                    </button>
                    <button type="submit" class="btn btn-primary" id="genererBtn" disabled>
                        <i class="fas fa-file-invoice"></i> Générer les factures
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const moisSelect = document.getElementById('mois');
    const anneeSelect = document.getElementById('annee');
    const verifierBtn = document.getElementById('verifierBtn');
    const genererBtn = document.getElementById('genererBtn');
    const verificationResultat = document.getElementById('verificationResultat');

    // Fonction pour vérifier les doublons
    function verifierDoublons() {
        const mois = moisSelect.value;
        const annee = anneeSelect.value;

        if (!mois || !annee) {
            verificationResultat.className = 'alert alert-warning d-block';
            verificationResultat.innerHTML = '<i class="fas fa-exclamation-triangle"></i> Veuillez sélectionner le mois et l\'année.';
            genererBtn.disabled = true;
            return;
        }

        // Afficher un loader
        verificationResultat.className = 'alert alert-info d-block';
        verificationResultat.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Vérification en cours...';
        genererBtn.disabled = true;

        // Appel AJAX pour vérifier
        fetch('{{ route("factures.verifier-doublons") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ mois: parseInt(mois), annee: parseInt(annee) })
        })
        .then(response => response.json())
        .then(data => {
            if (data.peut_generer) {
                verificationResultat.className = 'alert alert-success d-block';
                verificationResultat.innerHTML = `
                    <i class="fas fa-check-circle"></i>
                    <strong>Prêt à générer !</strong><br>
                    • Période : ${data.periode}<br>
                    • Loyers actifs : ${data.loyers_actifs}<br>
                    • Factures existantes : ${data.factures_existantes}<br>
                    • Factures à créer : <strong>${data.factures_a_creer}</strong>
                `;
                genererBtn.disabled = false;
            } else {
                verificationResultat.className = 'alert alert-warning d-block';
                verificationResultat.innerHTML = `
                    <i class="fas fa-exclamation-triangle"></i>
                    <strong>Aucune facture à créer</strong><br>
                    • Période : ${data.periode}<br>
                    • Loyers actifs : ${data.loyers_actifs}<br>
                    • Factures existantes : ${data.factures_existantes}<br>
                    Toutes les factures ont déjà été générées pour cette période.
                `;
                genererBtn.disabled = true;
            }
        })
        .catch(error => {
            verificationResultat.className = 'alert alert-danger d-block';
            verificationResultat.innerHTML = '<i class="fas fa-times-circle"></i> Erreur lors de la vérification.';
            genererBtn.disabled = true;
        });
    }

    // Event listeners
    verifierBtn.addEventListener('click', verifierDoublons);
    
    // Auto-vérification quand les valeurs changent
    moisSelect.addEventListener('change', () => {
        verificationResultat.classList.add('d-none');
        genererBtn.disabled = true;
    });
    
    anneeSelect.addEventListener('change', () => {
        verificationResultat.classList.add('d-none');
        genererBtn.disabled = true;
    });

    // Suggestion du mois précédent au chargement
    const moisCourant = new Date().getMonth() + 1;
    const moisPrecedent = moisCourant === 1 ? 12 : moisCourant - 1;
    
    if (!moisSelect.value) {
        moisSelect.value = moisPrecedent;
    }

    // === NOUVELLES FONCTIONNALITÉS ===
    
    // Fonction pour partager via WhatsApp
    function partagerWhatsApp(telephone, prenom, nom, numeroFacture, montant, mois, echeance) {
        // Nettoyer le numéro de téléphone
        const numeroClean = telephone.replace(/[^\d+]/g, '');
        
        // Préparer le message avec les détails de la facture
        const civilite = prenom && prenom.trim() !== '' ? 'Mr/Mme' : 'Mr/Mme';
        const nomComplet = prenom && prenom.trim() !== '' ? `${prenom} ${nom}` : nom;
        
        const message = `Bonjour ${civilite} ${nomComplet},

Une facture a été générée à votre nom.

📄 Numéro de facture : ${numeroFacture}
💰 Montant : ${montant} FCFA
📅 Mois du loyer : ${mois}
⏰ Date d'échéance : ${echeance}

Merci de procéder au règlement avant la date d'échéance. La facture PDF sera jointe à ce message.

Cordialement,
La Bonte Immo`;
        
        // Ouvrir WhatsApp avec le message prérempli
        const urlWhatsApp = `https://wa.me/${numeroClean}?text=${encodeURIComponent(message)}`;
        window.open(urlWhatsApp, '_blank');
    }
    
    // Fonction de filtrage des factures
    function filtrerFactures() {
        const filtreStatut = document.getElementById('filtreStatut').value.toLowerCase();
        const filtreMois = document.getElementById('filtreMois').value;
        const rechercheText = document.getElementById('rechercheFacture').value.toLowerCase();
        
        const lignes = document.querySelectorAll('tbody tr');
        let compteurVisible = 0;
        
        lignes.forEach(ligne => {
            let afficher = true;
            
            // Filtre par statut
            if (filtreStatut !== '') {
                const statutClasse = ligne.className;
                if (filtreStatut === 'en_retard' && !statutClasse.includes('table-danger')) {
                    afficher = false;
                } else if (filtreStatut === 'paye' && !statutClasse.includes('table-success')) {
                    afficher = false;
                } else if (filtreStatut === 'non_paye' && (statutClasse.includes('table-success') || statutClasse.includes('table-danger'))) {
                    afficher = false;
                }
            }
            
            // Filtre par mois
            if (filtreMois !== '' && afficher) {
                const cellulePeriode = ligne.querySelector('td:nth-child(4)');
                if (cellulePeriode) {
                    const periode = cellulePeriode.textContent;
                    const moisTexte = periode.split(' ')[0];
                    const moisNumerique = {
                        'janvier': '1', 'février': '2', 'mars': '3', 'avril': '4',
                        'mai': '5', 'juin': '6', 'juillet': '7', 'août': '8',
                        'septembre': '9', 'octobre': '10', 'novembre': '11', 'décembre': '12'
                    };
                    if (moisNumerique[moisTexte.toLowerCase()] !== filtreMois) {
                        afficher = false;
                    }
                }
            }
            
            // Recherche textuelle
            if (rechercheText !== '' && afficher) {
                const texte = ligne.textContent.toLowerCase();
                if (!texte.includes(rechercheText)) {
                    afficher = false;
                }
            }
            
            // Afficher ou masquer la ligne
            ligne.style.display = afficher ? '' : 'none';
            if (afficher) compteurVisible++;
        });
        
        // Afficher un message si aucun résultat
        let messageNoResult = document.getElementById('messageNoResult');
        if (compteurVisible === 0) {
            if (!messageNoResult) {
                messageNoResult = document.createElement('tr');
                messageNoResult.id = 'messageNoResult';
                messageNoResult.innerHTML = '<td colspan="7" class="text-center py-4"><i class="fas fa-search fa-2x text-muted mb-2"></i><br>Aucune facture ne correspond à vos critères de recherche.</td>';
                document.querySelector('tbody').appendChild(messageNoResult);
            }
        } else if (messageNoResult) {
            messageNoResult.remove();
        }
    }
    
    // Event listeners pour les filtres
    document.getElementById('filtreStatut').addEventListener('change', filtrerFactures);
    document.getElementById('filtreMois').addEventListener('change', filtrerFactures);
    document.getElementById('rechercheFacture').addEventListener('input', filtrerFactures);
    
    // Bouton pour vider la recherche
    document.getElementById('btnClearSearch').addEventListener('click', () => {
        document.getElementById('rechercheFacture').value = '';
        document.getElementById('filtreStatut').value = '';
        document.getElementById('filtreMois').value = '';
        filtrerFactures();
    });
    
    // Rendre la fonction partagerWhatsApp globale
    window.partagerWhatsApp = partagerWhatsApp;
});
</script>

@endsection