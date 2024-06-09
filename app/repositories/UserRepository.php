<?php
class UserRepository extends BaseRepository {
    public function __construct() {
        parent::__construct('Users', UserEntity::class);
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

    public function findById(int $id): ?UserEntity {
        return parent::findById($id);
    }
}

