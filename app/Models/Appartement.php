<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Appartement extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'immeuble_id',
        'numero',
        'type',
        'superficie',
        'etage',
        'loyer_mensuel',
        'garantie_locative',
        'description',
        'meuble',
        'disponible',
        'statut',
        'locataire_id',
    ];

    protected $casts = [
        'superficie' => 'decimal:2',
        'loyer_mensuel' => 'decimal:2',
        'garantie_locative' => 'decimal:2',
        'meuble' => 'boolean',
        'disponible' => 'boolean',
    ];

    public function immeuble()
    {
        return $this->belongsTo(Immeuble::class);
    }

    public function locataire()
    {
        return $this->belongsTo(Locataire::class, 'locataire_id');
    }

    public function locataires()
    {
        return $this->hasMany(Locataire::class);
    }

    public function loyers()
    {
        return $this->hasMany(Loyer::class);
    }

    /**
     * Vérifie si l'appartement est actuellement disponible (sans contrat actif)
     */
    public function estDisponible()
    {
        return !$this->loyers()->enCours()->exists();
    }

    /**
     * Récupère le contrat de loyer actuel s'il existe
     */
    public function contratActuel()
    {
        return $this->loyers()->enCours()->first();
    }

    public function liberer()
    {
        $this->update([
            'statut' => 'libre',
            'locataire_id' => null
        ]);
        // Désactiver le contrat actuel s'il existe
        $contratActuel = $this->contratActuel();
        if ($contratActuel) {
            $contratActuel->desactiver('Appartement libéré');
        }
    }

    public function occuper($locataireId, $montantLoyer = null, $dateDebut = null)
    {
        $this->update(['statut' => 'occupe']);
        
        // Créer un nouveau contrat de loyer
        return Loyer::create([
            'appartement_id' => $this->id,
            'locataire_id' => $locataireId,
            'montant' => $montantLoyer ?? $this->loyer_mensuel,
            'date_debut' => $dateDebut ?? now(),
            'garantie_locative' => $this->garantie_locative,
            'statut' => 'actif'
        ]);
    }

    public function revenuGenere()
    {
        return $this->loyers()->sum('montant');
    }

    /**
     * Scope pour les appartements disponibles
     */
    public function scopeDisponibles($query)
    {
        return $query->whereDoesntHave('loyers', function($q) {
            $q->enCours();
        });
    }
}