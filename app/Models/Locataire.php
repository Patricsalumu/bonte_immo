<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Locataire extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nom',
        'prenom',
        'date_naissance',
        'telephone',
        'email',
        'adresse',
        'profession',
        'employeur',
        'revenu_mensuel',
        'numero_carte_identite',
        'contact_urgence_nom',
        'contact_urgence_telephone',
        'notes',
        'actif',
        'appartement_id',
        'date_entree',
        'date_sortie',
        'garantie_initiale',
    ];

    protected $casts = [
        'date_entree' => 'date',
        'date_sortie' => 'date',
        'garantie_initiale' => 'decimal:2',
    ];

    public function appartement()
    {
        return $this->belongsTo(Appartement::class);
    }

    public function loyers()
    {
        return $this->hasMany(Loyer::class);
    }

    public function paiements()
    {
        return $this->hasMany(Paiement::class);
    }

    public function factures()
    {
        return $this->hasMany(Facture::class);
    }

    public function garantieRestante()
    {
        $utilisationGarantie = $this->paiements()
            ->where('mode_paiement', 'garantie_locative')
            ->where('est_annule', false)
            ->sum('montant');

        return $this->garantie_initiale - $utilisationGarantie;
    }

    public function loyersImpayes()
    {
        return $this->loyers()->where('statut', 'impaye')->get();
    }

    public function historiquePaiements()
    {
        return $this->paiements()
            ->where('est_annule', false)
            ->orderBy('date_paiement', 'desc')
            ->get();
    }

    /**
     * Vérifie si le locataire a un contrat de loyer actif
     */
    public function aContratActif()
    {
        return $this->loyers()->enCours()->exists();
    }

    /**
     * Récupère le contrat de loyer actuel s'il existe
     */
    public function contratActuel()
    {
        return $this->loyers()->enCours()->first();
    }

    /**
     * Vérifie si le locataire est disponible (sans contrat actif)
     */
    public function estDisponible()
    {
        return !$this->aContratActif();
    }

    public function estActif()
    {
        return is_null($this->date_sortie) || $this->date_sortie->isFuture();
    }

    /**
     * Scope pour les locataires disponibles (sans contrat actif)
     */
    public function scopeDisponibles($query)
    {
        return $query->whereDoesntHave('loyers', function($q) {
            $q->enCours();
        });
    }
}