<?php
class UserBadgeEntity {
    public int $id;
    public int $user_id;
    public int $badge_id;
    public string $awarded_at;

    public function __construct(
        int $id = null,
        int $user_id = null,
        int $badge_id = null,
        string $awarded_at = ''
    ) {
        $this->id = $id;
        $this->user_id = $user_id;
        $this->badge_id = $badge_id;
        $this->awarded_at = $awarded_at;
    }
}
