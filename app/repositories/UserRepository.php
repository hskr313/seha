<?php

use Couchbase\User;

class UserRepository extends BaseRepository {
    public function __construct() {
        parent::__construct('Users', UserEntity::class);
    }

    public function findByEmail(string $email): ?UserEntity {
        $result = $this->findByCriteria(['email' => $email]);
        return $result ? $result[0] : null;
    }

    public function getUsersCount(): int {
        $result = $this->db->query("SELECT COUNT(*) AS count FROM {$this->table}");

        // Vérification des erreurs
        if (!$result) {
            die('Erreur SQL : ' . $this->db->error);
        }

        $data = $result->fetch_assoc();
        return $data['count'];
    }

    // Je vais utiliser ça pour la messagerie.
    public function findByUsername(string $username): array {
        $query = "SELECT * FROM {$this->table} WHERE username LIKE ?";
        $stmt = $this->db->prepare($query);
        if (!$stmt) {
            die("Prepare failed: " . $this->db->error);
        }
        $search = "%$username%";
        $stmt->bind_param('s', $search);
        $stmt->execute();
        $result = $stmt->get_result();
        if (!$result) {
            die("Query failed: " . $this->db->error);
        }
        $rows = $result->fetch_all(MYSQLI_ASSOC);
        return array_map([$this, 'mapToEntity'], $rows);
    }
}

