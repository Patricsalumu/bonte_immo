<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompteFinancier extends Model
{
    use HasFactory;

    protected $table = 'comptes_financiers';

    protected $fillable = [
        'nom_compte',
        'type',
        'solde_actuel',
        'gestionnaire_id',
        'description',
        'actif',
        'autoriser_decouvert',
    ];

    protected $casts = [
        'solde_actuel' => 'decimal:2',
        'actif' => 'boolean',
        'autoriser_decouvert' => 'boolean',
    ];

    public function gestionnaire()
    {
        return $this->belongsTo(User::class, 'gestionnaire_id');
    }

    public function mouvementsSource()
    {
        return $this->hasMany(MouvementCaisse::class, 'compte_source_id');
    }

    public function mouvementsDestination()
    {
        return $this->hasMany(MouvementCaisse::class, 'compte_destination_id');
    }

    public function mouvements()
    {
        return MouvementCaisse::where('compte_source_id', $this->id)
            ->orWhere('compte_destination_id', $this->id)
            ->orderBy('date_operation', 'desc');
    }

    public function crediter($montant)
    {
        $this->solde_actuel += $montant;
        $this->save();
    }

    public function debiter($montant)
    {
        $this->solde_actuel -= $montant;
        $this->save();
    }

    public function transfererVers(CompteFinancier $compteDestination, $montant, $description, $utilisateur)
    {
        // Débiter le compte source
        $this->debiter($montant);
        
        // Créditer le compte destination
        $compteDestination->crediter($montant);
        
        // Enregistrer le mouvement
        MouvementCaisse::create([
            'compte_source_id' => $this->id,
            'compte_destination_id' => $compteDestination->id,
            'type_mouvement' => 'transfert',
            'montant' => $montant,
            'description' => $description,
            'utilisateur_id' => $utilisateur->id,
            'date_operation' => now(),
        ]);
    }

    public function scopeParType($query, $type)
    {
        return $query->where('type_compte', $type);
    }

    public function getTypeCompteLibelleAttribute()
    {
        $types = [
            'caisse' => 'Caisse',
            'banque' => 'Banque',
            'gestionnaire' => 'Gestionnaire',
            'charges' => 'Charges',
            'autre' => 'Autre',
        ];

        return $types[$this->type_compte] ?? $this->type_compte;
    }
}