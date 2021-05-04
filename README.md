# website
Site web du projet :)
Nomalement on a une synchro automatique de la branche main sur le serveur.

Adresse IP du serveur : 15.236.237.200

# Connection à l'API

Il est maintenant possible de se connecter à l'API pour envoyer des nouveaux refugees ou pour obtenir la dernière version des fields (et des listes).
Il est OBLIGATOIRE de transmettre un Application-Id dans les headers sinon la requête sera refusée

## Get fields :

Voici un exemple de requête et la réponse associée :

```
GET /api/fields HTTP/1.1
Host: 15.236.237.200:80
Authorization: Bearer YOUR_API_TOKEN
Accept: application/json
Content-Type: application/json
Application-id: YOUR_APPLICATION_ID
```

Exemple de réponse du serveur :
```
{
    "fields": {
            "unique_id": {
                "placeholder": "AAA-000001",
                "database_type": "string",
                "android_type": "EditText",
                "linked_list": "",
                "required": 0,
                "displayed_value": {
                    "eng": "Unique ID",
                    "fra": "ID Unique",
                    "esp": "Unico ID"
                }
            },
            "gender": {
                "placeholder": "F",
                "database_type": "string",
                "android_type": "Spinner",
                "linked_list": "Gender",
                "required": 1,
                "displayed_value": {
                    "eng": "Sex",
                    "fra": "Sexe",
                    "esp": "Sexo"
                }
            },
            "full_name": {
                "placeholder": "John Doe",
                "database_type": "string",
                "android_type": "EditText",
                "linked_list": "",
                "required": 1,
                "displayed_value": {
                    "eng": "Full Name",
                    "fra": "Nom complet",
                    "esp": "Full Name"
                }
            },
            ...
    },
    relations": {
            "BR": {
                "color": "000000",
                "importance": 1,
                "displayed_value": {
                    "eng": "Biological relationship",
                    "fra": "Relation biologique",
                    "esp": "Biological relationship"
                }
            },
            "NBR": {
                "color": "000000",
                "importance": 1,
                "displayed_value": {
                    "eng": "Non-biological relationship",
                    "fra": "Relation non biologique",
                    "esp": "Non-biological relationship"
                }
            },
            "TW": {
                "color": "000000",
                "importance": 1,
                "displayed_value": {
                    "eng": "Travelled with",
                    "fra": "A voyagé avec",
                    "esp": "Travelled with"
                }
            },
            "SA": {
                "color": "000000",
                "importance": 1,
                "displayed_value": {
                    "eng": "Saw",
                    "fra": "A vu",
                    "esp": "Saw"
                }
            },
            "SE": {
                "color": "000000",
                "importance": 1,
                "displayed_value": {
                    "eng": "Service",
                    "fra": "Service",
                    "esp": "Service"
                }
            }
    }
 }
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
Application-id: YOUR_APPLICATION_ID
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

## API post Link

L'API post permet aussi d'envoyer la liste des relations. Pour l'utiliser il faut :

- Que les deux refugees (personnes) soient déjà présente dans la database. Autrement dit, il faut d'abord effectuer une requete POST add refugee
- Préciser le `full_name` et le `unique_id de chaque personne
- Préciser une relation appartenant à la liste des relations prédéfinies.

```
POST /api/links HTTP/1.1
Host: 15.236.237.200:80
Accept: application/json
Content-Type: application/json
Authorization: Bearer YOUR_API_TOKEN
Application-id: YOUR_APPLICATION_ID
Content-Length: 249

[
    {
    "from_unique_id" : "ABC-000008",
    "from_full_name" : "INSERT FROM API 1",
    "to_unique_id" : "ABC-000009",
    "to_full_name" : "INSERT FROM API 2",
    "relation" : "3ebf36e0-82b1-423b-98b5-ee5ec52223b5",
    "detail" : "at the port"
    }
]
```

### Erreurs possibles : 

Si les données fournies ne sont pas bonnes, le serveur renvoie une erreur de type `422` :

```
{
    "message": "The given data was invalid.",
    "errors": {
        "0.refugee1_full_name": [
            "The 0.refugee1_full_name field is required."
        ],
        "1.refugee1_full_name": [
            "The 1.refugee1_full_name field is required."
        ],
        "2.refugee1_full_name": [
            "The 2.refugee1_full_name field is required."
        ],
        "3.refugee1_full_name": [
            "The 3.refugee1_full_name field is required."
        ],
        "0.refugee1_unique_id": [
            "The 0.refugee1_unique_id field is required."
        ],
        "1.refugee1_unique_id": [
            "The 1.refugee1_unique_id field is required."
        ],
        "2.refugee1_unique_id": [
            "The 2.refugee1_unique_id field is required."
        ],
        "3.refugee1_unique_id": [
            "The 3.refugee1_unique_id field is required."
        ],
        "0.refugee2_full_name": [
            "The 0.refugee2_full_name field is required."
        ],
        "1.refugee2_full_name": [
            "The 1.refugee2_full_name field is required."
        ],
        "2.refugee2_full_name": [
            "The 2.refugee2_full_name field is required."
        ],
        "3.refugee2_full_name": [
            "The 3.refugee2_full_name field is required."
        ],
        "0.refugee2_unique_id": [
            "The 0.refugee2_unique_id field is required."
        ],
        "1.refugee2_unique_id": [
            "The 1.refugee2_unique_id field is required."
        ],
        "2.refugee2_unique_id": [
            "The 2.refugee2_unique_id field is required."
        ],
        "3.refugee2_unique_id": [
            "The 3.refugee2_unique_id field is required."
        ],
        "0.relation": [
            "The 0.relation field is required."
        ],
        "1.relation": [
            "The 1.relation field is required."
        ],
        "2.relation": [
            "The 2.relation field is required."
        ],
        "3.relation": [
            "The 3.relation field is required."
        ]
    }
}
```
On retrouve le détail de tous les champs mal renseignés

## Obtenir son API Token :

Il faut se créer un compte sur le site puis se rendre dans profil. Ici il y a une section permettant de consulter son api token.

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

