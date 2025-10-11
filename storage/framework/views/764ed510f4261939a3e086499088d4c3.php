

<?php $__env->startSection('title', 'Factures et Paiements'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Factures et Paiements</h1>
    <div class="d-flex gap-2">
        <!-- Bouton pour générer les factures -->
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#genererFacturesModal">
            <i class="fas fa-file-invoice"></i> Générer factures
        </button>
        <a href="<?php echo e(route('paiements.create')); ?>" class="btn btn-success">
            <i class="fas fa-plus"></i> Nouveau paiement
        </a>
    </div>
</div>

<?php if(session('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?php echo e(session('success')); ?>

        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if(session('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?php echo e(session('error')); ?>

        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<!-- Statistiques rapides -->
<div class="row mb-3">
    <div class="col-md-4">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">Non payées</h6>
                        <h4 id="stat-non-payees"><?php echo e($factures->where('statut_paiement', 'non_paye')->count()); ?></h4>
                    </div>
                    <i class="fas fa-clock fa-2x"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-danger text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">En retard</h6>
                        <h4 id="stat-en-retard"><?php echo e($factures->filter(function($f) { return $f->estEnRetard(); })->count()); ?></h4>
                    </div>
                    <i class="fas fa-exclamation-triangle fa-2x"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">Payées</h6>
                        <h4 id="stat-payees"><?php echo e($factures->filter(function($f) { return $f->estPayee(); })->count()); ?></h4>
                    </div>
                    <i class="fas fa-check-circle fa-2x"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-4">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">Montant total</h6>
                        <h4 id="stat-montant-total"><?php echo e(number_format($factures->sum('montant'), 0, ',', ' ')); ?> CDF</h4>
                    </div>
                    <i class="fas fa-dollar-sign fa-2x"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">Montant payé</h6>
                        <h4 id="stat-montant-paye"><?php echo e(number_format($factures->filter(function($f) { return $f->estPayee(); })->sum('montant'), 0, ',', ' ')); ?> CDF</h4>
                    </div>
                    <i class="fas fa-money-bill-wave fa-2x"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-secondary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">Montant non payé</h6>
                        <h4 id="stat-montant-non-paye"><?php echo e(number_format($factures->where('statut_paiement', 'non_paye')->sum('montant'), 0, ',', ' ')); ?> CDF</h4>
                    </div>
                    <i class="fas fa-money-bill-alt fa-2x"></i>
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
        <?php if($factures->count() > 0): ?>
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
                        <?php $__currentLoopData = $factures; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $facture): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr class="<?php echo e($facture->estEnRetard() ? 'table-danger' : ($facture->estPayee() ? 'table-success' : '')); ?>">
                            <td>
                                <strong><?php echo e($facture->numero_facture); ?></strong>
                                <br>
                                <small class="text-muted"><?php echo e($facture->created_at->format('d/m/Y')); ?></small>
                            </td>
                            <td>
                                <div>
                                    <strong><?php echo e($facture->locataire->nom); ?> <?php echo e($facture->locataire->prenom); ?></strong>
                                    <br>
                                    <small class="text-muted"><?php echo e($facture->locataire->telephone); ?></small>
                                </div>
                            </td>
                            <td>
                                <div>
                                    <strong><?php echo e($facture->loyer->appartement->immeuble->nom); ?></strong>
                                    <br>
                                    <small class="text-muted">Apt <?php echo e($facture->loyer->appartement->numero); ?></small>
                                </div>
                            </td>
                            <td>
                                <strong><?php echo e($facture->getMoisNom()); ?> <?php echo e($facture->annee); ?></strong>
                                <br>
                                <small class="text-muted">Échéance: <?php echo e($facture->date_echeance->format('d/m/Y')); ?></small>
                            </td>
                            <td>
                                <strong><?php echo e(number_format($facture->montant, 0, ',', ' ')); ?> CDF</strong>
                                <?php if($facture->montant_paye > 0): ?>
                                    <br>
                                    <small class="text-success">Payé: <?php echo e(number_format($facture->montant_paye, 0, ',', ' ')); ?> CDF</small>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if($facture->estPayee()): ?>
                                    <span class="badge bg-success">Payée</span>
                                <?php elseif($facture->estPartielementPayee()): ?>
                                    <span class="badge bg-warning">Partielle</span>
                                <?php elseif($facture->estEnRetard()): ?>
                                    <span class="badge bg-danger">En retard</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Non payée</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <!-- Bouton PDF -->
                                    <a href="<?php echo e(route('factures.export-pdf', $facture)); ?>" 
                                       class="btn btn-outline-primary btn-sm" 
                                       target="_blank">
                                        <i class="fas fa-file-pdf"></i> PDF
                                    </a>
                                    
                                    <!-- Bouton WhatsApp -->
                                    <?php if($facture->locataire && $facture->locataire->telephone): ?>
                                    <button type="button" 
                                            class="btn btn-outline-success btn-sm" 
                                            onclick="partagerWhatsApp('<?php echo e($facture->locataire->telephone); ?>', '<?php echo e($facture->locataire->prenom ?? ''); ?>', '<?php echo e($facture->locataire->nom); ?>', '<?php echo e($facture->numero_facture); ?>', '<?php echo e(number_format($facture->montant, 0, ',', ' ')); ?>', '<?php echo e($facture->getMoisNom()); ?> <?php echo e($facture->annee); ?>', '<?php echo e($facture->date_echeance->format('d/m/Y')); ?>')">
                                        <i class="fab fa-whatsapp"></i> WhatsApp
                                    </button>
                                    <?php endif; ?>
                                    
                                    <?php if(!$facture->estPayee()): ?>
                                        <button type="button" 
                                                class="btn btn-success btn-sm" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#modalPaiement<?php echo e($facture->id); ?>">
                                            <i class="fas fa-credit-card"></i> Payer
                                        </button>
                                    <?php endif; ?>
                                    
                                    <a href="<?php echo e(route('factures.show', $facture)); ?>" 
                                       class="btn btn-outline-info btn-sm">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </div>

                                <!-- Modal de paiement -->
                                <?php if(!$facture->estPayee()): ?>
                                <div class="modal fade" id="modalPaiement<?php echo e($facture->id); ?>" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Régler la facture <?php echo e($facture->numero_facture); ?></h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <form method="POST" action="<?php echo e(route('factures.marquer-payee', $facture)); ?>">
                                                <?php echo csrf_field(); ?>
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label class="form-label">Montant à payer</label>
                                                        <input type="number" class="form-control" name="montant"
                                                               value="<?php echo e($facture->montant - $facture->montant_paye); ?>" 
                                                               min="1" max="<?php echo e($facture->montant - $facture->montant_paye); ?>" required>
                                                        <div class="form-text">
                                                            Montant restant: <?php echo e(number_format($facture->montant - $facture->montant_paye, 0, ',', ' ')); ?> CDF
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
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="text-center py-5">
                <i class="fas fa-file-invoice fa-4x text-muted mb-3"></i>
                <h4 class="text-muted">Aucune facture trouvée</h4>
                <p class="text-muted">Aucune facture disponible pour le moment.</p>
            </div>
        <?php endif; ?>
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
            <form id="genererFacturesForm" action="<?php echo e(route('factures.generer-mois')); ?>" method="POST">
                <?php echo csrf_field(); ?>
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
                                <option value="9" <?php echo e(now()->month == 10 ? 'selected' : ''); ?>>Septembre</option>
                                <option value="10" <?php echo e(now()->month == 11 ? 'selected' : ''); ?>>Octobre</option>
                                <option value="11" <?php echo e(now()->month == 12 ? 'selected' : ''); ?>>Novembre</option>
                                <option value="12" <?php echo e(now()->month == 1 ? 'selected' : ''); ?>>Décembre</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="annee" class="form-label">Année <span class="text-danger">*</span></label>
                            <select class="form-select" id="annee" name="annee" required>
                                <?php for($year = now()->year - 1; $year <= now()->year + 1; $year++): ?>
                                    <option value="<?php echo e($year); ?>" <?php echo e($year == now()->year ? 'selected' : ''); ?>>
                                        <?php echo e($year); ?>

                                    </option>
                                <?php endfor; ?>
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
    window.partagerWhatsApp = function(telephone, prenom, nom, numeroFacture, montant, mois, echeance) {
        const numeroClean = telephone.replace(/[^\d+]/g, '');
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
        
        const urlWhatsApp = `https://wa.me/${numeroClean}?text=${encodeURIComponent(message)}`;
        window.open(urlWhatsApp, '_blank');
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
});
</script>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="<?php echo e(asset('js/filtres-factures.js')); ?>"></script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\immo\resources\views/paiements/index.blade.php ENDPATH**/ ?>