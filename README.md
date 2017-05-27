Wiki-API
========

**Réalisé par Mavillaz Rémi and Paul Girardin**

Github KizeRemi: https://github.com/KizeRemi  
GitHub PaulGirardin: https://github.com/PaulGirardin

Projet Wiki pour une école primaire. Le projet est décomposé en 2 parties : API et Interface utilisateur. Ce dépot comporte uniquement la partie API réalisé sous Symfony 3.2


## Fonctionnalités

- [x] Inscription Utilisateur
- [x] Connexion/déconnexion
- [x] Gestion du profil
- [x] Gestion des rôles (anon, user, admin)
- [x] Gestion des pages
- [ ] Recherche

### Optionnel
- [ ] Upload d'images
- [ ] Système d'experience
- [ ] Commentaires
- [x] Catégories

## Installation

Pré-requis : Composer

Ouvrez le terminal de votre ordinateur, allez dans le dossier d'installation du projet et cloner le dépot

```
git clone https://github.com/KizeRemi/Wiki-API.git

```

Puis
```
composer install

```
Suivre la procédure de configuration pour la base de données. Enjoy !

## Connexion utilisateur

Pour créer un utilisateur, aller sur la route /user en POST avec en paramètre:
- username
- password
- password_confirmation
- email

Puis pour se log, aller sur le route /login_check en POST avec en paramètre:
- _username
- _password

L'api renvoit un token de connexion, nécessaire pour accéder à toutes les URL de l'api.
Il faut donc envoyer dans le header:
Authorization => Bearer montokenutilisateur


Modèle
======

## User
* id
* email
* password
* pseudonyme
* roles
* created_at

## Experience
* user_id
* exp

## Page
* id
* created_at
* updated_at

## Revision
* page_id
* status: online|pending_validation|canceled|draft
* title
* content
* updated_by
* updated_at
* created_at

## Rating
* id
* revision_id
* rating
* user_id
* created_at
* updated_at

## Categorie

Routing API
===========
/api/v1/
## User
  * /user
    * POST   /
    * GET    /{id}
    * PUT    /{id}
    * DELETE /{id}
    * POST   /login
    * GET    /logout

## Page
  * /page
    * POST   /
    * GET    /{slug}
    * PUT    /{slug}
    * DELETE /{slug}
    * GET    /last?limit=10&offset=0
    * GET    /best_rated?limit=10&offset=0
    * GET    /search?q=query&limit=10&offset=0

## PageRevision
  * /page/{page_slug}/revision
    * POST   /
    * GET    /
    * PUT    /{id}
    * DELETE /{id}
    * GET    /

## Rating
  * /page/{page_slug}/revision/{revision_id}/rate
    * POST   /
    * GET    /{id}
    * PUT    /{id}
    * DELETE /{id}

