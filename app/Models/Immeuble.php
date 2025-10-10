<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Immeuble extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nom',
        'adresse',
        'description',
    ];

    public function appartements()
    {
        return $this->hasMany(Appartement::class);
    }

    public function nombreAppartements()
    {
        return $this->appartements()->count();
    }

    public function revenus()
    {
        return $this->appartements()
            ->with('loyers')
            ->get()
            ->sum(function ($appartement) {
                return $appartement->loyers->where('statut', 'paye')->sum('montant');
            });
    }

    public function appartementLibres()
    {
        return $this->appartements()->where('statut', 'libre')->count();
    }

    public function appartementOccupes()
    {
        return $this->appartements()->where('statut', 'occupe')->count();
    }
}