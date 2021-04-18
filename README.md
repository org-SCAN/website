# website
Site web du projet :)
Nomalement on a une synchro automatique de la branche main sur le serveur.

Adresse IP du serveur : 15.236.237.200

# Connection à l'API

Il est maintenant possible de se connecter à l'API pour envoyer des nouveaux refugees ou pour obtenir la dernière version des fields (et des listes).

## Get fields :

Voici un exemple de requête et la réponse associée :

```
GET /api/fields HTTP/1.1
Host: 15.200.236.237:80
Authorization: Bearer YOUR_API_TOKEN
Accept: application/json
Content-Type: application/json
```

Réponse du serveur :
```
{
    "fields": [
        {
            "title": "Unique ID",
            "label": "unique_id",
            "placeholder": "AAA-000001",
            "UI_type": "EditText",
            "linked_list": "",
            "required": "Auto generated",
            "order": 0
        },
        {
            "title": "Full Name",
            "label": "full_name",
            "placeholder": "Manuel",
            "UI_type": "EditText",
            "linked_list": "",
            "required": "Required",
            "order": 1
        },
        {
            "title": "Gender",
            "label": "gender",
            "placeholder": "F",
            "UI_type": "Spinner",
            "linked_list": "Gender",
            "required": "Strongly advised",
            "order": 2
        }
    ],
    "Gender": [
        {
            "id": "f53f1664-aea4-4844-8e48-9a3c1539de83",
            "short": "F",
            "full": "Female"
        },
        {
            "id": "5a0a8892-11de-4249-b26b-9edf950dd6f0",
            "short": "M",
            "full": "Male"
        },
        {
            "id": "b007c422-aa97-4ce5-8941-d472357e24b9",
            "short": "NB",
            "full": "Non-Binary"
        },
        {
            "id": "6e511f4f-29ee-4770-94a3-f4d42e786388",
            "short": "O",
            "full": "Other"
        }
    ]
}
```

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

## Add refugees :

Il faut maintenant envoyer une requête post avec le json à l'intérieur :

Exemple :

```
POST /api/manage_refugees HTTP/1.1
Host: 15.236.237.200:80
Accept: application/json
Content-Type: application/json
Authorization: Bearer YOUR_API_TOKEN
Content-Length: 256

[
    {
    "unique_id" : "ABC-000001",
    "full_name" : "full name",
    "country" : "NIGER",
    "date" : "2021-04-12"
    },
    {
    "unique_id" : "ABC-000002",
    "full_name" : "full name",
    "country" : "NIGER",
    "date" : "2021-04-12"
    }
]
```

Le serveur répondra une erreur si la donnée envoyée n'est pas valide, je détaillerai plus tard comment et ce que vous pouvez en faire, sinon un message de succès est envoyé.

## Obtenir son API Token :

Il faut se créer un compte sur le site puis se rendre dans profil. Ici il y a une section permettant de consulter son api token.
# Lancement de Laravel

Après avoir exécuté `./start_laravel`, il faut se connecter à ce conteneur et exécuter les commandes suivantes :

```
composer update
php artisan migrate --seed
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

