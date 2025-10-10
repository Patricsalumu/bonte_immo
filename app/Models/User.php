<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'nom',
        'email',
        'password',
        'role',
        'actif',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'actif' => 'boolean',
    ];

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isGestionnaire()
    {
        return $this->role === 'gestionnaire';
    }

    public function paiements()
    {
        return $this->hasMany(Paiement::class, 'utilisateur_id');
    }

    public function mouvementsCaisses()
    {
        return $this->hasMany(MouvementCaisse::class, 'utilisateur_id');
    }
}