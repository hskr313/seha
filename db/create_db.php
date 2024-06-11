<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../config/config.php';

// Fonction pour charger automatiquement toutes les entités
function loadEntities($dir) {
    $entities = [];
    foreach (glob("$dir/*.php") as $file) {
        require_once $file;
        $entities[] = basename($file, '.php');
    }
    return $entities;
}

// Charger automatiquement toutes les entités dans le dossier /app/entities
$entityDir = '../app/entities';
$entities = loadEntities($entityDir);

// Connexion au serveur MySQL
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS);

// Vérifier la connexion
if ($conn->connect_error) {
    die("La connexion a échoué: " . $conn->connect_error);
}

// Créer la base de données
$dbName = 'ServiceExchangeHadiAdam';
$conn->query("DROP DATABASE IF EXISTS $dbName");
$conn->query("CREATE DATABASE $dbName");
$conn->select_db($dbName);

function getSqlType($phpType): string
{
    switch ($phpType) {
        case 'int':
            return 'INT';
        case 'string':
            return 'VARCHAR(255)';
        case 'bool':
            return 'BOOLEAN';
        case 'float':
            return 'FLOAT';
        default:
            return 'TEXT';
    }
}

function camelCaseToSnakeCase($input): string
{
    $pattern = '/(?<=[a-z])(?=[A-Z])|(?<=[A-Z])(?=[A-Z][a-z])/';
    return strtolower(preg_replace($pattern, '_', $input));
}

function generateTableName($shortName): string
{
    $tableName = camelCaseToSnakeCase($shortName);

    // Éviter les répétitions de 's' à la fin des noms de tables
    if (substr($tableName, -1) === 's') {
        $tableName .= 'es';
    } elseif (substr($tableName, -1) === 'y') {
        $tableName = substr_replace($tableName, 'ies', -1);
    } else {
        $tableName .= 's';
    }

    return $tableName;
}

/**
 * @throws ReflectionException
 */
function generateCreateTableSQL($className): string
{
    $reflect = new ReflectionClass($className);
    $shortName = preg_replace('/Entity$/', '', $reflect->getShortName());
    $tableName = generateTableName($shortName);

    $properties = $reflect->getProperties();
    $columns = ["id INT AUTO_INCREMENT PRIMARY KEY"];

    foreach ($properties as $property) {
        if ($property->getName() == 'id') {
            continue;
        }
        $type = $property->getType() ? $property->getType()->getName() : 'string';
        $sqlType = getSqlType($type);
        $columnName = camelCaseToSnakeCase($property->getName());
        $columns[] = "$columnName $sqlType";
    }

    $columnsSql = implode(", ", $columns);
    return "CREATE TABLE IF NOT EXISTS $tableName ($columnsSql);";
}

// Générer les requêtes SQL pour chaque entité
foreach ($entities as $entity) {
    $sql = generateCreateTableSQL($entity);
    if ($conn->query($sql) === TRUE) {
        echo "Table for $entity created successfully.<br>";
    } else {
        echo "Error creating table for $entity: " . $conn->error . "<br>";
    }
}

$conn->close();
