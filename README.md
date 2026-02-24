# GestionColoc

Application web de gestion de colocation développée avec Laravel. Elle permet de centraliser la gestion des colocations, de suivre les dépenses partagées et d'automatiser le calcul des soldes entre membres.

---

## Technologies utilisées

- PHP 8.x / Laravel 10
- MySQL
- Blade + Tailwind CSS
- Laravel Breeze (authentification)
- Eloquent ORM

---

## Fonctionnalités

**Authentification**
- Inscription et connexion
- Gestion du profil utilisateur
- Premier inscrit promu Admin Global automatiquement
- Blocage automatique des utilisateurs bannis

**Colocations**
- Création d'une colocation (owner automatique)
- Invitation des membres par email/token
- Une seule colocation active par utilisateur
- Départ d'un membre
- Annulation d'une colocation

**Dépenses**
- Ajout d'une dépense (titre, montant, date, catégorie, payeur)
- Historique des dépenses
- Filtrage par mois
- Statistiques par catégorie

**Balances et Paiements**
- Calcul automatique des soldes individuels
- Vue "qui doit à qui"
- Enregistrement des paiements (Marquer payé)

**Réputation**
- +1 si départ ou annulation sans dette
- -1 si départ ou annulation avec dette

**Administration**
- Dashboard global (utilisateurs, colocations, dépenses)
- Bannir / débannir des utilisateurs

---


## Structure du projet

```
app/
  Http/
    Controllers/       # Logique des requêtes
    Requests/          # Validation des formulaires
  Models/              # Modèles Eloquent
  Services/            # Logique métier (calcul balances, réputation)
database/
  migrations/          # Structure de la base de données
resources/
  views/               # Templates Blade
routes/
  web.php              # Routes de l'application
```

---

## Rôles

| Rôle | Description |
|------|-------------|
| Member | Membre standard d'une colocation |
| Owner | Créateur et administrateur de sa colocation |
| Admin Global | Administrateur de la plateforme |

---

## Diagrammes UML

Les diagrammes UML du projet sont disponibles dans le dossier `/docs` :

- Diagramme des cas d'utilisation
- Diagramme de classes
- Diagramme ERD

