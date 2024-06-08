<?php
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "ServiceExchangeHadiAdam";

// Connexion au serveur MySQL
$conn = new mysqli($servername, $username, $password);

// Vérifier la connexion
if ($conn->connect_error) {
    die("La connexion a échoué: " . $conn->connect_error);
}

// Lire le contenu du fichier SQL
$sql = file_get_contents('create_db_service_exchange.sql');

if ($sql === false) {
    die("Erreur lors de la lecture du fichier create_db_service_exchange.sql");
}

// Exécuter le script SQL
if ($conn->multi_query($sql)) {
    echo "Base de données et tables créées avec succès.";
} else {
    echo "Erreur lors de la création de la base de données: " . $conn->error;
}

$conn->close();

