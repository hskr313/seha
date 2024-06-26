<?php
class ServiceRepository extends BaseRepository {
    public function __construct() {
        parent::__construct('services', ServiceEntity::class);
    }

    public function findByUserId($userId) {
        return $this->findByCriteria(['user_id' => $userId]);
    }

    public function findByCategoryId($categoryId) {
        return $this->findByCriteria(['category_id' => $categoryId]);
    }

    public function findAllGroupedByCategory() {
        $query = "SELECT s.*, c.category_name as category_name FROM {$this->table} s JOIN categories c ON s.category_id = c.id WHERE s.is_published = true ORDER BY category_id";
        $result = $this->db->query($query);
        if (!$result) {
            die("Query failed: " . $this->db->error);
        }

        $rows = $result->fetch_all(MYSQLI_ASSOC);
        $grouped = [];
        foreach ($rows as $row) {
            $categoryId = $row['category_id'];
            if (!isset($grouped[$categoryId])) {
                $grouped[$categoryId] = [
                    'category_name' => $row['category_name'],
                    'services' => []
                ];
            }
            $grouped[$categoryId]['services'][] = $this->mapToEntity($row);
        }
        return $grouped;
    }

    public function findAllCategories() {
        $query = "SELECT * FROM categories";
        $result = $this->db->query($query);
        if (!$result) {
            die("Query failed: " . $this->db->error);
        }
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function findById(int $id): ?ServiceEntity {
        return parent::findById($id);
    }

    public function searchServices($query) {
        $query = "%{$query}%";
        $stmt = $this->db->prepare("
        SELECT s.*, u.username 
        FROM services s
        JOIN users u ON s.user_id = u.id
        WHERE s.name LIKE ? OR s.description LIKE ? OR u.username LIKE ?");
        if (!$stmt) {
            die("Prepare failed: " . $this->db->error);
        }
        $stmt->bind_param('sss', $query, $query, $query);
        $stmt->execute();
        $result = $stmt->get_result();
        if (!$result) {
            die("Query failed: " . $this->db->error);
        }
        $rows = $result->fetch_all(MYSQLI_ASSOC);
        return array_map([$this, 'mapToEntity'], $rows);
    }


    public function findAllGroupedByCategoryWithUsernames() {
        $query = "
            SELECT s.*, c.category_name, u.username 
            FROM services s
            JOIN categories c ON s.category_id = c.id
            JOIN users u ON s.user_id = u.id
            WHERE s.is_published = true
            ORDER BY s.category_id";
        $result = $this->db->query($query);
        if (!$result) {
            die("Query failed: " . $this->db->error);
        }

        $rows = $result->fetch_all(MYSQLI_ASSOC);
        $grouped = [];
        foreach ($rows as $row) {
            $categoryId = $row['category_id'];
            if (!isset($grouped[$categoryId])) {
                $grouped[$categoryId] = [
                    'category_name' => $row['category_name'],
                    'services' => []
                ];
            }
            $service = $this->mapToEntity($row);
            $service->username = $row['username'];
            $grouped[$categoryId]['services'][] = $service;
        }
        return $grouped;
    }
}
