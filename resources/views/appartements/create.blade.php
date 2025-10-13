@extends('layouts.app')

@section('title', 'Créer un Appartement')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Créer un Appartement</h1>
    <a href="{{ route('appartements.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Retour
    </a>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('appartements.store') }}">
                    @csrf

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="immeuble_id" class="form-label">Immeuble <span class="text-danger">*</span></label>
                            <select class="form-select @error('immeuble_id') is-invalid @enderror" 
                                    id="immeuble_id" 
                                    name="immeuble_id" 
                                    required>
                                <option value="">Sélectionner un immeuble</option>
                                @foreach($immeubles as $immeuble)
                                    <option value="{{ $immeuble->id }}" {{ old('immeuble_id') == $immeuble->id ? 'selected' : '' }}>
                                        {{ $immeuble->nom }} - {{ $immeuble->adresse }}
                                    </option>
                                @endforeach
                            </select>
                            @error('immeuble_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="numero" class="form-label">Numéro <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('numero') is-invalid @enderror" 
                                   id="numero" 
                                   name="numero" 
                                   value="{{ old('numero') }}" 
                                   required>
                            @error('numero')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="type" class="form-label">Type <span class="text-danger">*</span></label>
                            <select class="form-select @error('type') is-invalid @enderror" 
                                    id="type" 
                                    name="type" 
                                    required>
                                <option value="">Sélectionner un type</option>
                                <option value="studio" {{ old('type') == 'studio' ? 'selected' : '' }}>Studio</option>
                                <option value="1_chambre" {{ old('type') == '1_chambre' ? 'selected' : '' }}>1 Chambre</option>
                                <option value="2_chambres" {{ old('type') == '2_chambres' ? 'selected' : '' }}>2 Chambres</option>
                                <option value="3_chambres" {{ old('type') == '3_chambres' ? 'selected' : '' }}>3 Chambres</option>
                                <option value="4_chambres_plus" {{ old('type') == '4_chambres_plus' ? 'selected' : '' }}>4+ Chambres</option>
                                <option value="duplex" {{ old('type') == 'duplex' ? 'selected' : '' }}>Duplex</option>
                            </select>
                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="superficie" class="form-label">Superficie (m²)</label>
                            <input type="number" 
                                   class="form-control @error('superficie') is-invalid @enderror" 
                                   id="superficie" 
                                   name="superficie" 
                                   value="{{ old('superficie') }}" 
                                   min="0" 
                                   step="0.1">
                            @error('superficie')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="etage" class="form-label">Étage</label>
                            <input type="number" 
                                   class="form-control @error('etage') is-invalid @enderror" 
                                   id="etage" 
                                   name="etage" 
                                   value="{{ old('etage', 0) }}" 
                                   min="0">
                            @error('etage')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="loyer_mensuel" class="form-label">Loyer mensuel ($) <span class="text-danger">*</span></label>
                            <input type="number" 
                                   class="form-control @error('loyer_mensuel') is-invalid @enderror" 
                                   id="loyer_mensuel" 
                                   name="loyer_mensuel" 
                                   value="{{ old('loyer_mensuel') }}" 
                                   min="0" 
                                   required>
                            @error('loyer_mensuel')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="garantie_locative" class="form-label">Garantie locative ($)</label>
                            <input type="number" 
                                   class="form-control @error('garantie_locative') is-invalid @enderror" 
                                   id="garantie_locative" 
                                   name="garantie_locative" 
                                   value="{{ old('garantie_locative') }}" 
                                   min="0">
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
                                  rows="3">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="form-check">
                                <input class="form-check-input" 
                                       type="checkbox" 
                                       id="meuble" 
                                       name="meuble" 
                                       value="1" 
                                       {{ old('meuble') ? 'checked' : '' }}>
                                <label class="form-check-label" for="meuble">
                                    Appartement meublé
                                </label>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="form-check">
                                <input class="form-check-input" 
                                       type="checkbox" 
                                       id="disponible" 
                                       name="disponible" 
                                       value="1" 
                                       {{ old('disponible', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="disponible">
                                    Disponible à la location
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('appartements.index') }}" class="btn btn-secondary">Annuler</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Créer l'appartement
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
                    <li>La garantie locative est souvent équivalente à 2-3 mois de loyer</li>
                    <li>Un appartement indisponible ne peut pas être loué</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection