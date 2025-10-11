@extends('layouts.app')

@section('title', 'Modifier le Contrat de Loyer #' . $loyer->id)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Modifier le Contrat de Loyer #{{ $loyer->id }}</h1>
    <a href="{{ route('loyers.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Retour
    </a>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('loyers.update', $loyer) }}">
                    @csrf
                    @method('PUT')

                    <h5 class="mb-3">Informations du contrat</h5>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="locataire" class="form-label">Locataire</label>
                            <input type="text" 
                                   class="form-control" 
                                   id="locataire" 
                                   value="{{ $loyer->locataire->nom }} {{ $loyer->locataire->prenom }}" 
                                   readonly>
                            <small class="form-text text-muted">Le locataire ne peut pas être modifié après création</small>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="appartement" class="form-label">Appartement</label>
                            <input type="text" 
                                   class="form-control" 
                                   id="appartement" 
                                   value="{{ $loyer->appartement->immeuble->nom }} - Apt {{ $loyer->appartement->numero }}" 
                                   readonly>
                            <small class="form-text text-muted">L'appartement ne peut pas être modifié après création</small>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="date_debut" class="form-label">Date de début <span class="text-danger">*</span></label>
                            <input type="date" 
                                   class="form-control @error('date_debut') is-invalid @enderror" 
                                   id="date_debut" 
                                   name="date_debut" 
                                   value="{{ old('date_debut', $loyer->date_debut->format('Y-m-d')) }}" 
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
                                   value="{{ old('date_fin', $loyer->date_fin?->format('Y-m-d')) }}">
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
                                   value="{{ old('montant', $loyer->montant) }}" 
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
                                   value="{{ old('garantie_locative', $loyer->garantie_locative) }}" 
                                   min="0" 
                                   step="0.01">
                            @error('garantie_locative')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="statut" class="form-label">Statut du contrat <span class="text-danger">*</span></label>
                        <select class="form-select @error('statut') is-invalid @enderror" 
                                id="statut" 
                                name="statut" 
                                required>
                            <option value="actif" {{ old('statut', $loyer->statut) === 'actif' ? 'selected' : '' }}>
                                Actif
                            </option>
                            <option value="inactif" {{ old('statut', $loyer->statut) === 'inactif' ? 'selected' : '' }}>
                                Inactif
                            </option>
                        </select>
                        @error('statut')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="notes" class="form-label">Notes / Conditions particulières</label>
                        <textarea class="form-control @error('notes') is-invalid @enderror" 
                                  id="notes" 
                                  name="notes" 
                                  rows="3" 
                                  placeholder="Conditions spéciales, remarques...">{{ old('notes', $loyer->notes) }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('loyers.index') }}" class="btn btn-secondary">Annuler</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Mettre à jour le contrat
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Informations du contrat</h5>
                <div class="alert alert-info">
                    <h6><i class="fas fa-info-circle"></i> Contrat #{{ $loyer->id }}</h6>
                    <ul class="mb-0">
                        <li><strong>Créé le :</strong> {{ $loyer->created_at->format('d/m/Y à H:i') }}</li>
                        <li><strong>Dernière modification :</strong> {{ $loyer->updated_at->format('d/m/Y à H:i') }}</li>
                        <li><strong>Statut actuel :</strong> 
                            @if($loyer->statut === 'actif')
                                <span class="badge bg-success">Actif</span>
                            @else
                                <span class="badge bg-secondary">Inactif</span>
                            @endif
                        </li>
                        @if($loyer->estEnCours())
                            <li><strong>Durée :</strong> {{ $loyer->duree }}</li>
                        @endif
                    </ul>
                </div>
                
                <div class="mt-3">
                    <h6>Actions disponibles</h6>
                    <div class="d-grid gap-2">
                        <a href="{{ route('loyers.show', $loyer) }}" class="btn btn-outline-info btn-sm">
                            <i class="fas fa-eye"></i> Voir les détails
                        </a>
                        @if($loyer->statut === 'actif')
                            <form action="{{ route('loyers.desactiver', $loyer) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-outline-danger btn-sm w-100" 
                                        onclick="return confirm('Êtes-vous sûr de vouloir désactiver ce contrat ?')">
                                    <i class="fas fa-times-circle"></i> Désactiver le contrat
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Calculer la durée automatiquement
    const dateDebutInput = document.getElementById('date_debut');
    const dateFinInput = document.getElementById('date_fin');
    
    function calculateDuration() {
        if (dateDebutInput.value && dateFinInput.value) {
            const dateDebut = new Date(dateDebutInput.value);
            const dateFin = new Date(dateFinInput.value);
            
            if (dateFin > dateDebut) {
                const diffTime = Math.abs(dateFin - dateDebut);
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                const diffMonths = Math.round(diffDays / 30);
                
                // Afficher la durée quelque part si nécessaire
                console.log(`Durée: ${diffMonths} mois environ`);
            }
        }
    }
    
    dateDebutInput.addEventListener('change', calculateDuration);
    dateFinInput.addEventListener('change', calculateDuration);
});
</script>
@endsection