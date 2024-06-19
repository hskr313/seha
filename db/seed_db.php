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
    // Attendre la fin de toutes les requêtes
    do {
        if ($conn->more_results()) {
            $conn->next_result();
        } else {
            break;
        }
    } while (true);

    echo "Base de données remplie avec succès.<br>";
} else {
    echo "Erreur lors du remplissage de la base de données: " . $conn->error . "<br>";
}

// Hashing des mots de passe
$passwords = [
    'admin' => 'admin_password_hash',
    'hadi' => 'password_hash',
    'adam' => 'password_hash',
    'chris' => 'password_hash',
    'mehdi' => 'password_hash'
];

foreach ($passwords as $username => $password) {
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
    $conn->query("UPDATE users SET password = '$hashedPassword' WHERE username = '$username'");
}

// Vérifier que les mots de passe ont été mis à jour
foreach ($passwords as $username => $password) {
    $result = $conn->query("SELECT password FROM users WHERE username = '$username'");
    $row = $result->fetch_assoc();
    echo "Username: $username, Hashed Password: " . $row['password'] . "<br>";
}

$conn->close();
