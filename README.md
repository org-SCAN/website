# website
Site web du projet :)
Nomalement on a une synchro automatique de la branche main sur le serveur.

Adresse IP du serveur : 15.236.237.200

## Docker ou comment bosser de chez moi :

À partir de `.env.example` créer le fichier `.env` avec les bons logs (à la racine du dossier) :
`cp .env.example .env`

Pour lancer le projet, on peut plus ou moins utiliser docker. Pour ce faire 

via docker : 

    docker-compose build && docker-compose up -d

Ou mieux : (bien avoir les droits d'éxecution sur start-laravel)

    ./start-laravel

On peut alors consulter `127.0.0.1:8080`

Pour stopper le tout :

    docker-compose down

### Associer la database

Pour pouvoir bosser avec la database, il est nécessaire de motifier le fichier `src/.env`.

Voici un exemple de ce qu'il doit contenir, où les valeurs `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` doivent correspondre aux informations saisies dans le `.env` de racine (utilisé par docker pour générer le service de mongodb).

```
DB_CONNECTION=mysql
DB_HOST=sql
DB_PORT=3306
DB_DATABASE=DB_DATABASE_NAME
DB_USERNAME=user_example
DB_PASSWORD=my_passwd
```

# Lancement de Laravel

Après avoir exécuté `./start_laravel`, il faut se connecter à ce conteneur et exécuter les commandes suivantes :

```
composer update
php artisan migrate
```

Cela permet de télécharger les dépendances et de mettre à jour la base de donnée avec les fichiers nécessaires.



## Séance du 02/04/21

- Mise en place des premiers scripts de migration de la database
- Mise en place du Docker de manière stable
- Rédaction de premier scripts (toujours dans le domaine de la DB)



## Séance du 03/03/21

Brainstorm sur les choses à implémenter, discussion sur le back et le front rapidement, choix de Laravel et on monte laravel sur le serveur.

![Brainstorm](/img/website-brainstorm.png)

## Séance du 15/03/21

Travail sur Laravel, on a regardé comment on pouvait faire les migrations DB (ça tourne sur le serveur).
Réflexion sur le contenu des pages du site. [Ici](https://www.figma.com/file/SfFnr65viq4wDuNNmEdbqp/Untitled?node-id=0%3A1).
Réflexion sur les liens entre les pages.

