<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Facture extends Model
{
    use HasFactory;

    protected $fillable = [
        'loyer_id',
        'locataire_id', 
        'numero_facture',
        'mois',
        'annee',
        'montant',
        'date_echeance',
        'statut_paiement',
        'date_paiement',
        'montant_paye',
        'notes'
    ];

    protected $casts = [
        'date_echeance' => 'date',
        'date_paiement' => 'date',
        'montant' => 'decimal:2',
        'montant_paye' => 'decimal:2'
    ];

    // Relations
    public function loyer()
    {
        return $this->belongsTo(Loyer::class);
    }

    public function locataire()
    {
        return $this->belongsTo(Locataire::class);
    }

    public function paiements()
    {
        return $this->hasMany(Paiement::class);
    }

    public function appartement()
    {
        return $this->hasOneThrough(Appartement::class, Loyer::class, 'id', 'id', 'loyer_id', 'appartement_id');
    }

    // Scopes
    public function scopeNonPayees($query)
    {
        return $query->where('statut_paiement', 'non_paye');
    }

    public function scopePayees($query)
    {
        return $query->whereIn('statut_paiement', ['paye', 'paye_en_retard']);
    }

    public function scopeEnRetard($query)
    {
        return $query->where('statut_paiement', 'non_paye')
                    ->where('date_echeance', '<', now());
    }

    public function scopePourMois($query, $mois, $annee)
    {
        return $query->where('mois', $mois)->where('annee', $annee);
    }

    public function scopePourLocataire($query, $locataireId)
    {
        return $query->where('locataire_id', $locataireId);
    }

    // Accessors & Mutators
    public function getPeriodeAttribute()
    {
        return str_pad($this->mois, 2, '0', STR_PAD_LEFT) . '/' . $this->annee;
    }

    public function getEstEnRetardAttribute()
    {
        return $this->statut_paiement === 'non_paye' && $this->date_echeance < now();
    }

    public function getJoursRetardAttribute()
    {
        if (!$this->est_en_retard) {
            return 0;
        }
        return now()->diffInDays($this->date_echeance);
    }

    public function getMontantRestantAttribute()
    {
        return $this->montant - $this->montant_paye;
    }

    /**
     * Calculer le montant total payé via les paiements
     */
    public function montantPaye()
    {
        return $this->paiements()
                    ->where('est_annule', false)
                    ->sum('montant');
    }

    public function getEstPayeeAttribute()
    {
        return in_array($this->statut_paiement, ['paye', 'paye_en_retard']);
    }

    // Méthodes utilitaires
    public static function genererNumeroFacture()
    {
        $derniere = self::orderBy('id', 'desc')->first();
        $numero = $derniere ? intval(substr($derniere->numero_facture, 3)) + 1 : 1;
        return 'FAC' . str_pad($numero, 5, '0', STR_PAD_LEFT);
    }

    public function marquerCommePayee($montant = null, $date = null)
    {
        $montant = $montant ?? $this->montant;
        $date = $date ?? now();
        
        $this->update([
            'montant_paye' => $montant,
            'date_paiement' => $date,
            'statut_paiement' => $date > $this->date_echeance ? 'paye_en_retard' : 'paye'
        ]);
    }

    public static function genererFacturesPourMois($mois, $annee)
    {
        // Récupérer tous les loyers actifs
        $loyers = Loyer::with(['locataire', 'appartement'])
                       ->where('statut', 'actif')
                       ->get();

        $facturesCreees = 0;

        foreach ($loyers as $loyer) {
            // Vérifier si une facture existe déjà pour ce mois
            $factureExistante = self::where('loyer_id', $loyer->id)
                                   ->where('mois', $mois)
                                   ->where('annee', $annee)
                                   ->first();

            if (!$factureExistante) {
                // Au Congo, on facture le mois précédent
                // Le 1er octobre, on facture septembre (mois écoulé)
                // La date d'échéance est fixée au 5 du mois courant
                $dateEcheance = Carbon::create($annee, $mois, 5);
                
                self::create([
                    'loyer_id' => $loyer->id,
                    'locataire_id' => $loyer->locataire_id,
                    'numero_facture' => self::genererNumeroFacture(),
                    'mois' => $mois, // Mois pour lequel on facture (mois écoulé)
                    'annee' => $annee,
                    'montant' => $loyer->montant,
                    'date_echeance' => $dateEcheance, // Échéance dans le mois courant
                    'statut_paiement' => 'non_paye'
                ]);
                
                $facturesCreees++;
            }
        }

        return $facturesCreees;
    }

    /**
     * Génère automatiquement les factures pour le mois précédent
     * À appeler chaque 1er du mois
     */
    public static function genererFacturesMoisPrecedent()
    {
        $maintenant = now();
        $moisPrecedent = $maintenant->copy()->subMonth();
        
        return self::genererFacturesPourMois(
            $moisPrecedent->month, 
            $moisPrecedent->year
        );
    }

    // Méthodes pour le template PDF
    public function getMoisNom()
    {
        $mois = [
            1 => 'Janvier', 2 => 'Février', 3 => 'Mars', 4 => 'Avril',
            5 => 'Mai', 6 => 'Juin', 7 => 'Juillet', 8 => 'Août',
            9 => 'Septembre', 10 => 'Octobre', 11 => 'Novembre', 12 => 'Décembre'
        ];
        
        return $mois[$this->mois] ?? '';
    }

    public function estPayee()
    {
        return in_array($this->statut_paiement, ['paye', 'paye_en_retard']);
    }

    public function estPartielementPayee()
    {
        return $this->montant_paye > 0 && $this->montant_paye < $this->montant;
    }

    public function estEnRetard()
    {
        return $this->statut_paiement === 'non_paye' && $this->date_echeance < now();
    }

    public function getJoursRetard()
    {
        if (!$this->estEnRetard()) {
            return 0;
        }
        return now()->diffInDays($this->date_echeance);
    }

    // Boot method pour les événements
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($facture) {
            if (empty($facture->numero_facture)) {
                $facture->numero_facture = self::genererNumeroFacture();
            }
        });
    }
}
