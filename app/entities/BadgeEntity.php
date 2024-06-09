<?php
class BadgeEntity {
    public int $id;
    public string $badge_logical_value;
    public string $badge_name;
    public string $description;

    public function __construct(
        int $id = null,
        string $badge_logical_value = '',
        string $badge_name = '',
        string $description = ''
    ) {
        $this->id = $id;
        $this->badge_logical_value = $badge_logical_value;
        $this->badge_name = $badge_name;
        $this->description = $description;
    }
}
