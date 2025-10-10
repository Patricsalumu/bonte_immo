@extends('layouts.app')

@section('title', 'Notifications aux Locataires')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">
        <i class="fas fa-bell"></i> Notifications aux Locataires
    </h1>
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

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-paper-plane"></i> Envoyer un Rappel
                </h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('notifications.envoyer') }}" id="formNotification">
                    @csrf

                    <div class="mb-4">
                        <label class="form-label fw-bold">Destinataires <span class="text-danger">*</span></label>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="type_destinataires" 
                                           id="tous" value="tous" checked>
                                    <label class="form-check-label" for="tous">
                                        <strong>Tous les locataires actifs</strong>
                                        <br><small class="text-muted">Envoyer à tous les locataires</small>
                                    </label>
                                </div>

                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="type_destinataires" 
                                           id="retards" value="retards">
                                    <label class="form-check-label" for="retards">
                                        <strong>Locataires en retard</strong>
                                        <br><small class="text-muted">Uniquement ceux avec des factures impayées</small>
                                    </label>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="type_destinataires" 
                                           id="immeuble" value="immeuble">
                                    <label class="form-check-label" for="immeuble">
                                        <strong>Locataires d'un immeuble</strong>
                                        <br><small class="text-muted">Sélectionner un immeuble spécifique</small>
                                    </label>
                                </div>

                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="type_destinataires" 
                                           id="selection" value="selection">
                                    <label class="form-check-label" for="selection">
                                        <strong>Sélection manuelle</strong>
                                        <br><small class="text-muted">Choisir individuellement</small>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sélection immeuble -->
                    <div class="mb-3" id="div_immeuble" style="display: none;">
                        <label for="immeuble_id" class="form-label">Immeuble</label>
                        <select name="immeuble_id" id="immeuble_id" class="form-select">
                            <option value="">Sélectionner un immeuble</option>
                            @foreach($immeubles as $immeuble)
                                <option value="{{ $immeuble->id }}">{{ $immeuble->nom }} - {{ $immeuble->adresse }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Sélection manuelle -->
                    <div class="mb-3" id="div_selection" style="display: none;">
                        <label class="form-label">Locataires</label>
                        <div id="liste_locataires" class="border rounded p-3" style="max-height: 200px; overflow-y: auto;">
                            <!-- Les locataires seront chargés via JavaScript -->
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="mode_envoi" class="form-label">Mode d'envoi <span class="text-danger">*</span></label>
                            <select name="mode_envoi" id="mode_envoi" class="form-select" required>
                                <option value="">Sélectionner...</option>
                                <option value="sms">SMS</option>
                                <option value="email">Email</option>
                                <option value="whatsapp">WhatsApp</option>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="objet" class="form-label">Objet <span class="text-danger">*</span></label>
                            <input type="text" 
                                   name="objet" 
                                   id="objet" 
                                   class="form-control" 
                                   placeholder="Ex: Rappel de paiement de loyer"
                                   required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="message" class="form-label">Message <span class="text-danger">*</span></label>
                        <textarea name="message" 
                                  id="message" 
                                  class="form-control" 
                                  rows="5" 
                                  placeholder="Saisissez votre message de rappel..."
                                  required></textarea>
                        <div class="form-text">
                            <span id="compteur_caracteres">0</span>/500 caractères
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-outline-secondary" onclick="previsualiser()">
                            <i class="fas fa-eye"></i> Prévisualiser
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-paper-plane"></i> Envoyer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <!-- Modèles de messages -->
        <div class="card mb-3">
            <div class="card-header">
                <h6 class="card-title mb-0">
                    <i class="fas fa-templates"></i> Modèles de messages
                </h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <button type="button" class="btn btn-outline-primary btn-sm" onclick="utiliserModele('rappel_simple')">
                        Rappel simple
                    </button>
                    <button type="button" class="btn btn-outline-warning btn-sm" onclick="utiliserModele('rappel_urgent')">
                        Rappel urgent
                    </button>
                    <button type="button" class="btn btn-outline-info btn-sm" onclick="utiliserModele('information')">
                        Information générale
                    </button>
                    <button type="button" class="btn btn-outline-success btn-sm" onclick="utiliserModele('felicitations')">
                        Félicitations
                    </button>
                </div>
            </div>
        </div>

        <!-- Statistiques -->
        <div class="card">
            <div class="card-header">
                <h6 class="card-title mb-0">
                    <i class="fas fa-chart-bar"></i> Statistiques
                </h6>
            </div>
            <div class="card-body">
                <div class="mb-2">
                    <small class="text-muted">Total locataires actifs:</small>
                    <strong class="float-end">{{ $locatairesEnRetard->count() + 50 }}</strong>
                </div>
                <div class="mb-2">
                    <small class="text-muted">Locataires en retard:</small>
                    <strong class="float-end text-danger">{{ $locatairesEnRetard->count() }}</strong>
                </div>
                <div class="mb-2">
                    <small class="text-muted">Immeubles actifs:</small>
                    <strong class="float-end">{{ $immeubles->count() }}</strong>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de prévisualisation -->
<div class="modal fade" id="modalPrevisualisation" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Prévisualisation du message</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <strong>Objet:</strong>
                    <div id="preview_objet" class="border p-2 bg-light"></div>
                </div>
                <div class="mb-3">
                    <strong>Message:</strong>
                    <div id="preview_message" class="border p-2 bg-light" style="white-space: pre-line;"></div>
                </div>
                <div class="mb-3">
                    <strong>Signature automatique:</strong>
                    <div class="border p-2 bg-light">
                        Cordialement,<br>
                        La Bonte Immo<br>
                        Avenue de la révolution, Q. Industriel C. Lshi
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal" onclick="document.getElementById('formNotification').submit()">
                    <i class="fas fa-paper-plane"></i> Envoyer maintenant
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Gestion des types de destinataires
document.querySelectorAll('input[name="type_destinataires"]').forEach(radio => {
    radio.addEventListener('change', function() {
        // Cacher tous les divs
        document.getElementById('div_immeuble').style.display = 'none';
        document.getElementById('div_selection').style.display = 'none';
        
        // Afficher le div correspondant
        if (this.value === 'immeuble') {
            document.getElementById('div_immeuble').style.display = 'block';
        } else if (this.value === 'selection') {
            document.getElementById('div_selection').style.display = 'block';
            chargerTousLocataires();
        } else if (this.value === 'retards') {
            // Optionnel: charger la liste des locataires en retard
        }
    });
});

// Gestion de l'immeuble sélectionné
document.getElementById('immeuble_id').addEventListener('change', function() {
    if (document.querySelector('input[name="type_destinataires"]:checked').value === 'immeuble') {
        chargerLocatairesImmeuble(this.value);
    }
});

// Compteur de caractères
document.getElementById('message').addEventListener('input', function() {
    const count = this.value.length;
    document.getElementById('compteur_caracteres').textContent = count;
    
    if (count > 500) {
        this.style.borderColor = 'red';
    } else {
        this.style.borderColor = '';
    }
});

// Modèles de messages
function utiliserModele(type) {
    const objet = document.getElementById('objet');
    const message = document.getElementById('message');
    
    switch(type) {
        case 'rappel_simple':
            objet.value = 'Rappel de paiement de loyer';
            message.value = 'Cher(e) locataire,\n\nNous vous rappelons que votre loyer du mois en cours est maintenant dû. Merci de procéder au paiement dans les plus brefs délais.\n\nMerci pour votre compréhension.';
            break;
            
        case 'rappel_urgent':
            objet.value = 'URGENT - Paiement de loyer en retard';
            message.value = 'Cher(e) locataire,\n\nVotre loyer est en retard de paiement. Nous vous demandons de régulariser votre situation rapidement pour éviter les mesures supplémentaires.\n\nMerci de nous contacter si vous rencontrez des difficultés.';
            break;
            
        case 'information':
            objet.value = 'Information importante';
            message.value = 'Cher(e) locataire,\n\nNous vous informons que [information à personnaliser].\n\nN\'hésitez pas à nous contacter pour toute question.';
            break;
            
        case 'felicitations':
            objet.value = 'Merci pour votre ponctualité';
            message.value = 'Cher(e) locataire,\n\nNous tenons à vous remercier pour la ponctualité de vos paiements. Votre sérieux est très apprécié.\n\nBonne continuation.';
            break;
    }
}

// Prévisualisation
function previsualiser() {
    const objet = document.getElementById('objet').value;
    const message = document.getElementById('message').value;
    
    document.getElementById('preview_objet').textContent = objet || '[Objet vide]';
    document.getElementById('preview_message').textContent = message || '[Message vide]';
    
    new bootstrap.Modal(document.getElementById('modalPrevisualisation')).show();
}

// Charger tous les locataires pour sélection manuelle
function chargerTousLocataires() {
    // Simulation - dans un vrai projet, faire un appel AJAX
    const locataires = [
        {id: 1, nom: 'MPIANA', prenom: 'Jean', telephone: '+243123456789'},
        {id: 2, nom: 'KASONGO', prenom: 'Marie', telephone: '+243987654321'},
        // ... autres locataires
    ];
    
    afficherListeLocataires(locataires);
}

// Charger les locataires d'un immeuble
function chargerLocatairesImmeuble(immeubleId) {
    if (!immeubleId) return;
    
    // Simulation - dans un vrai projet, faire un appel AJAX
    fetch(`/notifications/locataires-immeuble?immeuble_id=${immeubleId}`)
        .then(response => response.json())
        .then(locataires => {
            afficherListeLocataires(locataires);
        })
        .catch(error => console.error('Erreur:', error));
}

// Afficher la liste des locataires avec cases à cocher
function afficherListeLocataires(locataires) {
    const container = document.getElementById('liste_locataires');
    
    if (locataires.length === 0) {
        container.innerHTML = '<p class="text-muted">Aucun locataire trouvé.</p>';
        return;
    }
    
    let html = '';
    locataires.forEach(locataire => {
        html += `
            <div class="form-check mb-2">
                <input class="form-check-input" type="checkbox" 
                       name="locataires_selectionnes[]" 
                       value="${locataire.id}" 
                       id="locataire_${locataire.id}">
                <label class="form-check-label" for="locataire_${locataire.id}">
                    <strong>${locataire.nom} ${locataire.prenom}</strong>
                    <br><small class="text-muted">${locataire.telephone}</small>
                </label>
            </div>
        `;
    });
    
    container.innerHTML = html;
}
</script>
@endsection