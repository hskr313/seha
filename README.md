
# Projet Développement Web - Université

Ce projet a été réalisé dans le cadre du cours "Projet Développement Web" à l'université, en collaboration avec @Veynah.

# Installation du Projet PHP en Local

Ce guide vous expliquera comment installer et configurer votre projet PHP en utilisant MAMP sur macOS et WAMP sur Windows.

## Prérequis

- MAMP pour macOS [Télécharger MAMP](https://www.mamp.info/en/downloads/)
- WAMP pour Windows [Télécharger WAMP](https://www.wampserver.com/en/)

## Installation

### Étape 1: Copier les fichiers du projet

Copiez les fichiers de votre projet dans le répertoire approprié :
- Pour MAMP : `/Applications/MAMP/htdocs/seha`
- Pour WAMP : `C:/wamp64/www/seha`

### Étape 2: Scripts SQL

Assurez-vous que le fichier SQL pour remplir la base de données est dans le dossier du projet "/db" :
- `service_exchange_seed.sql`

### Étape 3: Exécuter les scripts PHP

Les scripts PHP `create_db.php` et `seed_db.php` sont fournis pour automatiser la création et le peuplement de la base de données.

#### Créer la base de données

Ouvrez votre navigateur et accédez à l'URL suivante pour créer la base de données :

Pour MAMP (macOS) :
```
http://localhost:8888/seha/db/create_db.php
```

Pour WAMP (Windows) :
```
http://localhost/seha/db/create_db.php
```

#### Remplir la base de données

Ouvrez votre navigateur et accédez à l'URL suivante pour remplir la base de données :

Pour MAMP (macOS) :
```
http://localhost:8888/seha/db/seed_db.php
```

Pour WAMP (Windows) :
```
http://localhost/seha/db/seed_db.php
```

### Étape 4: Accéder à votre projet

Assurez-vous que MAMP ou WAMP est en cours d'exécution.

#### Pour MAMP :
Ouvrez votre navigateur et accédez à :
```
http://localhost:8888/seha
```

#### Pour WAMP :
Ouvrez votre navigateur et accédez à :
```
http://localhost/seha
```

### Configuration du fichier de connexion à la base de données

Assurez-vous que votre fichier de connexion à la base de données est correctement configuré.

**db_connect.php** :

```php
<?php
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "ServiceExchangeHadiAdam";

// Créer la connexion
$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die("La connexion a échoué : " . $conn->connect_error);
}
```

En suivant ces étapes, vous pourrez configurer et exécuter seha en local avec MAMP sur macOS et WAMP sur Windows.
