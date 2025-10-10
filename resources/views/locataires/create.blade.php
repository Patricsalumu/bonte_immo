@extends('layouts.app')

@section('title', 'Créer un Locataire')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Créer un Locataire</h1>
    <a href="{{ route('locataires.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Retour
    </a>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('locataires.store') }}">
                    @csrf

                    <h5 class="mb-3">Informations personnelles</h5>
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="nom" class="form-label">Nom <span class="text-danger">*</span></label>
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

                        <div class="col-md-4 mb-3">
                            <label for="prenom" class="form-label">Prénom <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('prenom') is-invalid @enderror" 
                                   id="prenom" 
                                   name="prenom" 
                                   value="{{ old('prenom') }}" 
                                   required>
                            @error('prenom')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="date_naissance" class="form-label">Date de naissance</label>
                            <input type="date" 
                                   class="form-control @error('date_naissance') is-invalid @enderror" 
                                   id="date_naissance" 
                                   name="date_naissance" 
                                   value="{{ old('date_naissance') }}">
                            @error('date_naissance')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="telephone" class="form-label">Téléphone <span class="text-danger">*</span></label>
                            <input type="tel" 
                                   class="form-control @error('telephone') is-invalid @enderror" 
                                   id="telephone" 
                                   name="telephone" 
                                   value="{{ old('telephone') }}" 
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
                                   value="{{ old('email') }}">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="adresse" class="form-label">Adresse actuelle</label>
                        <textarea class="form-control @error('adresse') is-invalid @enderror" 
                                  id="adresse" 
                                  name="adresse" 
                                  rows="2">{{ old('adresse') }}</textarea>
                        @error('adresse')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <hr class="my-4">

                    <h5 class="mb-3">Informations professionnelles</h5>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="profession" class="form-label">Profession</label>
                            <input type="text" 
                                   class="form-control @error('profession') is-invalid @enderror" 
                                   id="profession" 
                                   name="profession" 
                                   value="{{ old('profession') }}">
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
                                   value="{{ old('employeur') }}">
                            @error('employeur')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="revenu_mensuel" class="form-label">Revenu mensuel (CDF)</label>
                            <input type="number" 
                                   class="form-control @error('revenu_mensuel') is-invalid @enderror" 
                                   id="revenu_mensuel" 
                                   name="revenu_mensuel" 
                                   value="{{ old('revenu_mensuel') }}" 
                                   min="0">
                            @error('revenu_mensuel')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="numero_carte_identite" class="form-label">Numéro carte d'identité</label>
                            <input type="text" 
                                   class="form-control @error('numero_carte_identite') is-invalid @enderror" 
                                   id="numero_carte_identite" 
                                   name="numero_carte_identite" 
                                   value="{{ old('numero_carte_identite') }}">
                            @error('numero_carte_identite')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <hr class="my-4">

                    <h5 class="mb-3">Contact d'urgence</h5>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="contact_urgence_nom" class="form-label">Nom du contact</label>
                            <input type="text" 
                                   class="form-control @error('contact_urgence_nom') is-invalid @enderror" 
                                   id="contact_urgence_nom" 
                                   name="contact_urgence_nom" 
                                   value="{{ old('contact_urgence_nom') }}">
                            @error('contact_urgence_nom')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="contact_urgence_telephone" class="form-label">Téléphone du contact</label>
                            <input type="tel" 
                                   class="form-control @error('contact_urgence_telephone') is-invalid @enderror" 
                                   id="contact_urgence_telephone" 
                                   name="contact_urgence_telephone" 
                                   value="{{ old('contact_urgence_telephone') }}">
                            @error('contact_urgence_telephone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="notes" class="form-label">Notes</label>
                        <textarea class="form-control @error('notes') is-invalid @enderror" 
                                  id="notes" 
                                  name="notes" 
                                  rows="3">{{ old('notes') }}</textarea>
                        @error('notes')
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
                                   {{ old('actif', true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="actif">
                                Locataire actif
                            </label>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('locataires.index') }}" class="btn btn-secondary">Annuler</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Créer le locataire
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
                    <li>Le téléphone est essentiel pour les communications</li>
                    <li>Les informations professionnelles aident à évaluer la solvabilité</li>
                    <li>Le contact d'urgence est important en cas de problème</li>
                    <li>Vous pourrez attribuer un appartement après la création</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection