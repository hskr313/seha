<?php
class MessageRepository extends BaseRepository {
    public function __construct() {
        parent::__construct('messages', MessageEntity::class);
    }

    public function findBySenderId(int $sender_id): array {
        return $this->findByCriteria(['sender_id' => $sender_id]);
    }

    public function findByReceiverId(int $receiver_id): array {
        return $this->findByCriteria(['receiver_id' => $receiver_id]);
    }

    public function findByConversation(int $user1_id, int $user2_id): array {
        $query = "SELECT * FROM {$this->table} WHERE (sender_id = ? AND receiver_id = ?) OR (sender_id = ? AND receiver_id = ?) ORDER BY sent_at";
        $stmt = $this->db->prepare($query);
        if (!$stmt) {
            die("Prepare failed: " . $this->db->error);
        }
        $stmt->bind_param('iiii', $user1_id, $user2_id, $user2_id, $user1_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if (!$result) {
            die("Query failed: " . $this->db->error);
        }
        $rows = $result->fetch_all(MYSQLI_ASSOC);
        return array_map([$this, 'mapToEntity'], $rows);
    }

    public function findAllConversations(int $user_id): array {
        $query = "
            SELECT m1.*
            FROM {$this->table} m1
            INNER JOIN (
                SELECT 
                    LEAST(sender_id, receiver_id) AS user1_id, 
                    GREATEST(sender_id, receiver_id) AS user2_id, 
                    MAX(sent_at) AS max_sent_at
                FROM {$this->table}
                WHERE sender_id = ? OR receiver_id = ?
                GROUP BY user1_id, user2_id
            ) m2 ON (m1.sender_id = m2.user1_id AND m1.receiver_id = m2.user2_id AND m1.sent_at = m2.max_sent_at)
                OR (m1.sender_id = m2.user2_id AND m1.receiver_id = m2.user1_id AND m1.sent_at = m2.max_sent_at)
            ORDER BY m1.sent_at DESC";

        $stmt = $this->db->prepare($query);
        if (!$stmt) {
            die("Prepare failed: " . $this->db->error);
        }
        $stmt->bind_param('ii', $user_id, $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if (!$result) {
            die("Query failed: " . $this->db->error);
        }
        $rows = $result->fetch_all(MYSQLI_ASSOC);
        return array_map([$this, 'mapToEntity'], $rows);
    }
}
