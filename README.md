# La Bonte Immo - Système de Gestion Immobilière

## 📋 Description

Application web complète de gestion immobilière développée avec Laravel pour l'entreprise **La Bonte Immo** située avenue de la révolution, Q. Industriel C. Lshi.

## ✨ Fonctionnalités

### 🏢 Gestion Immobilière
- **Immeubles** : Création, modification, consultation avec statistiques
- **Appartements** : Gestion complète avec statuts (libre/occupé/sous préavis)
- **Locataires** : Profils détaillés avec historique des paiements

### 💰 Gestion Financière
- **Loyers** : Génération automatique mensuelle avec calcul de garantie locative
- **Paiements** : Multi-modes (cash, virement, mobile money, garantie locative)
- **Caisse** : Système multi-comptes avec entrées, sorties et transferts
- **Garantie locative** : Calcul automatique et intégration dans les loyers

### 📊 Tableau de Bord & Rapports
- Statistiques en temps réel (appartements, revenus, factures impayées)
- Graphiques d'évolution des loyers sur 6 mois
- Export PDF/Excel des factures et rapports mensuels
- Suivi des garanties locatives par locataire

### 🔐 Authentification & Sécurité
- **Rôles utilisateur** :
  - **Admin** : Accès complet (ajout, modification, suppression, transferts)
  - **Gestionnaire** : Accès limité (lecture, ajout paiements, annulation)
- Middleware de protection des routes
- Soft delete pour toutes les données importantes

## 🛠 Technologies Utilisées

- **Backend** : Laravel 10 (PHP 8.1+)
- **Frontend** : Bootstrap 5, Chart.js
- **Base de données** : MySQL
- **Architecture** : MVC avec authentification par rôles
- **Export** : DomPDF, Laravel Excel

## 📦 Installation

### Prérequis
- PHP 8.1 ou supérieur
- Composer
- MySQL 5.7 ou supérieur
- Serveur web (Apache/Nginx) ou XAMPP

### Étapes d'installation

1. **Cloner le projet**
   ```bash
   git clone [repository-url]
   cd immo
   ```

2. **Installer les dépendances**
   ```bash
   composer install
   ```

3. **Configuration de l'environnement**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Configurer la base de données**
   ```
   # Dans le fichier .env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=labonte_immo
   DB_USERNAME=root
   DB_PASSWORD=
   ```

5. **Créer la base de données**
   ```sql
   CREATE DATABASE labonte_immo CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   ```

6. **Exécuter les migrations**
   ```bash
   php artisan migrate
   ```

7. **Créer un utilisateur administrateur**
   ```bash
   php artisan db:seed --class=AdminSeeder
   ```

8. **Démarrer le serveur de développement**
   ```bash
   php artisan serve
   ```

L'application sera accessible à l'adresse : `http://localhost:8000`

## 👤 Connexion par défaut

- **Email** : admin@labonteimmo.com
- **Mot de passe** : password
- **Rôle** : Admin

## 🏗 Structure de la Base de Données

### Tables principales :

- **users** : Utilisateurs avec rôles
- **immeubles** : Bâtiments de l'entreprise
- **appartements** : Unités locatives
- **locataires** : Profils des locataires
- **loyers** : Loyers mensuels avec garantie intégrée
- **paiements** : Historique des paiements
- **comptes_financiers** : Comptes de trésorerie
- **mouvements_caisses** : Journal des mouvements financiers

## 🚀 Utilisation

### Génération automatique des loyers
```bash
# Générer les loyers du mois courant
php artisan loyers:generer

# Générer pour un mois/année spécifique
php artisan loyers:generer --mois=12 --annee=2024
```

### Mise à jour des statuts
```bash
# Mettre à jour les statuts des loyers
php artisan loyers:mettre-a-jour-statuts
```

### Programmation automatique (Cron)
Ajouter dans le crontab pour automatiser :
```bash
# Génération automatique le 1er de chaque mois à 6h
0 6 1 * * cd /path/to/project && php artisan loyers:generer
```

## 📁 Structure du Projet

```
immo/
├── app/
│   ├── Console/Commands/      # Commandes Artisan
│   ├── Http/
│   │   ├── Controllers/       # Contrôleurs MVC
│   │   └── Middleware/        # Middleware de sécurité
│   └── Models/                # Modèles Eloquent
├── database/
│   └── migrations/            # Migrations de base de données
├── resources/
│   └── views/                 # Vues Blade
│       ├── layouts/           # Layout principal
│       ├── auth/              # Pages d'authentification
│       └── [modules]/         # Vues par module
├── routes/
│   └── web.php               # Routes web
└── config/                   # Fichiers de configuration
```

## 🔧 Maintenance

### Sauvegarde de la base de données
```bash
mysqldump -u root -p labonte_immo > backup_$(date +%Y%m%d).sql
```

### Nettoyage des logs
```bash
php artisan log:clear
```

### Optimisation en production
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## 📈 Fonctionnalités Avancées

### Système de Garantie Locative
- Calcul automatique de la garantie restante
- Utilisation de la garantie pour les paiements
- Mise à jour en temps réel

### Reporting Avancé
- Rapports mensuels par immeuble/locataire
- Export PDF des factures impayées
- Statistiques de trésorerie

### Audit Trail
- Historique complet des modifications
- Soft delete avec possibilité de restauration
- Journal des connexions utilisateur

## 🤝 Support

Pour toute question ou assistance :
- **Entreprise** : La Bonte Immo
- **Adresse** : Avenue de la révolution, Q. Industriel C. Lshi
- **Email** : contact@labonteimmo.com

## 📄 Licence

Ce projet est développé spécifiquement pour La Bonte Immo. Tous droits réservés.

---

**Version** : 1.0  
**Date de création** : Octobre 2025  
**Développé par** : GitHub Copilot