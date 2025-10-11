@extends('layouts.app')

@section('title', 'Modifier l\'Appartement')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Modifier l'Appartement</h1>
    <div>
        <a href="{{ route('appartements.show', $appartement) }}" class="btn btn-info">
            <i class="fas fa-eye"></i> Voir
        </a>
        <a href="{{ route('appartements.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Retour
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('appartements.update', $appartement) }}">
                    @csrf
                    @method('PUT')

                    <h5 class="mb-3">Informations de base</h5>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="immeuble_id" class="form-label">Immeuble <span class="text-danger">*</span></label>
                            <select class="form-select @error('immeuble_id') is-invalid @enderror" 
                                    id="immeuble_id" 
                                    name="immeuble_id" 
                                    required>
                                <option value="">Sélectionner un immeuble</option>
                                @foreach($immeubles as $immeuble)
                                    <option value="{{ $immeuble->id }}" 
                                            {{ old('immeuble_id', $appartement->immeuble_id) == $immeuble->id ? 'selected' : '' }}>
                                        {{ $immeuble->nom }} ({{ $immeuble->commune }})
                                    </option>
                                @endforeach
                            </select>
                            @error('immeuble_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="numero" class="form-label">Numéro de l'appartement <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('numero') is-invalid @enderror" 
                                   id="numero" 
                                   name="numero" 
                                   value="{{ old('numero', $appartement->numero) }}" 
                                   required>
                            @error('numero')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="type" class="form-label">Type d'appartement <span class="text-danger">*</span></label>
                            <select class="form-select @error('type') is-invalid @enderror" 
                                    id="type" 
                                    name="type" 
                                    required>
                                <option value="">Sélectionner un type</option>
                                <option value="studio" {{ old('type', $appartement->type) == 'studio' ? 'selected' : '' }}>Studio</option>
                                <option value="1_chambre" {{ old('type', $appartement->type) == '1_chambre' ? 'selected' : '' }}>1 chambre</option>
                                <option value="2_chambres" {{ old('type', $appartement->type) == '2_chambres' ? 'selected' : '' }}>2 chambres</option>
                                <option value="3_chambres" {{ old('type', $appartement->type) == '3_chambres' ? 'selected' : '' }}>3 chambres</option>
                                <option value="4_chambres_plus" {{ old('type', $appartement->type) == '4_chambres_plus' ? 'selected' : '' }}>4 chambres et plus</option>
                                <option value="duplex" {{ old('type', $appartement->type) == 'duplex' ? 'selected' : '' }}>Duplex</option>
                            </select>
                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="superficie" class="form-label">Superficie (m²) <span class="text-danger">*</span></label>
                            <input type="number" 
                                   class="form-control @error('superficie') is-invalid @enderror" 
                                   id="superficie" 
                                   name="superficie" 
                                   value="{{ old('superficie', $appartement->superficie) }}" 
                                   min="10" 
                                   step="0.1" 
                                   required>
                            @error('superficie')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <h5 class="mb-3 mt-4">Informations financières</h5>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="loyer_mensuel" class="form-label">Loyer mensuel (CDF) <span class="text-danger">*</span></label>
                            <input type="number" 
                                   class="form-control @error('loyer_mensuel') is-invalid @enderror" 
                                   id="loyer_mensuel" 
                                   name="loyer_mensuel" 
                                   value="{{ old('loyer_mensuel', $appartement->loyer_mensuel) }}" 
                                   min="0" 
                                   required>
                            @error('loyer_mensuel')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="garantie_locative" class="form-label">Garantie locative (CDF) <span class="text-danger">*</span></label>
                            <input type="number" 
                                   class="form-control @error('garantie_locative') is-invalid @enderror" 
                                   id="garantie_locative" 
                                   name="garantie_locative" 
                                   value="{{ old('garantie_locative', $appartement->garantie_locative) }}" 
                                   min="0" 
                                   required>
                            @error('garantie_locative')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" 
                                  name="description" 
                                  rows="3" 
                                  placeholder="Description de l'appartement, équipements, état, etc.">{{ old('description', $appartement->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="form-check">
                                <input class="form-check-input" 
                                       type="checkbox" 
                                       id="actif" 
                                       name="actif" 
                                       value="1" 
                                       {{ old('actif', $appartement->actif) ? 'checked' : '' }}>
                                <label class="form-check-label" for="actif">
                                    Appartement actif
                                </label>
                            </div>
                        </div>

                        @if($appartement->locataire)
                        <div class="col-md-6 mb-3">
                            <label for="locataire_id" class="form-label">Locataire actuel</label>
                            <select class="form-select @error('locataire_id') is-invalid @enderror" 
                                    id="locataire_id" 
                                    name="locataire_id">
                                <option value="">Aucun locataire</option>
                                @foreach($locataires as $locataire)
                                    <option value="{{ $locataire->id }}" 
                                            {{ old('locataire_id', $appartement->locataire_id) == $locataire->id ? 'selected' : '' }}>
                                        {{ $locataire->nom }} {{ $locataire->prenom }}
                                    </option>
                                @endforeach
                            </select>
                            @error('locataire_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        @endif
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('appartements.show', $appartement) }}" class="btn btn-secondary">Annuler</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Enregistrer les modifications
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card bg-light">
            <div class="card-body">
                <h6 class="card-title">
                    <i class="fas fa-info-circle"></i> Informations
                </h6>
                <ul class="small text-muted mb-0">
                    <li>Les champs marqués d'un <span class="text-danger">*</span> sont obligatoires</li>
                    <li>Le numéro doit être unique dans l'immeuble</li>
                    <li>La garantie est généralement de 2-3 mois de loyer</li>
                    <li>Vous pouvez changer le locataire si nécessaire</li>
                </ul>
            </div>
        </div>

        @if($appartement->locataire)
        <div class="card bg-warning text-dark mt-3">
            <div class="card-body">
                <h6 class="card-title">
                    <i class="fas fa-exclamation-triangle"></i> Attention
                </h6>
                <p class="small mb-0">
                    Cet appartement est actuellement occupé par {{ $appartement->locataire->nom }} {{ $appartement->locataire->prenom }}. 
                    Modifier le locataire pourrait affecter les contrats en cours.
                </p>
            </div>
        </div>
        @endif

        <div class="card bg-info text-white mt-3">
            <div class="card-body">
                <h6 class="card-title">
                    <i class="fas fa-calculator"></i> Calculateur
                </h6>
                <div class="mb-2">
                    <label class="form-label small">Loyer/m² :</label>
                    <div id="prix_m2" class="fw-bold">-</div>
                </div>
                <div class="mb-2">
                    <label class="form-label small">Garantie recommandée (2 mois) :</label>
                    <div id="garantie_recommandee" class="fw-bold">-</div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function calculateValues() {
    const loyer = parseFloat(document.getElementById('loyer_mensuel').value) || 0;
    const superficie = parseFloat(document.getElementById('superficie').value) || 0;
    
    // Prix au m²
    if (superficie > 0) {
        const prixM2 = loyer / superficie;
        document.getElementById('prix_m2').textContent = prixM2.toFixed(0) + ' CDF/m²';
    } else {
        document.getElementById('prix_m2').textContent = '-';
    }
    
    // Garantie recommandée
    const garantieRecommandee = loyer * 2;
    document.getElementById('garantie_recommandee').textContent = garantieRecommandee.toLocaleString() + ' CDF';
    
    // Suggérer la garantie
    const garantieInput = document.getElementById('garantie_locative');
    if (garantieInput.value == '' || garantieInput.value == '0') {
        garantieInput.value = garantieRecommandee;
    }
}

document.getElementById('loyer_mensuel').addEventListener('input', calculateValues);
document.getElementById('superficie').addEventListener('input', calculateValues);

// Calculer au chargement
document.addEventListener('DOMContentLoaded', calculateValues);
</script>
@endsection