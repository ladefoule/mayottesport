## Installation

L'installation du projet nécessite la présence du gestionnaire de paquets Composer.

Après installation ce ce dernier, vous pourrez exécuter la ligne suivante :

**composer install**

Après installation des dépendances, vous devez renommer le fichier .env.example en .env

Ensuite configurez-le en modifiant les constantes suivantes : DB_DATABASE, DB_USERNAME et DB_PASSWORD

Vous pouvez maintenant exécuter la commande suivante pour lancer les migrations :

**php artisan migrate**

Pour insérer des données de peuplement de la base de données (sports/championnats/equipes/matches/etc...):

**php artisan db:seed**

Il ne vous reste plus qu'à lancer le serveur installé par composer :

**php artisan serve**

## Environnement

L'application est codé avec le framework Laravel 6.
Les librairies utilisées : JQuery, Datatables.

## License

Le framework Laravel est un logiciel libre sous licence [MIT license](https://opensource.org/licenses/MIT).


## Tâches à faire côté BACK

    1. Mettre en place toutes les relations entre les Models avec Eloquent
    2. Commenter toutes les fonctions des Models
    3. Définir les actions possibles que peut effectuer l’administrateur
        - Modifier les scores d’un match
        - Ajouter un match
        - …
    4. Noter les actions interdites :
        - Modifier la catégorie d’un match (champ => coupe vice versa)
        - Supprimer un championnat qui contient des saisons
        - …
    5. Algorithme de génération de classement
    6. Algorithme de calcul de la journée à afficher
        - On affiche la dernière
        - Ou la suivante en fonction de la distance entre le jour J et les 2
    7. Remettre le cache sur la listeDesAttributsAAfficher
    8. Trouver une solution pour la génération de la doc PHP (impossible d'installer phive.phar)
    9. Tester la mise en cache en RAM avec Redis au lieu de passer par le disque

## Tâches à faire côté FRONT

    1. Finaliser le modèle d’affichage pour un calendrier
    2. Affichage du classement à coté du calendrier
    3. Mettre en place le modèle d’affichage d’une page d'équipe
    4. Lister toutes les pages dont on aura besoin pour le PROJET
        - Accueil
        - Page d’accueil de sport
        - Football, Handball, ...
        - Page de classement d’une saison
        - R1, R2, …
        - Page de calendrier complet
        - Page de modification d’un résultat ou de la date du match
    5. Décrire en détail le mécanisme/déroulé que doit suivre l’user pour modifier un résultat de match ou une date de match.
    6. Mettre en place le fond d'écran (le fanion) de chaque club en arrière plan dans les pages de matches.
