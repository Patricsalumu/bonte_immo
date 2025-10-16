@if($factures->count() > 0)
    <tbody>
        @foreach($factures as $facture)
        <tr class="{{ $facture->estEnRetard() ? 'table-danger' : ($facture->estPayee() ? 'table-success' : '') }}">
            <td>
                <strong>{{ $facture->numero_facture }}</strong>
                <br>
                <small class="text-muted">{{ $facture->created_at->format('d/m/Y') }}</small>
            </td>
            <td>
                <div>
                    @if($facture->locataire)
                        <strong>{{ $facture->locataire->nom }} {{ $facture->locataire->prenom }}</strong>
                        <br>
                        <small class="text-muted">{{ $facture->locataire->telephone }}</small>
                    @else
                        <span class="text-muted">Locataire non trouvé</span>
                    @endif
                </div>
            </td>
            <td>
                <div>
                    @if($facture->loyer && $facture->loyer->appartement && $facture->loyer->appartement->immeuble)
                        <strong>{{ $facture->loyer->appartement->immeuble->nom }}</strong>
                        <br>
                        <small class="text-muted">Apt {{ $facture->loyer->appartement->numero }}</small>
                    @else
                        <span class="text-muted">Appartement non trouvé</span>
                    @endif
                </div>
            </td>
            <td>
                <strong>{{ $facture->getMoisNom() }} {{ $facture->annee }}</strong>
                <br>
                <small class="text-muted">Échéance: {{ $facture->date_echeance->format('d/m/Y') }}</small>
            </td>
            <td>
                <strong>{{ number_format($facture->montant, 0, ',', ' ') }} $</strong>
                @if($facture->montant_paye > 0)
                    <br>
                    <small class="text-success">Payé: {{ number_format($facture->montant_paye, 0, ',', ' ') }} $</small>
                @endif
            </td>
            <td>
                @php
                    $montantPaye = $facture->paiements->sum('montant');
                @endphp
                @if($montantPaye >= $facture->montant)
                    <span class="badge bg-success">Payée</span>
                @elseif($montantPaye > 0)
                    <span class="badge bg-warning">Partielle</span>
                @else
                    <span class="badge bg-danger">Non payée</span>
                @endif
            </td>
            <td>
                <div class="btn-group" role="group">
                    <a href="{{ route('factures.export-pdf', $facture) }}" 
                       class="btn btn-outline-primary btn-sm" 
                       target="_blank">
                        <i class="fas fa-file-pdf"></i> PDF
                    </a>
                    @if($facture->locataire && $facture->locataire->telephone)
                    <button type="button" 
                        class="btn btn-outline-success btn-sm" 
                        data-telephone="{{ $facture->locataire->telephone }}"
                        data-prenom="{{ $facture->locataire->prenom ?? '' }}"
                        data-nom="{{ $facture->locataire->nom }}"
                        data-numero="{{ $facture->numero_facture }}"
                        data-id="{{ $facture->id }}"
                        data-montant="{{ number_format($facture->montant, 0, ' ', ' ') }}"
                        data-mois="{{ $facture->getMoisNom() }} {{ $facture->annee }}"
                        data-echeance="{{ $facture->date_echeance->format('d/m/Y') }}"
                        onclick="partagerWhatsAppData(this)">
                        <i class="fab fa-whatsapp"></i> WhatsApp
                    </button>
                    @endif
                    @if($facture->peutRecevoirPaiement())
                        <button type="button"
                                class="btn btn-success btn-sm btn-open-modal-paiement"
                                data-bs-toggle="modal"
                                data-bs-target="#modalPaiement"
                                data-facture-id="{{ $facture->id }}"
                                data-loyer-id="{{ $facture->loyer_id }}"
                                data-numero="{{ $facture->numero_facture }}"
                                data-montant="{{ $facture->montant }}"
                                data-montant-restant="{{ $facture->montant - $facture->montantPaye() }}"
                                data-garantie="{{ $facture->loyer->garantie_locative ?? 0 }}">
                            <i class="fas fa-credit-card"></i> Payer

                            @if($facture->montantPaye() > 0)
                                <small>({{ number_format($facture->montantRestant(), 0, ',', ' ') }} $ restant)</small>
                            @endif
                        </button>
                    @endif
                    <a href="{{ route('factures.show', $facture) }}" 
                       class="btn btn-outline-info btn-sm">
                        <i class="fas fa-eye"></i>
                    </a>
                </div>
            </td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="7">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <p class="mb-0 text-muted">Affichage {{ $factures->firstItem() }} - {{ $factures->lastItem() }} sur {{ $factures->total() }} factures</p>
                    </div>
                    <div class="flex-fill text-center">
                        {{ $factures->links('vendor.pagination.custom') }}
                    </div>
                    <div class="flex-shrink-0"></div>
                </div>
            </td>
        </tr>
    </tfoot>
@else
    <tbody>
        <tr id="messageNoResult">
            <td colspan="7" class="text-center py-4"><i class="fas fa-search fa-2x text-muted mb-2"></i><br>Aucune facture ne correspond à vos critères de recherche.</td>
        </tr>
    </tbody>
@endif
