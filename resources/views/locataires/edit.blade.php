@extends('layouts.app')

@section('title', 'Modifier le Locataire')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Modifier le locataire</h1>
    <a href="{{ route('locataires.show', $locataire) }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Retour
    </a>
</div>

<form action="{{ route('locataires.update', $locataire) }}" method="POST">
    @csrf
    @method('PUT')
    
    <div class="row">
        <div class="col-md-8">
            <!-- Informations personnelles -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Informations personnelles</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nom" class="form-label">Nom <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('nom') is-invalid @enderror" 
                                   id="nom" 
                                   name="nom" 
                                   value="{{ old('nom', $locataire->nom) }}" 
                                   required>
                            @error('nom')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="prenom" class="form-label">Prénom <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('prenom') is-invalid @enderror" 
                                   id="prenom" 
                                   name="prenom" 
                                   value="{{ old('prenom', $locataire->prenom) }}" 
                                   required>
                            @error('prenom')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="date_naissance" class="form-label">Date de naissance</label>
                            <input type="date" 
                                   class="form-control @error('date_naissance') is-invalid @enderror" 
                                   id="date_naissance" 
                                   name="date_naissance" 
                                   value="{{ old('date_naissance', $locataire->date_naissance) }}">
                            @error('date_naissance')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="numero_carte_identite" class="form-label">Numéro carte d'identité</label>
                            <input type="text" 
                                   class="form-control @error('numero_carte_identite') is-invalid @enderror" 
                                   id="numero_carte_identite" 
                                   name="numero_carte_identite" 
                                   value="{{ old('numero_carte_identite', $locataire->numero_carte_identite) }}">
                            @error('numero_carte_identite')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="telephone" class="form-label">Téléphone <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('telephone') is-invalid @enderror" 
                                   id="telephone" 
                                   name="telephone" 
                                   value="{{ old('telephone', $locataire->telephone) }}" 
                                   required>
                            @error('telephone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" 
                                   class="form-control @error('email') is-invalid @enderror" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email', $locataire->email) }}">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-12 mb-3">
                            <label for="adresse" class="form-label">Adresse</label>
                            <textarea class="form-control @error('adresse') is-invalid @enderror" 
                                      id="adresse" 
                                      name="adresse" 
                                      rows="3">{{ old('adresse', $locataire->adresse) }}</textarea>
                            @error('adresse')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Informations professionnelles -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">Informations professionnelles</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="profession" class="form-label">Profession</label>
                            <input type="text" 
                                   class="form-control @error('profession') is-invalid @enderror" 
                                   id="profession" 
                                   name="profession" 
                                   value="{{ old('profession', $locataire->profession) }}">
                            @error('profession')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="employeur" class="form-label">Employeur</label>
                            <input type="text" 
                                   class="form-control @error('employeur') is-invalid @enderror" 
                                   id="employeur" 
                                   name="employeur" 
                                   value="{{ old('employeur', $locataire->employeur) }}">
                            @error('employeur')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="revenu_mensuel" class="form-label">Revenu mensuel ($)</label>
                            <input type="number" 
                                   class="form-control @error('revenu_mensuel') is-invalid @enderror" 
                                   id="revenu_mensuel" 
                                   name="revenu_mensuel" 
                                   value="{{ old('revenu_mensuel', $locataire->revenu_mensuel) }}"
                                   min="0"
                                   step="1000">
                            @error('revenu_mensuel')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                <span id="capacite_paiement_text"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact d'urgence -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">Contact d'urgence</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="contact_urgence_nom" class="form-label">Nom du contact</label>
                            <input type="text" 
                                   class="form-control @error('contact_urgence_nom') is-invalid @enderror" 
                                   id="contact_urgence_nom" 
                                   name="contact_urgence_nom" 
                                   value="{{ old('contact_urgence_nom', $locataire->contact_urgence_nom) }}">
                            @error('contact_urgence_nom')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="contact_urgence_telephone" class="form-label">Téléphone du contact</label>
                            <input type="text" 
                                   class="form-control @error('contact_urgence_telephone') is-invalid @enderror" 
                                   id="contact_urgence_telephone" 
                                   name="contact_urgence_telephone" 
                                   value="{{ old('contact_urgence_telephone', $locataire->contact_urgence_telephone) }}">
                            @error('contact_urgence_telephone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Notes -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">Notes</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="notes" class="form-label">Notes additionnelles</label>
                        <textarea class="form-control @error('notes') is-invalid @enderror" 
                                  id="notes" 
                                  name="notes" 
                                  rows="4" 
                                  placeholder="Notes sur le locataire, historique, remarques particulières...">{{ old('notes', $locataire->notes) }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Boutons d'action -->
            <div class="card mt-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="fas fa-save"></i> Enregistrer les modifications
                            </button>
                            <a href="{{ route('locataires.show', $locataire) }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Annuler
                            </a>
                        </div>
                        
                        <button type="button" 
                                class="btn btn-danger" 
                                data-bs-toggle="modal" 
                                data-bs-target="#deleteModal">
                            <i class="fas fa-trash"></i> Supprimer
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <!-- Statut et appartement -->
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Statut et logement</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="actif" class="form-label">Statut</label>
                        <select class="form-select @error('actif') is-invalid @enderror" 
                                id="actif" 
                                name="actif">
                            <option value="1" {{ old('actif', $locataire->actif) == 1 ? 'selected' : '' }}>Actif</option>
                            <option value="0" {{ old('actif', $locataire->actif) == 0 ? 'selected' : '' }}>Inactif</option>
                        </select>
                        @error('actif')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="appartement_id" class="form-label">Appartement</label>
                        <select class="form-select @error('appartement_id') is-invalid @enderror" 
                                id="appartement_id" 
                                name="appartement_id">
                            <option value="">Aucun appartement</option>
                            @foreach($appartements as $appartement)
                                <option value="{{ $appartement->id }}" 
                                        data-loyer="{{ $appartement->loyer_mensuel }}"
                                        {{ old('appartement_id', $locataire->appartement_id) == $appartement->id ? 'selected' : '' }}>
                                    {{ $appartement->immeuble->nom }} - Apt {{ $appartement->numero }}
                                    ({{ number_format($appartement->loyer_mensuel, 0, ',', ' ') }} $)
                                </option>
                            @endforeach
                        </select>
                        @error('appartement_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Evaluation financière -->
            <div class="card mt-3" id="evaluation_card" style="display: none;">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-calculator"></i> Évaluation
                    </h6>
                </div>
                <div class="card-body">
                    <div id="evaluation_content">
                        <!-- Contenu généré par JavaScript -->
                    </div>
                </div>
            </div>

            <!-- Résumé actuel -->
            @if($locataire->appartement || $locataire->loyers->count() > 0)
            <div class="card mt-3 bg-light">
                <div class="card-header">
                    <h6 class="mb-0">Résumé actuel</h6>
                </div>
                <div class="card-body">
                    @if($locataire->appartement)
                    <div class="mb-3">
                        <small class="text-muted">Appartement actuel :</small>
                        <div class="fw-bold">{{ $locataire->appartement->immeuble->nom }} - Apt {{ $locataire->appartement->numero }}</div>
                        <div class="text-success">{{ number_format($locataire->appartement->loyer_mensuel, 0, ',', ' ') }} $/mois</div>
                    </div>
                    @endif
                    
                    @if($locataire->loyers->count() > 0)
                    @php
                        $loyersPayes = $locataire->loyers->where('statut', 'paye')->count();
                        $loyersImpayes = $locataire->loyers->where('statut', 'impaye')->count();
                    @endphp
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="text-success fw-bold">{{ $loyersPayes }}</div>
                            <small class="text-muted">Payés</small>
                        </div>
                        <div class="col-6">
                            <div class="text-danger fw-bold">{{ $loyersImpayes }}</div>
                            <small class="text-muted">Impayés</small>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Historique des modifications -->
            <div class="card mt-3 bg-light">
                <div class="card-body">
                    <h6 class="card-title">
                        <i class="fas fa-history"></i> Historique
                    </h6>
                    <ul class="small text-muted mb-0">
                        <li><strong>Créé le :</strong> {{ $locataire->created_at->format('d/m/Y H:i') }}</li>
                        <li><strong>Modifié le :</strong> {{ $locataire->updated_at->format('d/m/Y H:i') }}</li>
                        @if($locataire->loyers)
                        <li><strong>Loyers :</strong> {{ $locataire->loyers->count() }} enregistré(s)</li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>
</form>

<!-- Modal de suppression -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmer la suppression</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Êtes-vous sûr de vouloir supprimer ce locataire ?</p>
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i>
                    <strong>Attention :</strong> Cette action est irréversible et supprimera également tous les loyers associés.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <form action="{{ route('locataires.destroy', $locataire) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Supprimer définitivement</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const revenuInput = document.getElementById('revenu_mensuel');
    const appartementSelect = document.getElementById('appartement_id');
    const evaluationCard = document.getElementById('evaluation_card');
    const evaluationContent = document.getElementById('evaluation_content');
    const capaciteText = document.getElementById('capacite_paiement_text');

    function updateEvaluation() {
        const revenu = parseFloat(revenuInput.value) || 0;
        const selectedOption = appartementSelect.options[appartementSelect.selectedIndex];
        const loyer = parseFloat(selectedOption.dataset.loyer) || 0;

        // Mise à jour de la capacité de paiement
        if (revenu > 0) {
            const capacite = revenu * 0.33;
            capaciteText.innerHTML = `<i class="fas fa-info-circle"></i> Capacité de paiement recommandée : <strong>${formatNumber(capacite)} $</strong>`;
        } else {
            capaciteText.innerHTML = '';
        }

        // Mise à jour de l'évaluation
        if (revenu > 0 && loyer > 0) {
            const ratio = (loyer / revenu) * 100;
            const resteAVivre = revenu - loyer;
            const capacite = revenu * 0.33;
            
            let statusClass = '';
            let statusText = '';
            let statusIcon = '';
            
            if (ratio <= 25) {
                statusClass = 'text-success';
                statusText = 'Excellent';
                statusIcon = 'fas fa-check-circle';
            } else if (ratio <= 33) {
                statusClass = 'text-warning';
                statusText = 'Acceptable';
                statusIcon = 'fas fa-exclamation-circle';
            } else {
                statusClass = 'text-danger';
                statusText = 'Risqué';
                statusIcon = 'fas fa-times-circle';
            }

            evaluationContent.innerHTML = `
                <div class="row text-center mb-3">
                    <div class="col-12">
                        <div class="${statusClass}">
                            <i class="${statusIcon} fa-2x"></i>
                            <div class="fw-bold">${statusText}</div>
                        </div>
                    </div>
                </div>
                <table class="table table-sm">
                    <tr>
                        <td>Ratio d'endettement :</td>
                        <td class="fw-bold ${statusClass}">${ratio.toFixed(1)}%</td>
                    </tr>
                    <tr>
                        <td>Reste à vivre :</td>
                        <td class="fw-bold">${formatNumber(resteAVivre)} $</td>
                    </tr>
                    <tr>
                        <td>Capacité théorique :</td>
                        <td class="fw-bold">${formatNumber(capacite)} $</td>
                    </tr>
                </table>
                <div class="alert alert-info alert-sm p-2 mb-0">
                    <small>
                        ${ratio <= 33 ? 
                            'Le locataire répond aux critères de solvabilité.' : 
                            'Attention : le ratio d\'endettement dépasse les recommandations (33% max).'
                        }
                    </small>
                </div>
            `;
            
            evaluationCard.style.display = 'block';
        } else {
            evaluationCard.style.display = 'none';
        }
    }

    function formatNumber(num) {
        return new Intl.NumberFormat('fr-FR').format(Math.round(num));
    }

    // Event listeners
    revenuInput.addEventListener('input', updateEvaluation);
    appartementSelect.addEventListener('change', updateEvaluation);

    // Évaluation initiale
    updateEvaluation();
});
</script>
@endsection