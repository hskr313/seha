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
        $query = "SELECT s.*, c.category_name as category_name FROM {$this->table} s JOIN categories c ON s.category_id = c.id ORDER BY category_id";
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
}
