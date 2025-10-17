@extends('layouts.app')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div class="d-flex gap-2">
        <!-- Bouton pour générer les factures -->
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#genererFacturesModal">
            <i class="fas fa-file-invoice"></i> Générer factures
        </button>
    </div>
</div>

<!-- Session messages: stocker dans des inputs cachés pour lecture JS (évite les injections Blade->JS directes) -->
<input type="hidden" id="session_success" value="{{ session('success') ? e(session('success')) : '' }}">
<input type="hidden" id="session_error" value="{{ session('error') ? e(session('error')) : '' }}">
<input type="hidden" id="session_whatsapp_url" value="{{ session('whatsapp_url') ? e(session('whatsapp_url')) : '' }}">
<input type="hidden" id="session_whatsapp_message" value="{{ session('whatsapp_message') ? e(session('whatsapp_message')) : '' }}">



<!-- Liste des factures -->
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">
            <i class="fas fa-file-invoice-dollar"></i> Factures de Loyer
        </h5>
    </div>
    <div class="card-body">
        <!-- Filtres et barre de recherche -->
        <form method="GET" action="{{ route('factures.index') }}" id="formFiltresFactures">
        <div class="row mb-4">
            <div class="col-md-3">
                <label for="filtreStatut" class="form-label">Filtrer par statut</label>
                <select id="filtreStatut" name="statut" class="form-select" aria-label="Filtrer par statut">
                    <option value="">Tous les statuts</option>
                    <option value="non_paye">Non payées</option>
                    <option value="partielle">Partielles</option>
                    <option value="paye">Payées</option>
                    <option value="en_retard">En retard</option>
                </select>
            </div>
            <div class="col-md-6">
                <label for="rechercheFacture" class="form-label">Rechercher</label>
                <div class="input-group">
                    <input type="text" id="rechercheFacture" name="search" class="form-control" 
                           placeholder="Numéro facture, client, téléphone, appartement..." aria-label="Recherche de factures">
                    <button class="btn btn-outline-secondary" type="button" id="btnClearSearch" title="Effacer la recherche">
                        <i class="fas fa-times"></i>
                    </button>
                    <button class="btn btn-primary" type="submit" id="btnServerSearch" title="Recherche serveur">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
        </div>
        <noscript>
            <div class="row mt-2">
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">Rechercher</button>
                </div>
            </div>
        </noscript>
        </form>
        @if($factures->count() > 0)
            <div class="table-responsive" data-ajax-url="{{ route('factures.ajax') }}">
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
                    @include('factures._table_rows', ['factures' => $factures])
                </table>
                <!-- Pagination -->
                <style>
                    /* Rendre les liens Previous/Next discrets : conserver le texte mais enlever le style 'gros bouton' */
                    .pagination .page-item.previous .page-link,
                    .pagination .page-item.next .page-link,
                    .pagination .page-link[aria-label="Previous"],
                    .pagination .page-link[aria-label="Next"] {
                        background: transparent !important;
                        border: none !important;
                        padding: 0.125rem 0.25rem !important;
                        min-width: 0 !important;
                        width: auto !important;
                        color: inherit !important;
                    }

                    /* Réduire l'icône si présente et masquer l'icône caret (cible aussi les svg avec classes w-5/h-5) */
                    .pagination svg,
                    .pagination .w-5,
                    .pagination .h-5,
                    .pagination .page-link svg,
                    .pagination .page-link .fa,
                    .pagination .page-link .sr-only {
                        display: none !important;
                    }
                </style>
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
    console.log("✅ Script principal chargé avec succès");
    
    // === CODE POUR LE MODAL DE GÉNÉRATION DES FACTURES ===
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

        // Requête AJAX pour vérifier
        fetch('/factures/verifier-doublons', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ mois: mois, annee: annee })
        })
        .then(response => response.json())
        .then(data => {
            if (data.doublons > 0) {
                verificationResultat.className = 'alert alert-warning d-block';
                verificationResultat.innerHTML = `
                    <i class="fas fa-exclamation-triangle"></i> 
                    ${data.doublons} facture(s) existent déjà pour ${data.mois_nom} ${annee}. 
                    <strong>Êtes-vous sûr de vouloir continuer ?</strong>
                `;
                genererBtn.disabled = false;
                genererBtn.textContent = 'Continuer quand même';
                genererBtn.className = 'btn btn-warning';
            } else {
                verificationResultat.className = 'alert alert-success d-block';
                verificationResultat.innerHTML = `
                    <i class="fas fa-check-circle"></i> 
                    Aucune facture trouvée pour ${data.mois_nom} ${annee}. 
                    Prêt à générer ${data.loyers_actifs} facture(s).
                `;
                genererBtn.disabled = false;
                genererBtn.textContent = 'Générer les factures';
                genererBtn.className = 'btn btn-success';
            }
        })
        .catch(error => {
            verificationResultat.className = 'alert alert-danger d-block';
            verificationResultat.innerHTML = '<i class="fas fa-exclamation-circle"></i> Erreur lors de la vérification.';
            genererBtn.disabled = true;
        });
    }

    // Event listeners pour le modal
    if (verifierBtn) {
        verifierBtn.addEventListener('click', verifierDoublons);
    }

    if (moisSelect && anneeSelect) {
        moisSelect.addEventListener('change', () => {
            verificationResultat.classList.add('d-none');
            genererBtn.disabled = true;
        });
        
        anneeSelect.addEventListener('change', () => {
            verificationResultat.classList.add('d-none');
            genererBtn.disabled = true;
        });

        // Suggestion du mois précédent au chargement
        if (!moisSelect.value) {
            const moisCourant = new Date().getMonth() + 1;
            const moisPrecedent = moisCourant === 1 ? 12 : moisCourant - 1;
            moisSelect.value = moisPrecedent;
        }
    }

    // === FONCTION WHATSAPP ===
    window.partagerWhatsApp = function(telephone, prenom, nom, numeroFacture, montant, mois, echeance, factureId) {
        const numeroClean = telephone.replace(/[^\d+]/g, '');
        const civilite = prenom && prenom.trim() !== '' ? 'Mr/Mme' : 'Mr/Mme';
        const nomComplet = prenom && prenom.trim() !== '' ? `${prenom} ${nom}` : nom;

        // Construction du lien PDF public avec l'ID
        const pdfUrl = `${window.location.origin}/public/factures/${factureId}/pdf`;

    const companyName = "{{ e(config('company.name')) }}";
    const message = `Bonjour ${civilite} ${nomComplet},\n\nUne facture de loyer a été générée à votre nom.\n\n📄 Numéro de facture : ${numeroFacture}\n💰 Montant : ${montant} $\n📅 Mois du loyer : ${mois}\n⏰ Date d'échéance : ${echeance}\n\nVous pouvez télécharger votre facture PDF ici : ${pdfUrl}\n\nMerci de procéder au règlement avant la date d'échéance.\n\nCordialement,\n${companyName}`;

        const urlWhatsApp = `https://wa.me/${numeroClean}?text=${encodeURIComponent(message)}`;
        window.open(urlWhatsApp, '_blank');
    };

    // === NOUVELLE FONCTION WHATSAPP AVEC DATA ATTRIBUTES ===
    window.partagerWhatsAppData = function(button) {
        const telephone = button.dataset.telephone;
        const prenom = button.dataset.prenom;
        const nom = button.dataset.nom;
        const numeroFacture = button.dataset.numero;
        const montant = button.dataset.montant;
        const mois = button.dataset.mois;
        const echeance = button.dataset.echeance;
        const factureId = button.dataset.id;
        
        partagerWhatsApp(telephone, prenom, nom, numeroFacture, montant, mois, echeance, factureId);
    };

    // === FONCTIONS DE DEBUG ===
    window.testFiltrage = function() {
        if (window.debugFiltres) {
            window.debugFiltres.test();
        } else {
            console.error("Le système de filtrage n'est pas chargé");
        }
    };

    window.testElements = function() {
        console.log("=== TEST ÉLÉMENTS ===");
        const lignes = document.querySelectorAll('tbody tr:not(#messageNoResult)');
        console.log("Nombre de lignes:", lignes.length);
        
        lignes.forEach((ligne, index) => {
            console.log(`Ligne ${index + 1}:`, {
                classe: ligne.className,
                visible: ligne.style.display !== 'none'
            });
        });
    };
    
    // Gestion des modes de paiement
    document.addEventListener('DOMContentLoaded', function() {
        // Pour tous les modals de paiement
        document.querySelectorAll('[id^="modalPaiement"]').forEach(modal => {
            const factureId = modal.id.replace('modalPaiement', '');
            const selectMode = modal.querySelector('select[name="mode_paiement"]');
            const inputMontant = modal.querySelector('input[name="montant"]');
            const garantieInfo = modal.querySelector(`#garantie-info-${factureId}`);
            
            if (selectMode && inputMontant) {
                selectMode.addEventListener('change', function() {
                    if (this.value === 'garantie_locative') {
                        // Afficher l'info de garantie
                        if (garantieInfo) {
                            garantieInfo.style.display = 'block';
                        }
                        
                        // Extraire le montant de garantie disponible depuis le texte de l'option
                        const optionText = this.selectedOptions[0].textContent;
                        const garantieMatch = optionText.match(/\(([0-9\s,]+)\s+$/);
                        
                        if (garantieMatch) {
                            const garantieDisponible = parseFloat(garantieMatch[1].replace(/[\s,]/g, ''));
                            const montantRestant = parseFloat(inputMontant.getAttribute('data-montant-restant'));
                            const montantMax = Math.min(montantRestant, garantieDisponible);
                            
                            inputMontant.setAttribute('max', montantMax);
                            
                            // Si le montant actuel dépasse la garantie, l'ajuster
                            if (parseFloat(inputMontant.value) > montantMax) {
                                inputMontant.value = montantMax;
                            }
                        }
                    } else {
                        // Masquer l'info de garantie
                        if (garantieInfo) {
                            garantieInfo.style.display = 'none';
                        }
                        
                        // Remettre le montant max original
                        const montantRestant = inputMontant.getAttribute('data-montant-restant');
                        inputMontant.setAttribute('max', montantRestant);
                    }
                });
            }
        });
    });
});
</script>

<!-- Modal pour afficher le message WhatsApp complet -->
@if(session('whatsapp_message'))
<div class="modal fade" id="whatsappMessageModal" tabindex="-1" aria-labelledby="whatsappMessageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="whatsappMessageModalLabel">
                    <i class="fab fa-whatsapp text-success"></i> Message WhatsApp
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> 
                    Ce message sera envoyé au locataire pour confirmer le paiement
                </div>
                <div class="bg-light p-3 rounded">
                    <pre class="mb-0">{{ session('whatsapp_message') }}</pre>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                <a href="{{ session('whatsapp_url') }}" target="_blank" class="btn btn-success">
                    <i class="fab fa-whatsapp"></i> Envoyer via WhatsApp
                </a>
            </div>
        </div>
    </div>
</div>
@endif

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Lecture sécurisée des messages de session stockés dans inputs cachés
    const msgSuccess = document.getElementById('session_success') ? document.getElementById('session_success').value : '';
    const msgError = document.getElementById('session_error') ? document.getElementById('session_error').value : '';
    const whatsappUrl = document.getElementById('session_whatsapp_url') ? document.getElementById('session_whatsapp_url').value : '';
    const whatsappMessage = document.getElementById('session_whatsapp_message') ? document.getElementById('session_whatsapp_message').value : '';

    if (msgSuccess) {
        const alert = document.createElement('div');
        alert.className = 'alert alert-success alert-dismissible fade show';
        alert.role = 'alert';
        alert.innerHTML = msgSuccess + '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
        document.querySelector('.container')?.prepend(alert);
    }

    if (msgError) {
        const alert = document.createElement('div');
        alert.className = 'alert alert-danger alert-dismissible fade show';
        alert.role = 'alert';
        alert.innerHTML = msgError + '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
        document.querySelector('.container')?.prepend(alert);
    }

    if (whatsappUrl) {
        const info = document.createElement('div');
        info.className = 'alert alert-info alert-dismissible fade show';
        info.role = 'alert';
        info.innerHTML = `
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <i class="fab fa-whatsapp me-2"></i>
                    <strong>Message WhatsApp prêt !</strong>
                </div>
                <div>
                    <a href="${whatsappUrl}" target="_blank" class="btn btn-success btn-sm me-2">
                        <i class="fab fa-whatsapp"></i> Envoyer via WhatsApp
                    </a>
                    <button type="button" class="btn btn-outline-info btn-sm" data-bs-toggle="modal" data-bs-target="#whatsappMessageModal">
                        <i class="fas fa-eye"></i> Voir le message
                    </button>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        document.querySelector('.container')?.prepend(info);
    }

    // Si on a un message WhatsApp complet, ouvrir le modal automatiquement
    if (whatsappMessage && document.getElementById('whatsappMessageModal')) {
        const modal = new bootstrap.Modal(document.getElementById('whatsappMessageModal'));
        modal.show();
    }
    // Focus automatique sur le premier champ de la modal paiement à l'ouverture
    document.querySelectorAll('[id^="modalPaiement"]').forEach(function(modal) {
        modal.addEventListener('shown.bs.modal', function () {
            const input = modal.querySelector('input, select, textarea, button');
            if (input) input.focus();
        });
    });
});
</script>
@endpush

<!-- Modal unique de paiement (réutilisable) -->
<div class="modal fade" id="modalPaiement" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Régler la facture <span id="modalFactureNumero"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" id="formModalPaiement" action="">
                @csrf
                <div class="modal-body">
                    <!-- Bloc infos facture -->
                    <div class="alert alert-secondary mb-3 p-2">
                        <div><strong>Numéro :</strong> <span id="modalInfoNumero"></span></div>
                        <div><strong>Locataire :</strong> <span id="modalInfoLocataire"></span></div>
                        <div><strong>Immeuble :</strong> <span id="modalInfoImmeuble"></span></div>
                        <div><strong>Appartement :</strong> <span id="modalInfoAppartement"></span></div>
                        <div><strong>Mois :</strong> <span id="modalInfoMois"></span></div>
                    </div>
                    <input type="hidden" name="_method" value="POST">
                    <div class="mb-3">
                        <label class="form-label">Montant à payer</label>
                        <input type="number" class="form-control" name="montant" id="modalMontant"
                               min="0.01" step="0.01" required>
                        <div class="form-text" id="modalMontantInfo"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Mode de paiement *</label>
                        <select name="mode_paiement" id="modalModePaiement" class="form-select" required>
                            <option value="">Sélectionner...</option>
                            <option value="cash">Espèces</option>
                            <option value="virement">Virement bancaire</option>
                            <option value="mobile_money">Mobile Money</option>
                            <option value="garantie_locative">Garantie locative</option>
                        </select>
                        <div class="form-text d-none text-info" id="modalGarantieInfo">
                            Garantie locative disponible : <strong id="modalGarantieDisponible"></strong>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Référence</label>
                        <input type="text" name="reference" id="modalReference" class="form-control" placeholder="Numéro de transaction, référence...">
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Quand on clique sur un bouton 'Payer', pré-remplir le modal unique
    document.querySelectorAll('.btn-open-modal-paiement').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            const factureId = this.dataset.factureId;
            const loyerId = this.dataset.loyerId;
            const numero = this.dataset.numero;
            const montant = parseFloat(this.dataset.montant) || 0;
            const montantRestant = parseFloat(this.dataset.montantRestant) || montant;
            const garantie = parseFloat(this.dataset.garantie) || 0;
            const locataire = this.dataset.locataire || '';
            const immeuble = this.dataset.immeuble || '';
            const appartement = this.dataset.appartement || '';
            const mois = this.dataset.mois || '';

            // Titre / numero facture
            document.getElementById('modalFactureNumero').textContent = numero;
            // Bloc infos
            document.getElementById('modalInfoNumero').textContent = numero || '(inconnu)';
            document.getElementById('modalInfoLocataire').textContent = locataire || '(inconnu)';
            document.getElementById('modalInfoImmeuble').textContent = immeuble || '(inconnu)';
            document.getElementById('modalInfoAppartement').textContent = appartement || '(inconnu)';
            document.getElementById('modalInfoMois').textContent = mois || '(inconnu)';

            // Action du formulaire (route marquer-payee)
            const form = document.getElementById('formModalPaiement');
            form.action = '/factures/' + factureId + '/marquer-payee';

            // Montant
            const inputMontant = document.getElementById('modalMontant');
            inputMontant.value = montantRestant.toFixed(2);
            inputMontant.setAttribute('max', montantRestant);
            document.getElementById('modalMontantInfo').textContent = 'Montant restant: ' + Number(montantRestant).toLocaleString() + ' $';

            // Garantie
            const garantieInfo = document.getElementById('modalGarantieInfo');
            const garantieDisponibleEl = document.getElementById('modalGarantieDisponible');
            if (garantie > 0) {
                garantieInfo.classList.remove('d-none');
                garantieDisponibleEl.textContent = Number(garantie).toLocaleString() + ' $';
            } else {
                garantieInfo.classList.add('d-none');
                garantieDisponibleEl.textContent = '';
            }

            // Si l'utilisateur choisit 'garantie_locative', ajuster le max
            const selectMode = document.getElementById('modalModePaiement');
            selectMode.value = 'cash';
            selectMode.onchange = function() {
                if (this.value === 'garantie_locative') {
                    const maxVal = Math.min(garantie, montantRestant);
                    inputMontant.setAttribute('max', maxVal);
                    if (parseFloat(inputMontant.value) > maxVal) {
                        inputMontant.value = maxVal.toFixed(2);
                    }
                } else {
                    inputMontant.setAttribute('max', montantRestant);
                }
            };
        });
    });
});
</script>
@endpush
