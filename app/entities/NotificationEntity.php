<?php
class NotificationEntity {
    public int $id;
    public int $user_id;
    public string $message;
    public bool $is_read;

    public function __construct(
        int $id = null,
        int $user_id = null,
        string $message = '',
        bool $is_read = false
    ) {
        $this->id = $id;
        $this->user_id = $user_id;
        $this->message = $message;
        $this->is_read = $is_read;
    }
}
