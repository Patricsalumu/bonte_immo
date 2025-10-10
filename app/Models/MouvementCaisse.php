<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MouvementCaisse extends Model
{
    use HasFactory;

    protected $table = 'mouvements_caisses';

    protected $fillable = [
        'compte_source_id',
        'compte_destination_id',
        'type_mouvement',
        'montant',
        'mode_paiement',
        'description',
        'categorie',
        'utilisateur_id',
        'date_operation',
        'est_annule',
    ];

    protected $casts = [
        'montant' => 'decimal:2',
        'date_operation' => 'date',
        'est_annule' => 'boolean',
    ];

    public function compteSource()
    {
        return $this->belongsTo(CompteFinancier::class, 'compte_source_id');
    }

    public function compteDestination()
    {
        return $this->belongsTo(CompteFinancier::class, 'compte_destination_id');
    }

    public function utilisateur()
    {
        return $this->belongsTo(User::class, 'utilisateur_id');
    }

    public function annuler()
    {
        $this->est_annule = true;
        $this->save();

        // Annuler les mouvements de solde
        if ($this->type_mouvement === 'entree' && $this->compteDestination) {
            $this->compteDestination->debiter($this->montant);
        } elseif ($this->type_mouvement === 'sortie' && $this->compteSource) {
            $this->compteSource->crediter($this->montant);
        } elseif ($this->type_mouvement === 'transfert') {
            if ($this->compteSource) {
                $this->compteSource->crediter($this->montant);
            }
            if ($this->compteDestination) {
                $this->compteDestination->debiter($this->montant);
            }
        }
    }

    public function scopeActifs($query)
    {
        return $query->where('est_annule', false);
    }

    public function scopeParType($query, $type)
    {
        return $query->where('type_mouvement', $type);
    }

    public function scopeParCompte($query, $compteId)
    {
        return $query->where('compte_source_id', $compteId)
            ->orWhere('compte_destination_id', $compteId);
    }

    public function scopeParPeriode($query, $dateDebut, $dateFin)
    {
        return $query->whereBetween('date_operation', [$dateDebut, $dateFin]);
    }

    public function getTypeLibelleAttribute()
    {
        $types = [
            'entree' => 'Entrée',
            'sortie' => 'Sortie',
            'transfert' => 'Transfert',
        ];

        return $types[$this->type_mouvement] ?? $this->type_mouvement;
    }
}