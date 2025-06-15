# DPharma - Gestion de Pharmacie

## 📋 Description
DPharma est une application web de gestion de pharmacie développée avec Laravel 12, Inertia.js et Vue.js. Elle permet de gérer les produits pharmaceutiques, les clients, les ventes et le personnel de la pharmacie de manière efficace et sécurisée.

## Features principales

### Gestion des Produits Pharmaceutiques
- Création, lecture, mise à jour et suppression (CRUD) des produits
- Gestion des stocks et des seuils d'alerte
- Suivi des dates de péremption
- Recherche et filtrage avancé

### Gestion des Clients
- Fiches clients complètes
- Historique des achats
- Gestion des profils clients

### Gestion des Ventes
- Enregistrement des ventes
- Suppression des ventes
- Mise à jour des ventes

### Gestion des Utilisateurs
- Rôles et permissions (Administrateur, Pharmacien)
- Création, lecture, mise à jour et suppression (CRUD) des utilisateurs

## 🛠️ Prérequis

- PHP 8.2 ou supérieur
- Composer
- Node.js 18+ et npm/pnpm
- Base de données (SQLite)
- Serveur web (Nginx/Apache)

## Installation

1. **Cloner le dépôt**
   ```bash
   git clone https://github.com/BoubakarPI/dpharma.boukary.dev.git
   cd dpharma.boukary.dev
   ```

2. **Installer les dépendances PHP**
   ```bash
   composer install
   ```

3. **Installer les dépendances JavaScript**
   ```bash
   npm install
   # ou
   pnpm install
   ```

4. **Configurer l'environnement**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Configurer la base de données**
   - Créer une base de données
   - Mettre à jour le fichier `.env` avec les informations de connexion

6. **Exécuter les migrations et les seeders**
   ```bash
   php artisan migrate --seed
   ```

7. **Compiler les assets**
   ```bash
   npm run build
   ```

8. **Lancer le serveur**
   ```bash
   php artisan serve
   ```


## 🔐 Identifiants de test

Un compte administrateur est créé par défaut :
- **Email**: hello@boukary.dev
- **Mot de passe**: password

## 🏗️ Architecture

Le projet suit une architecture N-tiers avec une séparation claire des préoccupations et plusieurs couches d'abstraction :

### Couches de l'Architecture

1. **Couche Présentation**
   - Contrôleurs (Http/Controllers)
   - Middleware d'authentification et d'autorisation
   - Form Request Validation
   - Ressources API pour la transformation des données

2. **Couche Application**
   - Services (App/Services)
   - DTOs (Data Transfer Objects)
   - Événements et écouteurs
   - Interfaces de services

3. **Couche Domaine**
   - Modèles Eloquent
   - Repositories et Interfaces
   - Logique métier spécifique
   - Événements de domaine

4. **Couche Infrastructure**
   - Implémentations des repositories
   - Fournisseurs de services
   - Configuration et migrations
   - Fournisseurs d'accès aux données

### Principes Clés
- **Inversion de contrôle** via le conteneur de services de Laravel
- **Injection de dépendances** pour un couplage faible
- **Repository Pattern** pour l'abstraction de l'accès aux données
- **Interfaces** pour définir les contrats entre les couches

### Exemple de Flux Typique
1. Le contrôleur reçoit la requête HTTP
2. La validation est effectuée via Form Request
3. Le contrôleur délègue au service approprié via son interface
4. Le service utilise les repositories et modèles pour exécuter la logique métier
5. Les données sont retournées au contrôleur via des DTOs
6. Le contrôleur retourne la réponse appropriée (JSON, Vue, etc.)

## 🔒 Sécurité

- Authentification avec Laravel Sanctum
- Protection CSRF
- Validation des données côté serveur
- Politiques d'autorisation
- Protection contre les attaques XSS et l'injection SQL

## 🧪 Tests

Pour exécuter les tests :
```bash
php artisan test
# ou
./vendor/bin/pest
```

## 📝 Licence

Ce projet est sous licence MIT. Voir le fichier `LICENSE` pour plus de détails.

## ✉️ Contact

Pour toute question ou suggestion, veuillez me contacter à [hello@boukary.dev](mailto:hello@boukary.dev).
