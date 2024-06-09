<?php
class MessageEntity {
    public int $id;
    public int $sender_id;
    public int $receiver_id;
    public string $content;
    public string $sent_at;

    public function __construct(
        int $id = null,
        int $sender_id = null,
        int $receiver_id = null,
        string $content = '',
        string $sent_at = ''
    ) {
        $this->id = $id;
        $this->sender_id = $sender_id;
        $this->receiver_id = $receiver_id;
        $this->content = $content;
        $this->sent_at = $sent_at;
    }
}
