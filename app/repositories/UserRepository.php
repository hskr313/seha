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
}

