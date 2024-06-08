<?php
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "ServiceExchangeHadiAdam";

// Connexion à la base de données MySQL
$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die("La connexion a échoué: " . $conn->connect_error);
}

// Lire le contenu du fichier SQL
$sql = file_get_contents('service_exchange_seed.sql');

if ($sql === false) {
    die("Erreur lors de la lecture du fichier service_exchange_seed.sql");
}

// Exécuter le script SQL
if ($conn->multi_query($sql)) {
    echo "Base de données remplie avec succès.";
} else {
    echo "Erreur lors du remplissage de la base de données: " . $conn->error;
}

$conn->close();

