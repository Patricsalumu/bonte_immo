@extends('layouts.app')

@section('title', 'Créer un Contrat de Loyer')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Créer un Contrat de Loyer</h1>
    <a href="{{ route('loyers.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Retour
    </a>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('loyers.store') }}">
                    @csrf

                    <h5 class="mb-3">Informations du contrat</h5>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="locataire_id" class="form-label">Locataire <span class="text-danger">*</span></label>
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
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="appartement_id" class="form-label">Appartement <span class="text-danger">*</span></label>
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
                                    </option>
                                @endforeach
                            </select>
                            @error('appartement_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
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

                        <div class="col-md-4 mb-3">
                            <label for="date_fin" class="form-label">Date de fin</label>
                            <input type="date" 
                                   class="form-control @error('date_fin') is-invalid @enderror" 
                                   id="date_fin" 
                                   name="date_fin" 
                                   value="{{ old('date_fin') }}">
                            @error('date_fin')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="duree_mois" class="form-label">Durée (mois)</label>
                            <input type="number" 
                                   class="form-control @error('duree_mois') is-invalid @enderror" 
                                   id="duree_mois" 
                                   name="duree_mois" 
                                   value="{{ old('duree_mois', 12) }}" 
                                   min="1">
                            @error('duree_mois')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="montant_loyer" class="form-label">Montant du loyer (CDF) <span class="text-danger">*</span></label>
                            <input type="number" 
                                   class="form-control @error('montant_loyer') is-invalid @enderror" 
                                   id="montant_loyer" 
                                   name="montant_loyer" 
                                   value="{{ old('montant_loyer') }}" 
                                   min="0" 
                                   required>
                            @error('montant_loyer')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="garantie_versee" class="form-label">Garantie versée (CDF)</label>
                            <input type="number" 
                                   class="form-control @error('garantie_versee') is-invalid @enderror" 
                                   id="garantie_versee" 
                                   name="garantie_versee" 
                                   value="{{ old('garantie_versee') }}" 
                                   min="0">
                            @error('garantie_versee')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="jour_echeance" class="form-label">Jour d'échéance</label>
                            <select class="form-select @error('jour_echeance') is-invalid @enderror" 
                                    id="jour_echeance" 
                                    name="jour_echeance">
                                @for($i = 1; $i <= 31; $i++)
                                    <option value="{{ $i }}" {{ old('jour_echeance', 1) == $i ? 'selected' : '' }}>
                                        {{ $i }}
                                    </option>
                                @endfor
                            </select>
                            @error('jour_echeance')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="conditions_particulieres" class="form-label">Conditions particulières</label>
                        <textarea class="form-control @error('conditions_particulieres') is-invalid @enderror" 
                                  id="conditions_particulieres" 
                                  name="conditions_particulieres" 
                                  rows="3">{{ old('conditions_particulieres') }}</textarea>
                        @error('conditions_particulieres')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="form-check">
                                <input class="form-check-input" 
                                       type="checkbox" 
                                       id="charges_incluses" 
                                       name="charges_incluses" 
                                       value="1" 
                                       {{ old('charges_incluses') ? 'checked' : '' }}>
                                <label class="form-check-label" for="charges_incluses">
                                    Charges incluses dans le loyer
                                </label>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="form-check">
                                <input class="form-check-input" 
                                       type="checkbox" 
                                       id="actif" 
                                       name="actif" 
                                       value="1" 
                                       {{ old('actif', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="actif">
                                    Contrat actif
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
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
        <div class="card bg-light">
            <div class="card-body">
                <h6 class="card-title">
                    <i class="fas fa-info-circle"></i> Informations
                </h6>
                <ul class="small text-muted mb-0">
                    <li>Les champs marqués d'un <span class="text-danger">*</span> sont obligatoires</li>
                    <li>Seuls les appartements disponibles sont proposés</li>
                    <li>Le montant du loyer se remplit automatiquement</li>
                    <li>La garantie recommandée est de 2-3 mois de loyer</li>
                    <li>Le jour d'échéance détermine la date de paiement mensuel</li>
                </ul>
            </div>
        </div>

        <div class="card bg-info text-white mt-3">
            <div class="card-body">
                <h6 class="card-title">
                    <i class="fas fa-lightbulb"></i> Suggestion
                </h6>
                <p class="small mb-0">
                    Une fois le contrat créé, les factures de loyer seront générées automatiquement selon la périodicité définie.
                </p>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('appartement_id').addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    const loyerMensuel = selectedOption.getAttribute('data-loyer');
    const garantie = selectedOption.getAttribute('data-garantie');
    
    if (loyerMensuel) {
        document.getElementById('montant_loyer').value = loyerMensuel;
    }
    if (garantie) {
        document.getElementById('garantie_versee').value = garantie;
    }
});

document.getElementById('duree_mois').addEventListener('change', function() {
    const dateDebut = document.getElementById('date_debut').value;
    if (dateDebut && this.value) {
        const debut = new Date(dateDebut);
        debut.setMonth(debut.getMonth() + parseInt(this.value));
        document.getElementById('date_fin').value = debut.toISOString().split('T')[0];
    }
});
</script>
@endsection