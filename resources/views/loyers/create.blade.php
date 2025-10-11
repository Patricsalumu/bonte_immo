@extends('layouts.app')

@section('title', 'Créer un Contrat de Loyer')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Créer un Contrat de Loyer</h1>
    <a href="{{ route('loyers.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Retour
    </a>
</div>

@if(count($appartements) == 0)
    <div class="alert alert-warning">
        <h5><i class="fas fa-exclamation-triangle"></i> Aucun appartement disponible</h5>
        <p>Tous les appartements ont déjà un contrat actif. Vous devez d'abord libérer un appartement pour créer un nouveau contrat.</p>
        <a href="{{ route('appartements.index') }}" class="btn btn-primary">Voir les appartements</a>
    </div>
@elseif(count($locataires) == 0)
    <div class="alert alert-warning">
        <h5><i class="fas fa-exclamation-triangle"></i> Aucun locataire disponible</h5>
        <p>Tous les locataires ont déjà un contrat actif. Vous devez d'abord ajouter un nouveau locataire ou libérer un contrat existant.</p>
        <a href="{{ route('locataires.create') }}" class="btn btn-primary">Ajouter un locataire</a>
    </div>
@else
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('loyers.store') }}">
                    @csrf

                    <h5 class="mb-3">Informations du contrat</h5>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="locataire_id" class="form-label">Locataire disponible <span class="text-danger">*</span></label>
                            <select class="form-select @error('locataire_id') is-invalid @enderror" 
                                    id="locataire_id" 
                                    name="locataire_id" 
                                    required>
                                <option value="">Sélectionner un locataire</option>
                                @foreach($locataires as $locataire)
                                    <option value="{{ $locataire->id }}" {{ old('locataire_id') == $locataire->id ? 'selected' : '' }}>
                                        {{ $locataire->nom }} {{ $locataire->prenom }} - {{ $locataire->telephone }}
                                    </option>
                                @endforeach
                            </select>
                            @error('locataire_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Seuls les locataires sans contrat actif sont affichés</small>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="appartement_id" class="form-label">Appartement disponible <span class="text-danger">*</span></label>
                            <select class="form-select @error('appartement_id') is-invalid @enderror" 
                                    id="appartement_id" 
                                    name="appartement_id" 
                                    required>
                                <option value="">Sélectionner un appartement</option>
                                @foreach($appartements as $appartement)
                                    <option value="{{ $appartement->id }}" 
                                            data-loyer="{{ $appartement->loyer_mensuel }}"
                                            data-garantie="{{ $appartement->garantie_locative }}"
                                            {{ old('appartement_id') == $appartement->id ? 'selected' : '' }}>
                                        {{ $appartement->immeuble->nom }} - Apt {{ $appartement->numero }} ({{ $appartement->type }})
                                        - {{ number_format($appartement->loyer_mensuel, 0, ',', ' ') }} CDF/mois
                                    </option>
                                @endforeach
                            </select>
                            @error('appartement_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Seuls les appartements sans contrat actif sont affichés</small>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="date_debut" class="form-label">Date de début <span class="text-danger">*</span></label>
                            <input type="date" 
                                   class="form-control @error('date_debut') is-invalid @enderror" 
                                   id="date_debut" 
                                   name="date_debut" 
                                   value="{{ old('date_debut', date('Y-m-d')) }}" 
                                   required>
                            @error('date_debut')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="date_fin" class="form-label">Date de fin (optionnel)</label>
                            <input type="date" 
                                   class="form-control @error('date_fin') is-invalid @enderror" 
                                   id="date_fin" 
                                   name="date_fin" 
                                   value="{{ old('date_fin') }}">
                            @error('date_fin')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Laisser vide pour un contrat à durée indéterminée</small>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="montant" class="form-label">Montant du loyer (CDF) <span class="text-danger">*</span></label>
                            <input type="number" 
                                   class="form-control @error('montant') is-invalid @enderror" 
                                   id="montant" 
                                   name="montant" 
                                   value="{{ old('montant') }}" 
                                   min="0" 
                                   step="0.01"
                                   required>
                            @error('montant')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="garantie_locative" class="form-label">Garantie locative (CDF)</label>
                            <input type="number" 
                                   class="form-control @error('garantie_locative') is-invalid @enderror" 
                                   id="garantie_locative" 
                                   name="garantie_locative" 
                                   value="{{ old('garantie_locative') }}" 
                                   min="0" 
                                   step="0.01">
                            @error('garantie_locative')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="notes" class="form-label">Notes / Conditions particulières</label>
                        <textarea class="form-control @error('notes') is-invalid @enderror" 
                                  id="notes" 
                                  name="notes" 
                                  rows="3" 
                                  placeholder="Conditions spéciales, remarques...">{{ old('notes') }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('loyers.index') }}" class="btn btn-secondary">Annuler</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Créer le contrat
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Informations</h5>
                <div class="alert alert-info">
                    <h6><i class="fas fa-info-circle"></i> Contrat de loyer</h6>
                    <ul class="mb-0">
                        <li>Seuls les appartements et locataires <strong>disponibles</strong> sont affichés</li>
                        <li>La date de fin est optionnelle (contrat à durée indéterminée)</li>
                        <li>La garantie locative peut être saisie séparément</li>
                        <li>Le contrat sera automatiquement marqué comme <strong>actif</strong></li>
                    </ul>
                </div>
                
                <div id="apartment-details" class="mt-3" style="display: none;">
                    <h6>Détails de l'appartement</h6>
                    <div id="apartment-info"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<script>
document.addEventListener('DOMContentLoaded', function() {
    const appartementSelect = document.getElementById('appartement_id');
    const montantInput = document.getElementById('montant');
    const garantieInput = document.getElementById('garantie_locative');
    const apartmentDetails = document.getElementById('apartment-details');
    const apartmentInfo = document.getElementById('apartment-info');

    if (appartementSelect) {
        appartementSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            if (selectedOption.value) {
                const loyer = selectedOption.dataset.loyer;
                const garantie = selectedOption.dataset.garantie;
                
                if (loyer) {
                    montantInput.value = loyer;
                }
                if (garantie) {
                    garantieInput.value = garantie;
                }
                
                apartmentInfo.innerHTML = `
                    <p><strong>Loyer suggéré:</strong> ${Number(loyer).toLocaleString()} CDF</p>
                    <p><strong>Garantie suggérée:</strong> ${Number(garantie).toLocaleString()} CDF</p>
                `;
                apartmentDetails.style.display = 'block';
            } else {
                apartmentDetails.style.display = 'none';
                montantInput.value = '';
                garantieInput.value = '';
            }
        });
    }
});
</script>
@endsection