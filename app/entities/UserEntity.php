<?php
class UserEntity {
    public ?int $id;
    public string $username;
    public string $password;
    public string $email;
    public int $time_credit;
    public int $role_id;

    public function __construct(
        ?int $id = null,
        string $username = '',
        string $password = '',
        string $email = '',
        int $time_credit = 10,
        int $role_id = 2
    ) {
        $this->id = $id;
        $this->username = $username;
        $this->password = $password;
        $this->email = $email;
        $this->time_credit = $time_credit;
        $this->role_id = $role_id;
    }
}
