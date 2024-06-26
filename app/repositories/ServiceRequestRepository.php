<?php
class ServiceRequestRepository extends BaseRepository {
    public function __construct() {
        parent::__construct('service_requests', ServiceRequestEntity::class);
    }

    public function findByProviderId($providerId) {
        return $this->findByCriteria(['provider_id' => $providerId]);
    }

    public function updateStatus($requestId, $statusId) {
        $stmt = $this->db->prepare("UPDATE service_requests SET request_status_id = ? WHERE id = ?");
        if (!$stmt) {
            die("Prepare failed: " . $this->db->error);
        }
        $stmt->bind_param('ii', $statusId, $requestId);
        return $stmt->execute();
    }

    public function findRequestsWithDetails($providerId) {
        $query = "
            SELECT sr.*, s.name as service_name, u.username as requester_name
            FROM service_requests sr
            JOIN services s ON sr.service_id = s.id
            JOIN users u ON sr.requester_id = u.id
            WHERE sr.provider_id = ? AND sr.request_status_id = 1"; // 1 is for pending
        $stmt = $this->db->prepare($query);
        if (!$stmt) {
            die("Prepare failed: " . $this->db->error);
        }
        $stmt->bind_param('i', $providerId);
        $stmt->execute();
        $result = $stmt->get_result();
        if (!$result) {
            die("Query failed: " . $this->db->error);
        }
        $rows = $result->fetch_all(MYSQLI_ASSOC);
        return $rows;
    }
}