<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Paiement extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'loyer_id',
        'locataire_id',
        'facture_id',
        'compte_id',
        'montant',
        'date_paiement',
        'mode_paiement',
        'reference_paiement',
        'utilisateur_id',
        'est_annule',
        'notes',
    ];

    protected $casts = [
        'montant' => 'decimal:2',
        'date_paiement' => 'date',
        'est_annule' => 'boolean',
    ];

    public function loyer()
    {
        return $this->belongsTo(Loyer::class);
    }

    public function locataire()
    {
        return $this->belongsTo(Locataire::class);
    }

    public function facture()
    {
        return $this->belongsTo(Facture::class);
    }

    public function compte()
    {
        return $this->belongsTo(CompteFinancier::class, 'compte_id');
    }

    public function utilisateur()
    {
        return $this->belongsTo(User::class, 'utilisateur_id');
    }

    public function annuler()
    {
        $this->est_annule = true;
        $this->save();
        
        // Mettre à jour le statut du loyer
        $this->loyer->mettreAJourStatut();
        
        // Mettre à jour la garantie restante
        $this->mettreAJourGarantieRestante();
    }

    public function mettreAJourGarantieRestante()
    {
        if ($this->mode_paiement === 'garantie_locative') {
            $locataire = $this->locataire;
            $garantieUtilisee = $locataire->paiements()
                ->where('mode_paiement', 'garantie_locative')
                ->where('est_annule', false)
                ->sum('montant');
            
            $garantieRestante = $locataire->garantie_initiale - $garantieUtilisee;
            
            // Mettre à jour tous les loyers futurs
            $locataire->loyers()
                ->where('date_echeance', '>=', $this->date_paiement)
                ->update(['garantie_restante' => $garantieRestante]);
        }
    }

    public function scopeActifs($query)
    {
        return $query->where('est_annule', false);
    }

    public function scopeParMode($query, $mode)
    {
        return $query->where('mode_paiement', $mode);
    }

    public function scopeParPeriode($query, $dateDebut, $dateFin)
    {
        return $query->whereBetween('date_paiement', [$dateDebut, $dateFin]);
    }
}