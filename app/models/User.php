<?php
class User extends BaseModel {
    public function getUsersCount() {
        $result = $this->db->query("SELECT COUNT(*) AS count FROM Users");
        return $result->fetch_assoc();
    }
}
