<?php

use Couchbase\User;

class UserRepository extends BaseRepository { // Déclaration de la classe UserRepository qui hérite de BaseRepository

    // Constructeur de la classe, initialise la table et la classe d'entité
    public function __construct() {
        parent::__construct('Users', UserEntity::class); // Appelle le constructeur de BaseRepository avec le nom de la table et la classe d'entité
    }

    // Méthode pour trouver un utilisateur par email
    public function findByEmail(string $email): ?UserEntity {
        $result = $this->findByCriteria(['email' => $email]); // Utilise findByCriteria pour récupérer l'utilisateur par email
        return $result ? $result[0] : null; // Retourne le premier utilisateur trouvé ou null si aucun utilisateur n'est trouvé
    }

    // Méthode pour obtenir le nombre total d'utilisateurs
    public function getUsersCount(): int {
        $result = $this->db->query("SELECT COUNT(*) AS count FROM {$this->table}"); // Exécute une requête SQL pour compter le nombre d'utilisateurs

        // Vérification des erreurs
        if (!$result) { // Vérifie si la requête a échoué
            die('Erreur SQL : ' . $this->db->error); // Arrête l'exécution du script et affiche un message d'erreur
        }

        $data = $result->fetch_assoc(); // Récupère la ligne de résultat
        return $data['count']; // Retourne le nombre d'utilisateurs
    }

    // Méthode pour trouver des utilisateurs par nom d'utilisateur (pour la messagerie)
    public function findByUsername(string $username): array {
        $query = "SELECT * FROM {$this->table} WHERE username LIKE ?"; // Requête SQL pour sélectionner les utilisateurs dont le nom d'utilisateur correspond au critère de recherche
        $stmt = $this->db->prepare($query); // Prépare la requête SQL
        if (!$stmt) { // Vérifie si la préparation de la requête a échoué
            die("Prepare failed: " . $this->db->error); // Arrête l'exécution du script et affiche un message d'erreur
        }
        $search = "%$username%"; // Ajoute des jokers pour la recherche
        $stmt->bind_param('s', $search); // Lie le paramètre de la requête
        $stmt->execute(); // Exécute la requête
        $result = $stmt->get_result(); // Récupère le résultat de la requête
        if (!$result) { // Vérifie si la requête a échoué
            die("Query failed: " . $this->db->error); // Arrête l'exécution du script et affiche un message d'erreur
        }
        $rows = $result->fetch_all(MYSQLI_ASSOC); // Récupère toutes les lignes sous forme de tableau associatif
        return array_map([$this, 'mapToEntity'], $rows); // Transforme chaque ligne de résultat en objet entité et retourne un tableau d'entités
    }

    // Méthode pour obtenir le crédit temps d'un utilisateur
    public function getTimeCredit(int $userId): int {
        $stmt = $this->db->prepare("SELECT time_credit FROM {$this->table} WHERE id = ?"); // Prépare une requête SQL pour sélectionner le crédit temps de l'utilisateur
        if (!$stmt) { // Vérifie si la préparation de la requête a échoué
            die("Prepare failed: " . $this->db->error); // Arrête l'exécution du script et affiche un message d'erreur
        }
        $stmt->bind_param('i', $userId); // Lie le paramètre de la requête
        $stmt->execute(); // Exécute la requête
        $result = $stmt->get_result(); // Récupère le résultat de la requête
        if (!$result) { // Vérifie si la requête a échoué
            die("Query failed: " . $this->db->error); // Arrête l'exécution du script et affiche un message d'erreur
        }
        $data = $result->fetch_assoc(); // Récupère la ligne de résultat
        return $data ? (int)$data['time_credit'] : 0; // Retourne le crédit temps de l'utilisateur ou 0 si aucun résultat n'est trouvé
    }

    // Méthode pour mettre à jour le crédit temps d'un utilisateur
    public function updateTimeCredit(int $userId, int $newCredit): bool {
        $stmt = $this->db->prepare("UPDATE {$this->table} SET time_credit = ? WHERE id = ?"); // Prépare une requête SQL pour mettre à jour le crédit temps de l'utilisateur
        if (!$stmt) { // Vérifie si la préparation de la requête a échoué
            die("Prepare failed: " . $this->db->error); // Arrête l'exécution du script et affiche un message d'erreur
        }
        $stmt->bind_param('ii', $newCredit, $userId); // Lie les paramètres de la requête
        return $stmt->execute(); // Exécute la requête et retourne true si la mise à jour a réussi, false sinon
    }
}
?>
