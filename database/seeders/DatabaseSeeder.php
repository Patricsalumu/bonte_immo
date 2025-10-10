<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\CompteFinancier;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Créer un administrateur par défaut
        User::create([
            'nom' => 'Administrateur',
            'email' => 'admin@labonteimmo.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'actif' => true,
        ]);

        // Créer un gestionnaire de test
        User::create([
            'nom' => 'Gestionnaire Test',
            'email' => 'gestionnaire@labonteimmo.com',
            'password' => Hash::make('password'),
            'role' => 'gestionnaire',
            'actif' => true,
        ]);

        // Créer les comptes financiers de base
        $comptes = [
            [
                'nom_compte' => 'Caisse Principale',
                'type_compte' => 'caisse',
                'solde_actuel' => 0,
                'description' => 'Caisse principale de l\'entreprise'
            ],
            [
                'nom_compte' => 'Compte Banque',
                'type_compte' => 'banque',
                'solde_actuel' => 0,
                'description' => 'Compte bancaire principal'
            ],
            [
                'nom_compte' => 'Compte Charges',
                'type_compte' => 'charges',
                'solde_actuel' => 0,
                'description' => 'Compte pour les dépenses et charges'
            ],
            [
                'nom_compte' => 'Compte Gestionnaire',
                'type_compte' => 'gestionnaire',
                'solde_actuel' => 0,
                'description' => 'Compte du gestionnaire terrain'
            ]
        ];

        foreach ($comptes as $compte) {
            CompteFinancier::create($compte);
        }

        $this->command->info('Utilisateurs et comptes financiers créés avec succès !');
        $this->command->info('Admin: admin@labonteimmo.com / password');
        $this->command->info('Gestionnaire: gestionnaire@labonteimmo.com / password');
    }
}