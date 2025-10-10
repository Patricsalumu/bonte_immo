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
        'mois',
        'annee',
        'montant',
        'statut',
        'date_echeance',
        'garantie_restante',
    ];

    protected $casts = [
        'montant' => 'decimal:2',
        'date_echeance' => 'date',
        'garantie_restante' => 'decimal:2',
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

    public function estPaye()
    {
        return $this->statut === 'paye';
    }

    public function estImpaye()
    {
        return $this->statut === 'impaye';
    }

    public function estPartiel()
    {
        return $this->statut === 'partiel';
    }

    public function mettreAJourStatut()
    {
        $montantPaye = $this->montantPaye();
        
        if ($montantPaye == 0) {
            $this->statut = 'impaye';
        } elseif ($montantPaye >= $this->montant) {
            $this->statut = 'paye';
        } else {
            $this->statut = 'partiel';
        }

        $this->save();
    }

    public function estEnRetard()
    {
        return $this->date_echeance->isPast() && !$this->estPaye();
    }

    public function getPeriodeAttribute()
    {
        $moisFr = [
            1 => 'Janvier', 2 => 'Février', 3 => 'Mars', 4 => 'Avril',
            5 => 'Mai', 6 => 'Juin', 7 => 'Juillet', 8 => 'Août',
            9 => 'Septembre', 10 => 'Octobre', 11 => 'Novembre', 12 => 'Décembre'
        ];

        return $moisFr[$this->mois] . ' ' . $this->annee;
    }
}