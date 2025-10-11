<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loyer extends Model
{
    use HasFactory;

    protected $fillable = [
        'appartement_id',
        'locataire_id',
        'montant',
        'date_debut',
        'date_fin',
        'statut', // 'actif' ou 'inactif'
        'garantie_locative',
        'notes'
    ];

    protected $casts = [
        'montant' => 'decimal:2',
        'date_debut' => 'date',
        'date_fin' => 'date',
        'garantie_locative' => 'decimal:2',
    ];

    public function appartement()
    {
        return $this->belongsTo(Appartement::class);
    }

    public function locataire()
    {
        return $this->belongsTo(Locataire::class);
    }

    public function paiements()
    {
        return $this->hasMany(Paiement::class);
    }

    public function factures()
    {
        return $this->hasMany(Facture::class);
    }

    public function montantPaye()
    {
        return $this->paiements()
            ->where('est_annule', false)
            ->sum('montant');
    }

    public function montantRestant()
    {
        return $this->montant - $this->montantPaye();
    }

    public function estActif()
    {
        return $this->statut === 'actif';
    }

    public function estInactif()
    {
        return $this->statut === 'inactif';
    }

    public function estEnCours()
    {
        $maintenant = now();
        return $this->estActif() && 
               (!$this->date_fin || $this->date_fin->isFuture()) &&
               $this->date_debut->isPast();
    }

    public function desactiver($motif = null)
    {
        $this->statut = 'inactif';
        if ($motif) {
            $this->notes = ($this->notes ? $this->notes . "\n" : '') . "Désactivé: " . $motif;
        }
        $this->save();
    }

    public function activer()
    {
        $this->statut = 'actif';
        $this->save();
    }

    public function getDureeAttribute()
    {
        if (!$this->date_debut || !$this->date_fin) {
            return 'Durée indéterminée';
        }
        
        $duree = $this->date_debut->diffInMonths($this->date_fin);
        return $duree . ' mois';
    }

    public function getStatutTextAttribute()
    {
        return $this->statut === 'actif' ? 'Contrat actif' : 'Contrat inactif';
    }

    // Scopes
    public function scopeActifs($query)
    {
        return $query->where('statut', 'actif');
    }

    public function scopeInactifs($query)
    {
        return $query->where('statut', 'inactif');
    }

    public function scopeEnCours($query)
    {
        $maintenant = now();
        return $query->where('statut', 'actif')
                    ->where('date_debut', '<=', $maintenant)
                    ->where(function($q) use ($maintenant) {
                        $q->whereNull('date_fin')
                          ->orWhere('date_fin', '>', $maintenant);
                    });
    }
}