@extends('layouts.app')

@section('title', 'Créer un Immeuble')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Créer un Immeuble</h1>
    <a href="{{ route('immeubles.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Retour
    </a>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('immeubles.store') }}">
                    @csrf

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nom" class="form-label">Nom de l'immeuble <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('nom') is-invalid @enderror" 
                                   id="nom" 
                                   name="nom" 
                                   value="{{ old('nom') }}" 
                                   required>
                            @error('nom')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="adresse" class="form-label">Adresse <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('adresse') is-invalid @enderror" 
                                   id="adresse" 
                                   name="adresse" 
                                   value="{{ old('adresse') }}" 
                                   required>
                            @error('adresse')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="commune" class="form-label">Commune</label>
                            <input type="text" 
                                   class="form-control @error('commune') is-invalid @enderror" 
                                   id="commune" 
                                   name="commune" 
                                   value="{{ old('commune') }}">
                            @error('commune')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="quartier" class="form-label">Quartier</label>
                            <input type="text" 
                                   class="form-control @error('quartier') is-invalid @enderror" 
                                   id="quartier" 
                                   name="quartier" 
                                   value="{{ old('quartier') }}">
                            @error('quartier')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="nombre_etages" class="form-label">Nombre d'étages</label>
                            <input type="number" 
                                   class="form-control @error('nombre_etages') is-invalid @enderror" 
                                   id="nombre_etages" 
                                   name="nombre_etages" 
                                   value="{{ old('nombre_etages', 1) }}" 
                                   min="1">
                            @error('nombre_etages')
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
                            <label for="valeur_estimee" class="form-label">Valeur estimée (CDF)</label>
                            <input type="number" 
                                   class="form-control @error('valeur_estimee') is-invalid @enderror" 
                                   id="valeur_estimee" 
                                   name="valeur_estimee" 
                                   value="{{ old('valeur_estimee') }}" 
                                   min="0">
                            @error('valeur_estimee')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="date_construction" class="form-label">Date de construction</label>
                            <input type="date" 
                                   class="form-control @error('date_construction') is-invalid @enderror" 
                                   id="date_construction" 
                                   name="date_construction" 
                                   value="{{ old('date_construction') }}">
                            @error('date_construction')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" 
                                   type="checkbox" 
                                   id="actif" 
                                   name="actif" 
                                   value="1" 
                                   {{ old('actif', true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="actif">
                                Immeuble actif
                            </label>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('immeubles.index') }}" class="btn btn-secondary">Annuler</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Créer l'immeuble
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
                    <li>Le nom et l'adresse sont requis pour créer un immeuble</li>
                    <li>Vous pourrez ajouter des appartements après la création</li>
                    <li>La valeur estimée est utilisée pour les rapports financiers</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection