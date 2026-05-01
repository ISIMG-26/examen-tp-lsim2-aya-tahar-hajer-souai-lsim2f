# 🏠 DarLoc — Plateforme de location de maisons en Tunisie

Mini-projet web (LSIM 2 — Technologies & Programmation Web 2025-2026).

## 👥 Membres du groupe

- **Nom Prénom 1** — [à compléter]
- **Nom Prénom 2** — [à compléter]
- **Nom Prénom 3** — [à compléter]
- **Section :** Groupe [A / B / C / D / E / F]

## 📝 Description du projet

DarLoc est une plateforme de location de maisons et appartements en Tunisie permettant aux utilisateurs de :

- 🔍 **Rechercher** des logements par mot-clé, ville, type (S+1, S+2, S+3, Villa, Duplex…) et budget
- 📋 **Consulter** la fiche détaillée de chaque bien (photos, prix, surface, équipements, description)
- 📅 **Réserver** un logement disponible (compte client requis)
- 🛠 **Gérer** les annonces via un tableau de bord administrateur (CRUD complet)

## 🛠 Technologies utilisées

| Couche       | Technologie                          |
| ------------ | ------------------------------------ |
| Front-end    | HTML5 sémantique, CSS3 externe       |
| Interactivité| JavaScript natif (DOM, Fetch / AJAX) |
| Back-end     | PHP natif (PDO)                      |
| Base de données | MySQL                             |

> Conformément au sujet : **aucun framework** (React, Angular, Laravel, Bootstrap…) n'est utilisé.

## 📁 Structure du projet

```
darloc-php/
├── index.php               ← Page d'accueil (liste des maisons)
├── README.md
├── css/
│   └── style.css           ← Feuille de style externe (design system)
├── js/
│   └── script.js           ← JS natif : DOM + validation + AJAX
├── images/                 ← Photos des maisons
├── html/
│   ├── login.php           ← Page de connexion
│   ├── register.php        ← Page d'inscription
│   ├── details.php         ← Fiche détaillée d'une maison
│   ├── admin.php           ← Tableau de bord admin
│   └── mes-reservations.php← Page client : ses réservations
├── back/                   ← Scripts PHP (logique serveur)
│   ├── db.php              ← Connexion PDO + helpers session
│   ├── login.php
│   ├── register.php
│   ├── logout.php
│   ├── search.php          ← Endpoint AJAX (filtrage maisons → JSON)
│   ├── check_email.php     ← Endpoint AJAX (vérification email)
│   ├── reserver.php
│   ├── resa_cancel.php
│   ├── maison_save.php     ← Ajout / modification (admin)
│   └── maison_delete.php   ← Suppression (admin)
└── database/
    └── script.sql          ← Script de création de la base
```

## 🚀 Installation locale (WAMP / XAMPP / MAMP)

1. **Copier le projet** dans le dossier `www/` (WAMP) ou `htdocs/` (XAMPP) :
   ```
   C:/wamp64/www/darloc-php/
   ```
2. **Démarrer Apache + MySQL** depuis le panneau WAMP / XAMPP.
3. **Créer la base de données :**
   - Ouvrir [phpMyAdmin](http://localhost/phpmyadmin)
   - Importer le fichier `database/script.sql`
   - (ou exécuter le contenu du fichier SQL dans l'onglet SQL)
4. **Accéder au site :**
   ```
   http://localhost/darloc-php/
   ```

## 🔐 Comptes de démonstration

| Rôle   | Email             | Mot de passe |
| ------ | ----------------- | ------------ |
| Admin  | admin@darloc.tn   | admin123     |
| Client | (créez un compte via la page Inscription) |

> Le mot de passe admin est automatiquement hashé à la première connexion.

## ✅ Checklist du sujet — toutes les exigences couvertes

### 1. Structure HTML ✔
- 6+ pages interconnectées (index, login, register, details, admin, mes-reservations)
- Balises sémantiques : `<header>`, `<nav>`, `<section>`, `<article>`, `<aside>`, `<footer>`
- Menu de navigation cohérent sur toutes les pages

### 2. CSS externe ✔
- Un seul fichier `css/style.css`
- Design system (variables CSS, palette terracotta/sable/olive)
- Responsive (mobile, tablette, desktop)

### 3. JavaScript — DOM ✔
- `getElementById`, `querySelector`, `addEventListener`
- Modification dynamique du contenu (cartes maisons, label prix)
- Ajout/suppression d'éléments (génération dynamique des cartes)
- Gestion d'événements : `submit`, `input`, `change`, `click`, `blur`

### 4. Validation des formulaires (JS) ✔
- Champs obligatoires vérifiés (login, register, admin)
- Validation regex email + longueur mot de passe
- Affichage de messages d'erreur sous chaque champ
- Blocage de la soumission via `e.preventDefault()`

### 5. AJAX ✔
- `fetch()` utilisé dans `search.php` (recherche temps réel sans rechargement)
- `fetch()` dans `check_email.php` (vérification unicité email à l'inscription)
- Mise à jour dynamique de la grille de résultats

### 6. PHP back-end ✔
- Utilisation de `$_POST` (login, register, save, reserver) et `$_GET` (search, delete, edit)
- Traitement complet des formulaires + redirections
- Code organisé en scripts séparés (1 responsabilité par fichier)
- Gestion de session pour l'authentification

### 7. MySQL ✔
- 3 tables liées : `users`, `maisons`, `reservations` (FK avec ON DELETE CASCADE)
- CRUD complet :
  - **SELECT** : `index.php`, `search.php`, `details.php`, `admin.php`, `mes-reservations.php`
  - **INSERT** : `register.php`, `reserver.php`, `maison_save.php`
  - **UPDATE** : `maison_save.php`, `resa_cancel.php`, `reserver.php`
  - **DELETE** : `maison_delete.php`
- Affichage dynamique sur toutes les pages

### 8. Qualité ✔
- Design moderne (palette méditerranéenne, typographie Playfair Display + Inter)
- Images bien dimensionnées avec `loading="lazy"`
- Navigation intuitive

## 🔒 Sécurité

- Mots de passe hashés avec `password_hash()` (bcrypt)
- Requêtes préparées PDO (anti-injection SQL)
- Échappement HTML systématique avec `htmlspecialchars()` (helper `e()`)
- Vérification de session sur les pages protégées
- Vérification du rôle admin sur les actions sensibles

## 📋 Répartition des tâches (à adapter selon votre groupe)

| Membre   | Tâches principales |
| -------- | ------------------ |
| Étudiant 1 | Structure HTML, design CSS, intégration |
| Étudiant 2 | JavaScript (DOM, validation, AJAX) |
| Étudiant 3 | Back-end PHP, base de données MySQL, sécurité |

---

📅 **Date de soumission :** [à compléter]
🏫 **Encadrant :** Mme Neila CHETTAOUI
