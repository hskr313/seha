<?php
ini_set('display_errors', 1); // Active l'affichage des erreurs
ini_set('display_startup_errors', 1); // Active l'affichage des erreurs au démarrage
error_reporting(E_ALL); // Affiche toutes les erreurs et avertissements

require_once '../config/config.php'; // Inclut le fichier de configuration contenant les constantes de connexion à la base de données

// Fonction pour charger automatiquement toutes les entités
function loadEntities($dir) {
    $entities = []; // Initialise un tableau pour stocker les entités
    foreach (glob("$dir/*.php") as $file) { // Parcourt tous les fichiers PHP dans le répertoire spécifié
        require_once $file; // Inclut chaque fichier PHP trouvé
        $entities[] = basename($file, '.php'); // Ajoute le nom de la classe de l'entité au tableau
    }
    return $entities; // Retourne le tableau d'entités
}

// Charger automatiquement toutes les entités dans le dossier /app/entities
$entityDir = '../app/entities'; // Définit le chemin vers le dossier contenant les entités
$entities = loadEntities($entityDir); // Charge toutes les entités depuis le dossier spécifié

// Connexion au serveur MySQL
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS); // Crée une nouvelle connexion MySQL en utilisant les constantes définies dans le fichier de configuration

// Vérifier la connexion
if ($conn->connect_error) { // Vérifie s'il y a une erreur de connexion
    die("La connexion a échoué: " . $conn->connect_error); // Arrête l'exécution du script et affiche un message d'erreur en cas de problème de connexion
}

// Créer la base de données
$dbName = 'ServiceExchangeHadiAdam'; // Définit le nom de la base de données
$conn->query("DROP DATABASE IF EXISTS $dbName"); // Supprime la base de données si elle existe déjà
$conn->query("CREATE DATABASE $dbName"); // Crée une nouvelle base de données
$conn->select_db($dbName); // Sélectionne la base de données nouvellement créée

// Fonction pour obtenir le type SQL correspondant à un type PHP
function getSqlType($phpType): string {
    switch ($phpType) { // Détermine le type SQL à partir du type PHP
        case 'int':
            return 'INT'; // Retourne INT pour les types PHP 'int'
        case 'string':
            return 'VARCHAR(255)'; // Retourne VARCHAR(255) pour les types PHP 'string'
        case 'bool':
            return 'BOOLEAN'; // Retourne BOOLEAN pour les types PHP 'bool'
        case 'float':
            return 'FLOAT'; // Retourne FLOAT pour les types PHP 'float'
        default:
            return 'TEXT'; // Retourne TEXT pour tous les autres types PHP
    }
}

// Fonction pour convertir un nom de type camelCase en snake_case
function camelCaseToSnakeCase($input): string {
    $pattern = '/(?<=[a-z])(?=[A-Z])|(?<=[A-Z])(?=[A-Z][a-z])/'; // Définie un motif pour trouver les transitions camelCase
    return strtolower(preg_replace($pattern, '_', $input)); // Remplace les transitions camelCase par des underscores et convertit en minuscule
}

// Fonction pour générer le nom de la table à partir du nom de la classe
function generateTableName($shortName): string {
    $tableName = camelCaseToSnakeCase($shortName); // Convertit le nom de la classe en snake_case

    // Éviter les répétitions de 's' à la fin des noms de tables
    if (substr($tableName, -1) === 's') {
        $tableName .= 'es'; // Ajoute 'es' si le nom se termine par 's'
    } elseif (substr($tableName, -1) === 'y') {
        $tableName = substr_replace($tableName, 'ies', -1); // Remplace 'y' par 'ies' si le nom se termine par 'y'
    } else {
        $tableName .= 's'; // Ajoute 's' dans les autres cas
    }

    return $tableName; // Retourne le nom de la table généré
}

/**
 * @throws ReflectionException
 */
// Fonction pour générer la requête SQL de création de table à partir d'une classe d'entité
function generateCreateTableSQL($className): string {
    $reflect = new ReflectionClass($className); // Crée une instance de ReflectionClass pour inspecter la classe
    $shortName = preg_replace('/Entity$/', '', $reflect->getShortName()); // Enlève le suffixe 'Entity' du nom de la classe pour obtenir un nom court
    $tableName = generateTableName($shortName); // Génère le nom de la table à partir du nom court

    $properties = $reflect->getProperties(); // Récupère toutes les propriétés de la classe
    $columns = ["id INT AUTO_INCREMENT PRIMARY KEY"]; // Initialise un tableau avec la colonne 'id' comme clé primaire auto-incrémentée

    foreach ($properties as $property) { // Parcourt toutes les propriétés de la classe
        if ($property->getName() == 'id') {
            continue; // Ignore la propriété 'id' car elle est déjà définie
        }
        $type = $property->getType() ? $property->getType()->getName() : 'string'; // Récupère le type de la propriété ou 'string' par défaut
        $sqlType = getSqlType($type); // Obtient le type SQL correspondant au type PHP
        $columnName = camelCaseToSnakeCase($property->getName()); // Convertit le nom de la propriété en snake_case
        $columns[] = "$columnName $sqlType"; // Ajoute la définition de la colonne au tableau
    }

    $columnsSql = implode(", ", $columns); // Concatène toutes les définitions de colonnes en une seule chaîne
    return "CREATE TABLE IF NOT EXISTS $tableName ($columnsSql);"; // Retourne la requête SQL de création de table
}

// Générer les requêtes SQL pour chaque entité
foreach ($entities as $entity) { // Parcourt toutes les entités
    $sql = generateCreateTableSQL($entity); // Génère la requête SQL de création de table pour l'entité
    if ($conn->query($sql) === TRUE) { // Exécute la requête SQL et vérifie si elle a réussi
        echo "Table for $entity created successfully.<br>"; // Affiche un message de succès si la table a été créée
    } else {
        echo "Error creating table for $entity: " . $conn->error . "<br>"; // Affiche un message d'erreur en cas de problème
    }
}

$conn->close(); // Ferme la connexion à la base de données
?>
