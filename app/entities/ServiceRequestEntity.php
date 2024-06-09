<?php
class ServiceRequestEntity {
    public int $id;
    public int $service_id;
    public int $requester_id;
    public int $provider_id;
    public int $request_status_id;
    public int $requested_hours;
    public string $requested_date;

    public function __construct(
        int $id = null,
        int $service_id = null,
        int $requester_id = null,
        int $provider_id = null,
        int $request_status_id = null,
        int $requested_hours = 0,
        string $requested_date = ''
    ) {
        $this->id = $id;
        $this->service_id = $service_id;
        $this->requester_id = $requester_id;
        $this->provider_id = $provider_id;
        $this->request_status_id = $request_status_id;
        $this->requested_hours = $requested_hours;
        $this->requested_date = $requested_date;
    }
}
