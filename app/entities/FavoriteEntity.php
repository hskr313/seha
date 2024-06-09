<?php
class FavoriteEntity {
    public int $id;
    public int $user_id;
    public int $service_id;

    public function __construct(
        int $id = null,
        int $user_id = null,
        int $service_id = null
    ) {
        $this->id = $id;
        $this->user_id = $user_id;
        $this->service_id = $service_id;
    }
}
