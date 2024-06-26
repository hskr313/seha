<?php
class TransactionRepository extends BaseRepository {
    public function __construct() {
        parent::__construct('transactions', TransactionEntity::class);
    }

    public function findByUserId($userId) {
        $query = "SELECT * FROM {$this->table} WHERE provider_id = ? OR receiver_id = ?";
        $stmt = $this->db->prepare($query);
        if (!$stmt) {
            die("Prepare failed: " . $this->db->error);
        }
        $stmt->bind_param('ii', $userId, $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        if (!$result) {
            die("Query failed: " . $this->db->error);
        }
        $rows = $result->fetch_all(MYSQLI_ASSOC);
        return array_map([$this, 'mapToEntity'], $rows);
    }
}
