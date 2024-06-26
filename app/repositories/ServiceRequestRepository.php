<?php
class ServiceRequestRepository extends BaseRepository {
    public function __construct() {
        parent::__construct('service_requests', ServiceRequestEntity::class);
    }
}
