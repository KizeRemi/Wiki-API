Wiki-API
========

**Réalisé par Mavillaz Rémi and Paul Girardin**

Github KizeRemi: https://github.com/KizeRemi  
GitHub PaulGirardin: https://github.com/PaulGirardin

Projet Wiki pour une école primaire. Le projet est décomposé en 2 parties : API et Interface utilisateur. Ce dépot comporte uniquement la partie API réalisée sous Symfony 3.2

## Fonctionnalités

- [x] Inscription Utilisateur
- [x] Connexion/déconnexion
- [x] Gestion du profil
- [x] Gestion des rôles (anon, user, admin)
- [x] Gestion des pages
- [x] Recherche
- [x] Rating
- [x] Bannière 

### Optionnel
- [x] Upload d'images
- [ ] Système d'experience
- [x] Commentaires
- [x] Catégories
- [x] Gallerie d'images

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

## Documentation

Toutes les routes sont disponibles ici :
http://wiki.remi-mavillaz.fr/api/doc

**L'API EST EN LIGNE**

Système d'experience (A définir) en dernier et bonus.



