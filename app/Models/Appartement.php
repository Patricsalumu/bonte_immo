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

    public function liberer()
    {
        $this->update(['statut' => 'libre']);
        $this->locataire?->update(['date_sortie' => now()]);
    }

    public function occuper($locataireId)
    {
        $this->update(['statut' => 'occupe']);
        // Génération automatique du loyer du mois courant
        $this->genererLoyerMoisCourant($locataireId);
    }

    private function genererLoyerMoisCourant($locataireId)
    {
        $mois = now()->month;
        $annee = now()->year;

        Loyer::firstOrCreate([
            'appartement_id' => $this->id,
            'mois' => $mois,
            'annee' => $annee,
        ], [
            'locataire_id' => $locataireId,
            'montant' => $this->loyer_mensuel,
            'date_echeance' => now()->endOfMonth(),
            'garantie_restante' => Locataire::find($locataireId)->garantie_initiale ?? 0,
        ]);
    }

    public function revenuGenere()
    {
        return $this->loyers()->where('statut', 'paye')->sum('montant');
    }
}