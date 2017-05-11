Wiki-API
========
Brief: Réaliser un wiki à l'aide des technologies Angular (Front) et Symfony (Back)

Fonctionnalités
===============

* Se connecter
* S'inscrire
* Se déconnecter
* Roles
  * ADMIN : Créer/Modifier/Suppriler
-- USER : Créer/Modifier
-- ANON : Lire
- Créer une page
- Modifier une page
- Supprimer une page
-- Interface de saisie WYSIWYG
- Historique des modifications
- Recherche de page
- Page profil

(Optionel)
- Insertion d'image
- Badge
- Chat
- Catégories
- Multilingue

Modèle
======

## User
* id
* email
* password
* pseudonyme
* roles
* created_at

## UserStatistic
* user_id
* score

## Page
* id
* created_at
* updated_at

## PageRevision
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
