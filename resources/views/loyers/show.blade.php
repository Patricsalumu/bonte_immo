@extends('layouts.app')

@section('title', 'Contrat de Loyer #' . $loyer->id)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Contrat de Loyer #{{ $loyer->id }}</h1>
    <div>
        <a href="{{ route('loyers.edit', $loyer) }}" class="btn btn-warning">
            <i class="fas fa-edit"></i> Modifier
        </a>
        <a href="{{ route('loyers.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Retour
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    Informations du contrat
                    @if($loyer->statut === 'actif')
                        <span class="badge bg-success ms-2">Actif</span>
                    @else
                        <span class="badge bg-secondary ms-2">Inactif</span>
                    @endif
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Locataire</h6>
                        <p class="mb-1"><strong>{{ $loyer->locataire->nom }} {{ $loyer->locataire->prenom }}</strong></p>
                        <p class="text-muted mb-3">{{ $loyer->locataire->telephone }}</p>

                        <h6>Appartement</h6>
                        <p class="mb-1"><strong>{{ $loyer->appartement->immeuble->nom }}</strong></p>
                        <p class="mb-1">Appartement {{ $loyer->appartement->numero }}</p>
                        <p class="text-muted mb-3">{{ $loyer->appartement->type }} - {{ $loyer->appartement->superficie }} m²</p>
                    </div>
                    <div class="col-md-6">
                        <h6>Détails financiers</h6>
                        <p class="mb-1"><strong>Montant du loyer:</strong> {{ number_format($loyer->montant, 0, ',', ' ') }} $</p>
                        <p class="mb-3"><strong>Garantie locative:</strong> {{ number_format($loyer->garantie_locative, 0, ',', ' ') }} $</p>

                        <h6>Durée du contrat</h6>
                        <p class="mb-1"><strong>Date de début:</strong> {{ $loyer->date_debut->format('d/m/Y') }}</p>
                        @if($loyer->date_fin)
                            <p class="mb-1"><strong>Date de fin:</strong> {{ $loyer->date_fin->format('d/m/Y') }}</p>
                            <p class="text-muted mb-3">{{ $loyer->duree }}</p>
                        @else
                            <p class="text-muted mb-3">Contrat à durée indéterminée</p>
                        @endif
                    </div>
                </div>

                @if($loyer->notes)
                    <hr>
                    <h6>Notes et conditions particulières</h6>
                    <p class="mb-0">{{ $loyer->notes }}</p>
                @endif
            </div>
        </div>

        @if($loyer->factures && $loyer->factures->count() > 0)
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Factures liées à ce contrat</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Mois</th>
                                <th>Montant</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($loyer->factures as $facture)
                                <tr>
                                    <td>{{ $facture->getMoisNom() }} {{ $facture->annee }}</td>
                                    <td>{{ number_format($facture->montant, 0, ',', ' ') }} $</td>
                                    <td>
                                        @if($facture->estPayee())
                                            <span class="badge bg-success">Payée</span>
                                        @elseif($facture->estPartielementPayee())
                                            <span class="badge bg-warning">Partielle</span>
                                        @else
                                            <span class="badge bg-danger">Impayée</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('factures.show', $facture) }}" class="btn btn-sm btn-outline-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Actions</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('loyers.edit', $loyer) }}" class="btn btn-warning">
                        <i class="fas fa-edit"></i> Modifier le contrat
                    </a>
                    
                    @if($loyer->statut === 'actif')
                        <form action="{{ route('loyers.desactiver', $loyer) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-danger w-100" 
                                    onclick="return confirm('Êtes-vous sûr de vouloir désactiver ce contrat ?')">
                                <i class="fas fa-times-circle"></i> Désactiver le contrat
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header">
                <h5 class="card-title mb-0">Informations système</h5>
            </div>
            <div class="card-body">
                <p class="mb-1"><strong>ID:</strong> {{ $loyer->id }}</p>
                <p class="mb-1"><strong>Créé le:</strong> {{ $loyer->created_at->format('d/m/Y à H:i') }}</p>
                <p class="mb-0"><strong>Modifié le:</strong> {{ $loyer->updated_at->format('d/m/Y à H:i') }}</p>
            </div>
        </div>

        @if($loyer->estEnCours())
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="card-title mb-0">Statut du contrat</h5>
            </div>
            <div class="card-body">
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> Contrat en cours
                    <hr>
                    <small>Ce contrat est actuellement actif et en vigueur.</small>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection