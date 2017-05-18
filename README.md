Wiki-API
========

** Réalisé par Mavillaz Rémi and Paul Girardin**

Github KizeRemi: https://github.com/KizeRemi  
GitHub PaulGirardin: https://github.com/PaulGirardin

Projet Wiki pour une école primaire. Le projet est décomposé en 2 parties : API et Interface utilisateur. Ce dépot comporte uniquement la partie API réalisé sous Symfony 3.2


##Fonctionnalités

- [x] Inscription Utilisateur
- [ ] Connexion/déconnexion
- [ ] Gestion du profil
- [ ] Gestion des rôles (anon, user, admin)
- [ ] Gestion des pages
- [ ] Recherche

### Optionnel
- [ ] Upload d'images
- [ ] Système de badge
- [ ] Commentaires
- [ ] Catégories
- [ ] Multilingue

* Roles
  * ADMIN : Créer/Modifier/Suppriler
-- USER : Créer/Modifier
-- ANON : Lire
-- Interface de saisie WYSIWYG
- Historique des modifications
- Recherche de page
- Page profil

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
    * GET    /{id}
    * PUT    /{id}
    * DELETE /{id}
    * GET    /

## Rating
  * /page/{page_slug}/revision/{revision_id}/rate
    * POST   /
    * GET    /{id}
    * PUT    /{id}
    * DELETE /{id}

FRONT
=====
### Toutes les pages
*
*
*

### /
*Derniers articles
*Meilleurs notes
*Page {home}
*Champs de recherche

### /search
*résultats de la recherche

### /page/{slug}
* title, content (la dernière revision online)
* ratinf (de la révision en cours)
* date de la révision

### /page/{slug}/history
* utilisateurs ayant contribués
