@extends('layouts.app')

@section('title', 'Modifier l\'Immeuble')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Modifier l'Immeuble</h1>
    <div>
        <a href="{{ route('immeubles.show', $immeuble) }}" class="btn btn-info">
            <i class="fas fa-eye"></i> Voir
        </a>
        <a href="{{ route('immeubles.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Retour
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('immeubles.update', $immeuble) }}">
                    @csrf
                    @method('PUT')

                    <h5 class="mb-3">Informations de base</h5>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nom" class="form-label">Nom de l'immeuble <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('nom') is-invalid @enderror" 
                                   id="nom" 
                                   name="nom" 
                                   value="{{ old('nom', $immeuble->nom) }}" 
                                   required>
                            @error('nom')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="nombre_etages" class="form-label">Nombre d'étages <span class="text-danger">*</span></label>
                            <input type="number" 
                                   class="form-control @error('nombre_etages') is-invalid @enderror" 
                                   id="nombre_etages" 
                                   name="nombre_etages" 
                                   value="{{ old('nombre_etages', $immeuble->nombre_etages) }}" 
                                   min="1" 
                                   required>
                            @error('nombre_etages')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="adresse" class="form-label">Adresse complète <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('adresse') is-invalid @enderror" 
                                  id="adresse" 
                                  name="adresse" 
                                  rows="2" 
                                  required>{{ old('adresse', $immeuble->adresse) }}</textarea>
                        @error('adresse')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="quartier" class="form-label">Quartier <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('quartier') is-invalid @enderror" 
                                   id="quartier" 
                                   name="quartier" 
                                   value="{{ old('quartier', $immeuble->quartier) }}" 
                                   required>
                            @error('quartier')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="commune" class="form-label">Commune <span class="text-danger">*</span></label>
                            <select class="form-select @error('commune') is-invalid @enderror" 
                                    id="commune" 
                                    name="commune" 
                                    required>
                                <option value="">Sélectionner une commune</option>
                                <option value="Bandalungwa" {{ old('commune', $immeuble->commune) == 'Bandalungwa' ? 'selected' : '' }}>Bandalungwa</option>
                                <option value="Barumbu" {{ old('commune', $immeuble->commune) == 'Barumbu' ? 'selected' : '' }}>Barumbu</option>
                                <option value="Bumbu" {{ old('commune', $immeuble->commune) == 'Bumbu' ? 'selected' : '' }}>Bumbu</option>
                                <option value="Gombe" {{ old('commune', $immeuble->commune) == 'Gombe' ? 'selected' : '' }}>Gombe</option>
                                <option value="Kalamu" {{ old('commune', $immeuble->commune) == 'Kalamu' ? 'selected' : '' }}>Kalamu</option>
                                <option value="Kasa-Vubu" {{ old('commune', $immeuble->commune) == 'Kasa-Vubu' ? 'selected' : '' }}>Kasa-Vubu</option>
                                <option value="Kimbanseke" {{ old('commune', $immeuble->commune) == 'Kimbanseke' ? 'selected' : '' }}>Kimbanseke</option>
                                <option value="Kinshasa" {{ old('commune', $immeuble->commune) == 'Kinshasa' ? 'selected' : '' }}>Kinshasa</option>
                                <option value="Kintambo" {{ old('commune', $immeuble->commune) == 'Kintambo' ? 'selected' : '' }}>Kintambo</option>
                                <option value="Lemba" {{ old('commune', $immeuble->commune) == 'Lemba' ? 'selected' : '' }}>Lemba</option>
                                <option value="Limete" {{ old('commune', $immeuble->commune) == 'Limete' ? 'selected' : '' }}>Limete</option>
                                <option value="Lingwala" {{ old('commune', $immeuble->commune) == 'Lingwala' ? 'selected' : '' }}>Lingwala</option>
                                <option value="Makala" {{ old('commune', $immeuble->commune) == 'Makala' ? 'selected' : '' }}>Makala</option>
                                <option value="Maluku" {{ old('commune', $immeuble->commune) == 'Maluku' ? 'selected' : '' }}>Maluku</option>
                                <option value="Masina" {{ old('commune', $immeuble->commune) == 'Masina' ? 'selected' : '' }}>Masina</option>
                                <option value="Matete" {{ old('commune', $immeuble->commune) == 'Matete' ? 'selected' : '' }}>Matete</option>
                                <option value="Mont-Ngafula" {{ old('commune', $immeuble->commune) == 'Mont-Ngafula' ? 'selected' : '' }}>Mont-Ngafula</option>
                                <option value="Ndjili" {{ old('commune', $immeuble->commune) == 'Ndjili' ? 'selected' : '' }}>Ndjili</option>
                                <option value="Ngaba" {{ old('commune', $immeuble->commune) == 'Ngaba' ? 'selected' : '' }}>Ngaba</option>
                                <option value="Ngaliema" {{ old('commune', $immeuble->commune) == 'Ngaliema' ? 'selected' : '' }}>Ngaliema</option>
                                <option value="Ngiri-Ngiri" {{ old('commune', $immeuble->commune) == 'Ngiri-Ngiri' ? 'selected' : '' }}>Ngiri-Ngiri</option>
                                <option value="Nsele" {{ old('commune', $immeuble->commune) == 'Nsele' ? 'selected' : '' }}>Nsele</option>
                                <option value="Selembao" {{ old('commune', $immeuble->commune) == 'Selembao' ? 'selected' : '' }}>Selembao</option>
                            </select>
                            @error('commune')
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
                                  placeholder="Description de l'immeuble, équipements, etc.">{{ old('description', $immeuble->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" 
                                   type="checkbox" 
                                   id="actif" 
                                   name="actif" 
                                   value="1" 
                                   {{ old('actif', $immeuble->actif) ? 'checked' : '' }}>
                            <label class="form-check-label" for="actif">
                                Immeuble actif
                            </label>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('immeubles.show', $immeuble) }}" class="btn btn-secondary">Annuler</a>
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
                    <li>Le nom de l'immeuble doit être unique</li>
                    <li>Vous pouvez désactiver temporairement un immeuble</li>
                    <li>La modification affectera tous les appartements liés</li>
                </ul>
            </div>
        </div>

        <div class="card bg-warning text-dark mt-3">
            <div class="card-body">
                <h6 class="card-title">
                    <i class="fas fa-exclamation-triangle"></i> Attention
                </h6>
                <p class="small mb-0">
                    Désactiver un immeuble n'affectera pas les contrats en cours, mais empêchera la création de nouveaux contrats.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection