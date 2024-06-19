<?php
class ServiceRepository extends BaseRepository {
    public function __construct() {
        parent::__construct('services', ServiceEntity::class);
    }

    public function findByUserId($userId) {
        return $this->findByCriteria(['user_id' => $userId]);
    }
}
