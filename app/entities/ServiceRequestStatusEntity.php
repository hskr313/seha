<?php
class ServiceRequestStatusEntity {
    public int $id;
    public string $status_logical_value;
    public string $status_label;

    public function __construct(
        int $id = null,
        string $status_logical_value = '',
        string $status_label = ''
    ) {
        $this->id = $id;
        $this->status_logical_value = $status_logical_value;
        $this->status_label = $status_label;
    }
}
