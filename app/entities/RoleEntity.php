<?php
class RoleEntity {
    public int $id;
    public string $role_name;

    public function __construct(
        int $id = null,
        string $role_name = ''
    ) {
        $this->id = $id;
        $this->role_name = $role_name;
    }
}

