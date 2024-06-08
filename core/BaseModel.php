<?php
class BaseModel {
    protected $db;

    public function __construct() {
        $this->db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if ($this->db->connect_error) {
            die("Database connection failed: " . $this->db->connect_error);
        }
    }
}

