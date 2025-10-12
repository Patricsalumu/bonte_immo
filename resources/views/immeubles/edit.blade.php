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