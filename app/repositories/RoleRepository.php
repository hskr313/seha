<?php
class RoleRepository extends BaseRepository {
    public function __construct() {
        parent::__construct('roles', RoleEntity::class);
    }

    public function getAdminID(): int {
        $result = $this->findByCriteria(['role_name' => 'admin']);
        return $result ? $result[0]->id : 1;
    }

    public function getUserID(): int {
        $result = $this->findByCriteria(['role_name' => 'user']);
        return $result ? $result[0]->id : 2;
    }
}

