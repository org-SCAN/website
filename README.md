# website
Site web du projet :)
Nomalement on a une synchro automatique de la branche main sur le serveur.

Adresse IP du serveur : 15.236.237.200

## Docker

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
  
## Séance du 03/03/21

Brainstorm sur les choses à implémenter, discussion sur le back et le front rapidement, choix de Laravel et on monte laravel sur le serveur.

![Brainstorm](/img/website-brainstorm.png)

## Séance du 15/03/21

Travail sur Laravel, on a regardé comment on pouvait faire les migrations DB (ça tourne sur le serveur).
Réflexion sur le contenu des pages du site. [Ici](https://www.figma.com/file/SfFnr65viq4wDuNNmEdbqp/Untitled?node-id=0%3A1).
Réflexion sur les liens entre les pages.

